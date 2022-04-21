<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class CustomerViewCustomer extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state  = $this->get('State');
		$this->item   = $this->get('Data');
		$this->regis = $this->getRegistrationByID($this->item->regis_id);
		$this->params = $app->getParams('com_customer');
		$this->list_call = $this->getListHistoryCall($user->id,$this->item->id);
		$this->item2 =  $this->getCustomerDetail($this->item->id);

		$this->itemID = $_REQUEST['Itemid'];

		if (!empty($this->item))
		{

		$this->item->category_id_title = & $this->getModel()->getCategoryName($this->item->category_id)->title;$this->form = $this->get('Form');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}



		if ($this->_layout == 'edit')
		{
			$authorised = $user->authorise('core.create', 'com_customer');

			if ($authorised !== true)
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
			}
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// We need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_CUSTOMER_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	public function getListHistoryCall($sale_id,$customer_id)
	 {
			 $db = JFactory::getDbo();
			 $query = $db->getQuery(true);
			 $query
					 ->select('*')
					 ->from('#__notes')
					 ->where('custommer_id = ' . $customer_id, 'AND')
					 ->where('created_by = ' . $sale_id)
					->order('id DESC');
			 $db->setQuery($query);
			 return $db->loadObjectList();
	 }

	 public function getCustomerDetail($id)
 	 {
 			 $db = JFactory::getDbo();
 			 $query = $db->getQuery(true);
 			 $query
 					 ->select('*')
 					 ->from('#__customers')
 					 ->where('id = ' . $id);
 			 $db->setQuery($query);
 			 return $db->loadObject();
 	 }

	 public function getRegistrationByID($id)
		{
				$model = $this->getModel();
				$regis = $model->getRegistration($id);
				return $regis;
		}

}
