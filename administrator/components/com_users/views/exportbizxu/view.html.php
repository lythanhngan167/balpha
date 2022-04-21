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
class UsersViewExportbizxu extends JViewLegacy
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

	 protected $form;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null){

		$this->state         = $this->get('State');
		$this->form  				 = $this->get('Form');

		$month_default =  $this->state->get('filter.month', date('m'));
		$year_default =  $this->state->get('filter.year', date('Y'));

		$from_date = $_REQUEST['jform']['from_date'];
		$to_date = $_REQUEST['jform']['to_date'];

		if($_REQUEST['clear'] == 1){
			$this->state->set('filter.month', date('m'));
			$this->state->set('filter.year', date('Y'));
			$this->state->set('filter.search', '');
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_users&view=exportbizxu&filter[search]=');
		}

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		JToolBarHelper::title("Xuất Excel Số dư BizXu Đại lý (TVV)");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);

	}

}
