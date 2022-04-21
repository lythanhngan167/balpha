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
 * View class for a list of Customer.
 *
 * @since  1.6
 */
class CustomerViewCustomers extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
		$this->state = $this->get('State');
		//$this->itemsOriginal = $this->get('ItemsOriginal');
		$this->items = $this->get('Items');

		$user = JFactory::getUser();
		$groups = \JAccess::getGroupsByUser($user->id, false);

		if($groups[0] == 15){
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_customer&view=search');
		}

		$this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		CustomerHelper::addSubmenu('customers');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');

		$canDo = CustomerHelper::getActions();
		$config = new JConfig();
		//$config->numberDayConfirmSuccessAT
		if($_REQUEST['filter']['type'] == 'accesstrade'){
			JToolBarHelper::title('Xác minh Thành công Dự án AT ( Đã mua '.$config->numberDayConfirmSuccessAT.' ngày trở lên )', 'customers.png');
		}elseif($_REQUEST['filter']['status_id'] == 99){
			JToolBarHelper::title('Xác minh Data Sọt rác', 'customers.png');
		}else{
			JToolBarHelper::title(JText::_('COM_CUSTOMER_TITLE_CUSTOMERS'), 'customers.png');
		}


		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/customer';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('customer.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('customers.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('customer.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('customers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('customers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
				JToolBarHelper::custom('customers.state', 'unpublish.png', 'unpublish_f2.png', 'reject', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'customers.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('customers.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('customers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'customers.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('customers.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_customer');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_customer&view=customers');
	}

	/**
	 * Method to order fields
	 *
	 * @return void
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`name`' => JText::_('COM_CUSTOMER_CUSTOMERS_NAME'),
			'a.`phone`' => JText::_('COM_CUSTOMER_CUSTOMERS_PHONE'),
			'a.`email`' => JText::_('COM_CUSTOMER_CUSTOMERS_EMAIL'),
			'a.`place`' => JText::_('COM_CUSTOMER_CUSTOMERS_PLACE'),
			'a.`sale_id`' => JText::_('COM_CUSTOMER_CUSTOMERS_SALE_ID'),
			'a.`category_id`' => JText::_('COM_CUSTOMER_CUSTOMERS_CATEGORY_ID'),
			'a.`project_id`' => JText::_('COM_CUSTOMER_CUSTOMERS_PROJECT_ID'),
			'a.`status_id`' => JText::_('COM_CUSTOMER_CUSTOMERS_STATUS_ID'),
			'a.`total_revenue`' => JText::_('COM_CUSTOMER_CUSTOMERS_TOTAL_REVENUE'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
		);
	}

    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }

		public function getNameUser($userid)
		{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query
						->select('*')
						->from('#__users')
						->where('id = ' .$userid);
				$db->setQuery($query);
				$result =  $db->loadObject();
				$agent_info = '';
				if($result->id > 0){
					$agent_info = "<b>".$result->username."</b><br>".$result->name;
				}else{
					$agent_info = '#';
				}
				return $agent_info;
		}

		public function checkConfirmNumberDayIsOk($buy_date){
			$model = $this->getModel();
			$checkConfirmOk = $model->checkConfirmNumberDayIsOk($buy_date);
			return $checkConfirmOk;
		}
}
