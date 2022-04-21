<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
/**
 * View class for a list of users.
 *
 * @since  1.6
 */
class UsersViewExportusers extends JViewLegacy
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
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');

		$month_default =  $this->state->get('filter.month', '');
		$year_default =  $this->state->get('filter.year', '');

		$gr_id_default =  $this->state->get('filter.gr_id', 3);

		// print_r($this->state);
		// die();

		// if($month_default == ''){
		// 	$this->state->set('filter.month', date('m'));
		// }
		// if($year_default == ''){
		// 	$this->state->set('filter.year', date('Y'));
		// }


		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->canDo         = JHelperContent::getActions('com_users');

		$this->db            = JFactory::getDbo();

		if($_REQUEST['clear'] == 1){
			$this->state->set('filter.month', date('m'));
			$this->state->set('filter.year', date('Y'));
			$this->state->set('filter.leader', '');
			$this->state->set('filter.search', '');
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_users&view=exportusers&filter[search]=');
		}

		//$this->listLeader = $this->getListLeader();
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		//$this->addToolbar();

		JToolBarHelper::title("Xuất Excel Thành viên");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);

	}

}
