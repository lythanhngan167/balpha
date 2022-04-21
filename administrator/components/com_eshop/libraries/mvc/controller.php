<?php
use Joomla\Utilities\ArrayHelper;
/**
 * @version        1.0
 * @package        OSFramework
 * @subpackage     EShopController
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopController extends JControllerLegacy
{

	private $component = '';

	private $entityName = '';

	private $langPrefix = '';

	private $viewListUrl = '';

	public function __construct($config)
	{
		parent::__construct($config);

		$input = JFactory::getApplication()->input;
		
		$this->component = $input->get('option');

		if (isset($config['entity_name']))
			$this->entityName = $config['entity_name'];
		else
			$this->entityName = $this->getEntityName();

		$this->langPrefix = ESHOP_LANG_PREFIX;

		if (isset($config['view_list_url']))
			$this->viewListUrl = $config['view_list_url'];
		else
			$this->viewListUrl = 'index.php?option=' . $this->component . '&view=' . EShopInflector::pluralize($this->entityName);

		$this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
	}

	/**
	 * Basic add function
	 */
	public function add()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view', $this->entityName);
		$input->set('edit', false);
		$this->display();
	}

	/**
	 * Basic edit function
	 */
	public function edit()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view', $this->entityName);
		$input->set('edit', true);

		$this->display();
	}

	/**
	 * Implementing Generic save function
	 */
	public function save()
	{
		$input        = new RADInput();
		$post         = $input->getData(RAD_INPUT_ALLOWRAW);
		$model        = $this->getModel($this->entityName);
		$cid          = $post['cid'];
		$post['id']   = (int) $cid[0];
		$ret          = $model->store($post);
		
		if ($ret)
		{
			$msg = JText::_($this->langPrefix . '_' . strtoupper($this->entityName) . '_SAVED');
		}
		else
		{
			$msg = JText::_($this->langPrefix . '_' . strtoupper($this->entityName) . '_SAVING_ERROR');
		}
		
		$task = $this->getTask();

		if ($task == 'save')
		{
			$url = $this->viewListUrl;
		}
		elseif ($task == 'save2new')
		{
			$url = 'index.php?option=' . $this->component . '&view=' . $this->entityName;
		}
		else
		{
			$url = $this->getEditEntityUrl($post['id']);
		}

		$this->setRedirect($url, $msg);
	}

	/**
	 * Save ordering of the record
	 */
	public function save_order()
	{
		$input = JFactory::getApplication()->input;
		$order = $input->get('order', array(), 'post');
		$cid   = $input->get('cid', array(), 'post');
		$order = ArrayHelper::toInteger($order);
		$cid = ArrayHelper::toInteger($cid);
		$model = $this->getModel($this->entityName);
		$ret   = $model->saveOrder($cid, $order);
		
		if ($ret)
		{
			$msg = JText::_($this->langPrefix . '_ORDERING_SAVED');
		}
		else
		{
			$msg = JText::_($this->langPrefix . '_ORDERING_SAVING_ERROR');
		}

		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	public function save_order_ajax()
	{
		$input = JFactory::getApplication()->input;

		// Get the input
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		$pks      = ArrayHelper::toInteger($pks);
		$order    = ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel($this->entityName);

		// Save the ordering
		$return = $model->saveOrder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		$app->close();
	}

	/**
	 * Order up an entity from the list
	 */
	public function orderup()
	{
		$model = $this->getModel($this->entityName);
		$model->move(-1);
		$msg = JText::_($this->langPrefix . '_ORDERING_UPDATED');

		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Order down an entity from the list
	 */
	public function orderdown()
	{
		$model = $this->getModel($this->entityName);
		$model->move(1);
		$msg = JText::_($this->langPrefix . '_ORDERING_UPDATED');

		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Remove entities function
	 */
	public function remove()
	{
		$input = JFactory::getApplication()->input;
		$model = $this->getModel($this->entityName);
		$cid   = $input->get('cid', array());
		$cid = ArrayHelper::toInteger($cid);
		$deletedStatus = $model->delete($cid);
		if ($deletedStatus == '0')
		{
			$msg     = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_REMOVED_ERROR');
			$msgType = 'error';
		}
		elseif ($deletedStatus == '2')
		{
			$msg     = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_REMOVED_WARNING');
			$msgType = 'notice';
		}
		else
		{
			$msg     = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_REMOVED');
			$msgType = 'message';
		}
		$this->setRedirect($this->viewListUrl, $msg, $msgType);
	}

	/**
	 * Publish entities
	 */
	public function publish()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'post');
		$cid = ArrayHelper::toInteger($cid);
		$model = &$this->getModel($this->entityName);
		$ret   = $model->publish($cid, 1);
		if ($ret)
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_PUBLISHED');
		else
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_PUBLISH_ERROR');

		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Unpublish entities
	 */
	public function unpublish()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'post');
		$cid = ArrayHelper::toInteger($cid);
		$model = &$this->getModel($this->entityName);
		$ret   = $model->publish($cid, 0);
		if ($ret)
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNPUBLISHED');
		else
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNPUBLISH_ERROR');

		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Featured entities
	 */
	public function featured()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'post');
		$cid = ArrayHelper::toInteger($cid);
		$model = &$this->getModel($this->entityName);
		$ret   = $model->featured($cid);
		if ($ret)
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_FEATURED');
		else
			$msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_FEATURED_ERROR');
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
 * Unfeatured entities
 */
    public function unfeatured()
    {
        $input = JFactory::getApplication()->input;
        $cid = $input->get('cid', array(), 'post');
        $cid = ArrayHelper::toInteger($cid);
        $model = &$this->getModel($this->entityName);
        $ret   = $model->unfeatured($cid);
        if ($ret)
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED');
        else
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED_ERROR');
        $this->setRedirect($this->viewListUrl, $msg);
    }

    /**
     * Unfeatured entities
     */
    public function extra()
    {
        $input = JFactory::getApplication()->input;
        $cid = $input->get('cid', array(), 'post');
        $task = $_POST['task'];
        $group = end(explode('.', $task));
        $cid = ArrayHelper::toInteger($cid);
        $model = &$this->getModel($this->entityName);
        $ret   = $model->extra($cid, $group);
        if ($ret)
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED');
        else
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED_ERROR');
        $this->setRedirect($this->viewListUrl, $msg);
    }

    /**
     * Unfeatured entities
     */
    public function unextra()
    {
        $input = JFactory::getApplication()->input;
        $cid = $input->get('cid', array(), 'post');
        $task = $_POST['task'];
        $group = end(explode('.', $task));
        $cid = ArrayHelper::toInteger($cid);
        $model = &$this->getModel($this->entityName);
        $ret   = $model->unextra($cid, $group);
        if ($ret)
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED');
        else
            $msg = JText::_($this->langPrefix . '_' . strtoupper(EShopInflector::pluralize($this->entityName)) . '_UNFEATURED_ERROR');
        $this->setRedirect($this->viewListUrl, $msg);
    }

	/**
	 * Copy entity function
	 */
	public function copy()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'post');
		$cid = ArrayHelper::toInteger($cid);
		$id    = $cid[0];
		$model = $this->getModel($this->entityName);
		$newId = $model->copy($id);
		$msg   = JText::_($this->langPrefix . '_' . strtoupper($this->entityName) . '_COPIED');
		if ($newId)
		{
			$url = $this->getEditEntityUrl($newId);
		}
		else
		{
			$url = $this->viewListUrl;
		}

		$this->setRedirect($url, $msg);
	}

	/**
	 * Cancel the entity .
	 * Redirect user to items list page
	 */
	public function cancel()
	{
		$this->setRedirect($this->viewListUrl);
	}

	/**
	 * Get name of entity which we are working on
	 */
	public function getEntityName()
	{
		if (empty($this->entityName))
		{
			$r = null;
			if (preg_match('/(.*)Controller(.*)/i', get_class($this), $r))
			{
				$this->entityName = strtolower($r[2]);
			}
		}

		return $this->entityName;
	}

	public function getEditEntityUrl($id = 0)
	{
        $input = JFactory::getApplication()->input;

		$url =  'index.php?option=' . $this->component . '&task=' . $this->entityName . '.edit&cid[]=' . $id;
		$stock_id = $input->get('stock_id', 0);
		if($stock_id){
		    $url .= '&stock_id='.$stock_id;
        }
		return $url;
	}
}
