<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileController{
	
	/**
	 * @var Extension
	 */
	public $app = null;
	
	/**
	 * @var JInput
	 */
	protected $input = null;
	
	/**
	 * @var String RedirectUrl
	 */
	public $redirect_url = '';
	
	/**
	 * @var String Msg to be displayed while redirecting
	 */
	public $msg = '';
	
	/**
	 * @var String MsgType of msg, going to be displayed on redirection
	 */
	public $msg_type = '';
	
	/**
	 * Constructor of controller 
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
			if (!preg_match('/(.*)Controller/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_PREFIX'), 500);
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
			if (!preg_match('/Controller(.*)/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
			}
			$this->_name = strtolower($r[1]);
		}

		return $this->_name;
	}
	
	public function getObject($itemid, $config = array(), $bind = array())
	{
		return $this->app->getObject($this->getName(), $this->getPrefix(), $itemid, $config, $bind);
	}
	
	public function getId()
	{
		return $this->input->get('id', false);
	}
	
	public function getModel($name = ''){
		if(empty($name)){
			$name = $this->_name;
		}
		
		return $this->app->getModel($name);
	}
	
	public function edit()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$itemid = $this->getId();
		return $this->_edit($itemid);
	}
	
	protected function _edit($itemid)
	{
		return true;
	}
	
	public function apply()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$itemid = $this->getId();
		$data = $this->input->get('joomprofile_form', array(), 'Array');
		
		$view = $this->input->get('view');
		
		$itemid = $this->_save($itemid, $data); 
		if($itemid){
			$this->msg = JText::_("COM_F90FROFILER_RECORD_SAVED");
			$this->msg_type = 'message';	
			$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.edit&id='.$itemid; 
		}		
		// set error
		return false;
	}
	
	public function save()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$itemid = $this->getId();
		$data = $this->input->get('joomprofile_form', array(), 'Array');
		
		$view = $this->input->get('view');
		
		$itemid = $this->_save($itemid, $data); 
		if($itemid){
			$this->msg = JText::_("COM_F90FROFILER_RECORD_SAVED");
			$this->msg_type = 'message';			
		}
		else{
			$this->msg = JText::_("COM_F90FROFILER_ERROR_RECORD_SAVE");
			$this->msg_type = 'message';
		}
		
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		// set error
		return false;
	}
	
	public function save2new()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		$itemid = $this->getId();
		$data = $this->input->get('joomprofile_form', array(), 'Array');
		
		$view = $this->input->get('view');
		
		$itemid = $this->_save($itemid, $data); 
		if($itemid){
			$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.edit'; 
			$this->msg = JText::_("COM_F90FROFILER_RECORD_SAVED");
			$this->msg_type = 'message';
		}		
		else{
			$this->msg = JText::_("COM_F90FROFILER_ERROR_RECORD_SAVE");
			$this->msg_type = 'message';
			$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		}
		// set error
		return false;
	}
	
	public function cancel()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$view = $this->input->get('view');
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid'; 
		return false;
	}
	
	public function remove()
	{
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		$cids = $this->input->get('cid', array(), 'array');
		$view = $this->input->get('view');
		
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		
		if(empty($cids)){
			$this->msg = JText::_("COM_F90FROFILER_ERROR_SELECT_RECORD");
			$this->msg_type = 'warning';
			return false;
		}
		
		$model = $this->getModel();
		$filter = array(' `id` IN ('.implode(", ", $cids).') ');
		if($model->remove($filter)){
			$this->msg = count($cids).' '.JText::_("COM_F90FROFILER_ITEM_DELETED");
			$this->msg_type = 'message';
		}
		else{
			$this->msg = JText::_("COM_F90FROFILER_ERROR_DELETE");
			$this->msg_type = 'error';
		}

		return false;
	}
	
	public function publish()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		$cids = $this->input->get('cid', array(), 'array');
		$view = $this->input->get('view');
		
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		
		if(empty($cids)){
			$this->msg = JText::_("COM_F90FROFILER_ERROR_SELECT_RECORD");
			$this->msg_type = 'warning';
			return false;
		}
		
		if($this->_bool('published', 1, $cids)){
			$this->msg = count($cids).' '.JText::_("COM_F90FROFILER_ITEM_PUBLISHED");
			$this->msg_type = 'message';
		}
		else{
			$this->msg = JText::_("COM_F90FROFILER_ERROR_PUBLISH");
			$this->msg_type = 'error';
		}

		return false;
	}
	
	public function unpublish()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		$cids = $this->input->get('cid', array(), 'array');
		$view = $this->input->get('view');
		
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		
		if(empty($cids)){
			$this->msg = JText::_("COM_F90FROFILER_ERROR_SELECT_RECORD");
			$this->msg_type = 'warning';
			return false;
		}
		
		if($this->_bool('published', 0, $cids)){
			$this->msg = count($cids).' '.JText::_("COM_F90FROFILER_ITEM_UNPUBLISHED");
			$this->msg_type = 'message';
		}
		else{
			$this->msg = JText::_("COM_F90FROFILER_ERROR_UNPUBLISH");
			$this->msg_type = 'error';
		}

		return false;
	}
	
	protected function _bool($column, $value, $cids)
	{
		$model = $this->getModel();
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($model->getTableName())
				->set($db->quoteName($column).' = '.$db->quote($value))
				->where(' `id` IN ('.implode(", ", $cids).') ');
		$db->setQuery($query);
		return $db->query();
	}
	
	protected function _save($itemid, $data)
	{
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$item = $this->getObject($itemid);
		if(!$item->bind($data)->save()){
			return false;
		}
		
		return $item->getId();
	}
	
	public function grid()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		return true;
	}
	
	public function get_view($name = '', $format = '', Array $config = array())
	{
		if(empty($name)){
			$name = $this->getName();
		}
		
		if(empty($format)){
			$format = $this->input->get('format', 'html');
		}
				
		if(empty($config)){
			$config = array();
			$config['input'] = $this->input;
		}
		
		$class_name = strtolower($this->getPrefix().'View'.$format.$name);
		
		static $views = array();
		if(!isset($views[$class_name])){
			if(!class_exists($class_name, true)){
				throw new Exception(JText::sprintf('JOOMPROFILE_EXTENSION_VIEW_NOT_FOUND', $v_name, $this->name));
			}

			$views[$class_name] = new $class_name($config);
			$views[$class_name]->app = $this->app;
		}
		
		return $views[$class_name];
	}		
	
	public function order()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$id = $this->input->get('id', 0);
		if(!$id){
			// @TODO : Error
			return false;
		}
		
		$otherid = $this->input->get('otherid', 0);
		if(!$otherid){
			// @TODO : Error
			return false;
		}
		
		$model = $this->getModel();
		$item  		= $model->getItem($id);
		$otheritem  = $model->getItem($otherid);
		
		$tmp_ordering = $item->ordering;
		$item->ordering = $otheritem->ordering;
		$otheritem->ordering = $tmp_ordering;
		
		$view = $this->input->get('view');
		$this->redirect_url = 'index.php?option=com_joomprofile&view='.$view.'&task='.$this->_name.'.grid';
		
		if($model->save($id, (array)$item) && $model->save($otherid, (array)$otheritem)){
			$this->msg = 'Item has been re-ordered';
			$this->msg_type = 'message';
		}
		else{
			$this->msg = "Error in re-ordering the item.";
			$this->msg_type = 'error';
		}  
		
		return false;
	}
}