<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport( 'joomla.access.access' );

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Recharge.
 *
 * @since  1.6
 */
class RechargeViewRecharges extends \Joomla\CMS\MVC\View\HtmlView
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
		$this->user = JFactory::getUser();
		$this->userGroup = JAccess::getGroupsByUser($this->user->id, false);

		if($_GET['ajax'] == '1'){
			//ajax
			$model = $this->getModel('Recharges', 'RechargeModel');
			if($_GET['type'] == 'confirm' && $_GET['code'] !='' && $_GET['amount'] !=''){
				$id = $_GET['id'];
				$code = $_GET['code'];
				$amount = $_GET['amount'];
				$status = $_GET['status'];
				$uid= $_GET['uid'];
				$data = array();
				$data['id'] = $id;
				$data['code'] = $code;
				$data['amount'] = $amount;
				$data['status'] = $status;
				$data['uid'] = $uid;


				$user = JFactory::getUser();
				if($user->id > 0){
					$data['user_id'] = $user->id;
					$charge = $model->updateStatus($data);
					echo $charge;
				}else{
					echo "-1";
				}
			}
			exit();
		}else{
			$this->state = $this->get('State');
			$this->items = $this->get('Items');
			// print_r($this->items);

			$this->pagination = $this->get('Pagination');
	        $this->filterForm = $this->get('FilterForm');
	        $this->activeFilters = $this->get('ActiveFilters');

			// Check for errors.
			if (count($errors = $this->get('Errors')))
			{
				throw new Exception(implode("\n", $errors));
			}

			RechargeHelper::addSubmenu('recharges');

			$this->addToolbar();

			$this->sidebar = JHtmlSidebar::render();
			parent::display($tpl);
		}

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
		$canDo = RechargeHelper::getActions();

		JToolBarHelper::title(Text::_('COM_RECHARGE_TITLE_RECHARGES'), 'recharges.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/recharge';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('recharge.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('recharges.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('recharge.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('recharges.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('recharges.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'recharges.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('recharges.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('recharges.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'recharges.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('recharges.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_recharge');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_recharge&view=recharges');
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
			'a.`created_by`' => JText::_('COM_RECHARGE_RECHARGES_CREATED_BY'),
			'a.`modified_by`' => JText::_('COM_RECHARGE_RECHARGES_MODIFIED_BY'),
			'a.`bank_name`' => JText::_('COM_RECHARGE_RECHARGES_BANK_NAME'),
			'a.`amount`' => JText::_('COM_RECHARGE_RECHARGES_AMOUNT'),
			'a.`note`' => JText::_('COM_RECHARGE_RECHARGES_NOTE'),
			'a.`status`' => JText::_('COM_RECHARGE_RECHARGES_STATUS'),
			'a.`image`' => JText::_('COM_RECHARGE_RECHARGES_IMAGE'),
			'a.`created_time`' => JText::_('COM_RECHARGE_RECHARGES_CREATED_TIME'),
			'a.`code`' => JText::_('COM_RECHARGE_RECHARGES_CODE'),
			'a.`type`' => JText::_('COM_RECHARGE_RECHARGES_TYPE'),
			'a.`updated_time`' => JText::_('COM_RECHARGE_RECHARGES_UPDATED_TIME'),
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
}
