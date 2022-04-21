<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Referral
 * @author     Truyền Đặng Minh <minhtruyen.ut@gmail.com>
 * @copyright  2021 Truyền Đặng Minh
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Referral.
 *
 * @since  1.6
 */
class ReferralViewReferrals extends \Joomla\CMS\MVC\View\HtmlView
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
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ReferralHelper::addSubmenu('referrals');

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
		$canDo = ReferralHelper::getActions();

		JToolBarHelper::title(Text::_('COM_REFERRAL_TITLE_REFERRALS'), 'referrals.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/referral';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('referral.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('referrals.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('referral.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('referrals.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('referrals.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'referrals.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('referrals.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('referrals.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'referrals.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('referrals.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_referral');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_referral&view=referrals');
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
			'a.`state`' => JText::_('JSTATUS'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`created_date`' => JText::_('COM_REFERRAL_REFERRALS_CREATED_DATE'),
			'a.`modified_date`' => JText::_('COM_REFERRAL_REFERRALS_MODIFIED_DATE'),
			'a.`cat_id`' => JText::_('COM_REFERRAL_REFERRALS_CAT_ID'),
			'a.`status_id`' => JText::_('COM_REFERRAL_REFERRALS_STATUS_ID'),
			'a.`name`' => JText::_('COM_REFERRAL_REFERRALS_NAME'),
			'a.`phone`' => JText::_('COM_REFERRAL_REFERRALS_PHONE'),
			'a.`is_exist`' => JText::_('COM_REFERRAL_REFERRALS_IS_EXIST'),
			'a.`user_id`' => JText::_('COM_REFERRAL_REFERRALS_USER_ID'),
			'a.`referral_code`' => JText::_('COM_REFERRAL_REFERRALS_REFERRAL_CODE'),
			'a.`email`' => JText::_('COM_REFERRAL_REFERRALS_EMAIL'),
			'a.`is_exist_agent`' => JText::_('COM_REFERRAL_REFERRALS_IS_EXIST_AGENT'),
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
