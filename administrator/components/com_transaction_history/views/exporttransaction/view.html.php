<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Transaction_history
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Transaction_history.
 *
 * @since  1.6
 */
class Transaction_historyViewExporttransaction extends \Joomla\CMS\MVC\View\HtmlView
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
		$this->state         = $this->get('State');

		$month_default =  $this->state->get('filter.month', '');
		$year_default =  $this->state->get('filter.year', '');
		$day =  $this->state->get('filter.day', '');

		$this->dates = $this->getAllDateInMonth($month_default, $year_default);

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->canDo         = JHelperContent::getActions('com_users');

		$this->db            = JFactory::getDbo();

		if($_REQUEST['clear'] == 1){
			$this->state->set('filter.month', date('m'));
			$this->state->set('filter.year', date('Y'));
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_transaction_history&view=exporttransaction&filter[search]=');
		}

		//$this->listLeader = $this->getListLeader();
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		//$this->addToolbar();

		JToolBarHelper::title("Xuất Excel Lịch Sử Giao Dịch");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
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
		$canDo = Transaction_historyHelper::getActions();

		JToolBarHelper::title(Text::_('COM_TRANSACTION_HISTORY_TITLE_TRANSACTIONHISTORIES'), 'transactionhistories.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/transactionhistory';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('transactionhistory.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('transactionhistories.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('transactionhistory.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('transactionhistories.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('transactionhistories.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'transactionhistories.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('transactionhistories.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('transactionhistories.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'transactionhistories.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('transactionhistories.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_transaction_history');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_transaction_history&view=transactionhistories');
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
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`created_by`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_CREATED_BY'),
			'a.`title`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_TITLE'),
			'a.`amount`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_AMOUNT'),
			'a.`created_date`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_CREATED_DATE'),
			'a.`type_transaction`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_TYPE_TRANSACTION'),
			'a.`status`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_STATUS'),
			'a.`reference_id`' => JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_REFERENCE_ID'),
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

		//get all date of Month
		public function getAllDateInMonth($month, $year) {
			$list=array();

			if($month == '' || $year == 0) {
				return $list;
			}

			for($d=1; $d<=31; $d++)
			{
				$time=mktime(12, 0, 0, $month, $d, $year);
				if (date('m', $time)==$month)
					$list[]=date('d', $time);
			}
			return $list;
		}
}
