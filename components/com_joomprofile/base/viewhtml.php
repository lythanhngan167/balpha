<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileViewHtml{

	/**
	 * @var JoomprofileExtension
	 */
	public $app = null;
	
	/**
	 * @var JInput
	 */
	protected $input = null;
	
	protected $_model = null;

	/**
	 * Constructor of viewhtml 
	 * @param Array $config Set default properties
	 */
	public function __construct($config = array()){
		if(isset($config['input'])){
			$this->input = $config['input'];
		}
		else{
			$this->input = JFactory::getApplication()->input;
		}
	}
	
	public function getPrefix()
	{
		if (empty($this->_prefix))
		{
			$r = null;
			if (!preg_match('/(.*)Viewhtml/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_VIEWHTML_GET_PREFIX'), 500);
			}
			$this->_prefix = strtolower($r[1]);
		}

		return $this->_prefix;
	}
	
	public function getName()
	{
		if (empty($this->_name))
		{
			$r = null;
			if (!preg_match('/Viewhtml(.*)/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_VIEWHTML_GET_NAME'), 500);
			}
			$this->_name = strtolower($r[1]);
		}

		return $this->_name;
	}
	
	public function getModel($name = ''){
		if(empty($name)){
			$name = $this->getName();
		}
		
		return $this->app->getModel($name);
	}
	
	public function getTemplate($config = array())
	{
		return $this->app->getTemplate($config);
	}
	
	public function getId(){
		return $this->input->get('id', false);
	}
	
	protected function _grid($template)
	{
		$model = $this->getModel();
		return $model->getGridItemList();
	}
	
	public function grid()
	{
		$template = $this->getTemplate();
		$records = $this->_grid($template);	
		$model = $this->getModel();
		
		$template->set('records', $records)
				 ->set('triggered', $this->triggered)
				 ->set('pagination', $model->getPagination())
				 ->set('state', $model->getState());
		return $template->render('admin.'.$this->app->getName().'.'.$this->_name.'.grid');
	}
	
	public function edit()
	{
		$itemid = $this->getId();		
		$model 	= $this->getModel();		
		$item 	= $this->getObject($itemid);
		
		$form 	= $model->getForm($item->toArray());
		
		//set ordering
		if($form && $form->getField('ordering') != false){
			if(!$itemid){
				$db = JFactory::getDbo();
				$query = " SELECT MAX(`ordering`) FROM ".$model->getTableName();
				$db->setQuery($query);
				$ordering = $db->loadResult() + 1;
				$form->bind(array('ordering' => $ordering));
			}	
		}
		
		$template = $this->getTemplate();
				
		$this->_edit($itemid, $form, $template);
		
		$template->set('item', $item->toObject())
				 ->set('form', $form)
				 ->set('triggered', $this->triggered);
		return $template->render('admin.'.$this->app->getName().'.'.$this->_name.'.edit');
	}

	protected function _edit($itemid, $form, $template)
	{
		return true;
	}
	
	public function getObject($itemid, $config = array(), $bind = array())
	{
		return $this->app->getObject($this->getName(), $this->getPrefix(), $itemid, $config, $bind);
	}

	public function setupScript()
	{
		ob_start();
		?>
		<script>
		joomprofile.url.root = '<?php echo JUri::root();?>';
		joomprofile.url.base = '<?php echo JUri::base();?>';
		</script>
		<?php

		$config = JoomprofileExtension::get('config')->getConfig('config');
		if(!isset($config['tmpl_load_jquery']) || $config['tmpl_load_jquery']){
			JHtml::_('jquery.framework');
		}

		if(!isset($config['tmpl_load_bootstrap']) || $config['tmpl_load_bootstrap']){
			JHtml::_('bootstrap.framework');

			JHtml::_('stylesheet', 'com_joomprofile/bootstrap.min.css', array(), true);
			JHtml::_('stylesheet', 'com_joomprofile/bootstrap-responsive.min.css', array(), true);
			JHtml::_('stylesheet', 'com_joomprofile/bootstrap-extended.min.css', array(), true);
			
			$doc = JFactory::getDocument();
			if($doc->direction == 'rtl'){
				JHtml::_('stylesheet', 'jui/bootstrap-rtl.css', array(), true);
			}
		}

		$content = ob_get_contents();
		ob_end_clean();

        $component = JComponentHelper::getComponent('com_joomprofile');

        // assuming the download id provided by user is stored in component params
        // under the "update_credentials_download_id" key
        $downloadId = $component->params->get('downloadid', '');

        // bind credentials to request by appending it to the download url
        if (empty($downloadId) && JFactory::getApplication()->isAdmin()) {
            JFactory::getApplication()
                ->enqueueMessage('You need to set your <a href="'.JRoute::_('index.php?option=com_config&view=component&component=com_joomprofile', false).'">Active Subscription Key in Configuration</a> to enable autoupdate JoomProfile.', 'error');
        }
		return $content;
	}
}