<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Registration.
 *
 * @since  1.6
 */
class RegistrationViewRegistrations extends \Joomla\CMS\MVC\View\HtmlView
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

		RegistrationHelper::addSubmenu('registrations');

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
		$canDo = RegistrationHelper::getActions();

		if($_REQUEST['filter']['duplicate_first_bca'] == 1){
			JToolBarHelper::title('Duyệt Đăng ký lại AT', 'registrations.png');
		}else{
			JToolBarHelper::title(Text::_('COM_REGISTRATION_TITLE_REGISTRATIONS'), 'registrations.png');
		}


		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/registration';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('registration.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('registrations.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('registration.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('registrations.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('registrations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'registrations.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('registrations.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('registrations.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'registrations.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('registrations.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_registration');
		}
		
		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_registration&view=registrations');
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
			'a.`created_by`' => JText::_('COM_REGISTRATION_REGISTRATIONS_CREATED_BY'),
			'a.`name`' => JText::_('COM_REGISTRATION_REGISTRATIONS_NAME'),
			'a.`email`' => JText::_('COM_REGISTRATION_REGISTRATIONS_EMAIL'),
			'a.`phone`' => JText::_('COM_REGISTRATION_REGISTRATIONS_PHONE'),
			'a.`job`' => JText::_('COM_REGISTRATION_REGISTRATIONS_JOB'),
			'a.`address`' => JText::_('COM_REGISTRATION_REGISTRATIONS_ADDRESS'),
			'a.`note`' => JText::_('COM_REGISTRATION_REGISTRATIONS_NOTE'),
			'a.`province`' => JText::_('COM_REGISTRATION_REGISTRATIONS_PROVINCE'),
			'a.`status`' => JText::_('COM_REGISTRATION_REGISTRATIONS_STATUS'),
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
		public function checkDateDuplicate($phone, $createDate){
			JLoader::import('joomla.application.component.model');
			JLoader::import( 'registrations', JPATH_ADMINISTRATOR  . '/components/com_registration/models' );
			$model = $this->getModel('Registrations', 'RegistrationModel');
			$result = $model->checkDateDuplicate($phone, $createDate);
			return $result;
		}

		public function getProjectName($id)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('title');
			$query->from($db->quoteName('#__projects'));
			$query->where($db->quoteName('id')." = ".$db->quote($id));
			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}
}
