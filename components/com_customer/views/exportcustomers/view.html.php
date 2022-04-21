<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
/**
 * View class for a list of users.
 *
 * @since  1.6
 */
class CustomerViewExportcustomers extends JViewLegacy
{
	/**
	 * The item data.
	 *
	 * @var   object
	 * @since 1.6
	 */
	protected $items;

	/**
	 * The pagination object.
	 *
	 * @var   JPagination
	 * @since 1.6
	 */
	protected $pagination;

	/**
	 * The model state.
	 *
	 * @var   JObject
	 * @since 1.6
	 */
	protected $state;

	/**
	 * A JForm instance with filter fields.
	 *
	 * @var    JForm
	 * @since  3.6.3
	 */
	 public $filterForm;

	/**
	 * An array with active filters.
	 *
	 * @var    array
	 * @since  3.6.3
	 */
	public $activeFilters;

	/**
	 * An ACL object to verify user rights.
	 *
	 * @var    JObject
	 * @since  3.6.3
	 */
	 protected $canDo;

	/**
	 * An instance of JDatabaseDriver.
	 *
	 * @var    JDatabaseDriver
	 * @since  3.6.3
	 */
	 protected $db;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null){
		$this->state         = $this->get('State');

		$customerStatus_selected =  $this->state->get('filter.customerStatus', '');
		if($customerStatus_selected == '') {
			$this->state->set('filter.customerStatus', 0);
		}

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->canDo         = JHelperContent::getActions('com_users');

		$this->db            = JFactory::getDbo();

		if($_REQUEST['clear'] == 1){
			$this->state->set('filter.customerStatus', 0);
			$this->state->set('filter.leader', '');
			$this->state->set('filter.search', '');
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_customer&view=exportcustomers&Itemid=431&filter[search]=');
		}

		//$this->listLeader = $this->getListLeader();
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		//$this->addToolbar();

		JToolBarHelper::title("Xuất Excel Landingpage");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);

	}

}
