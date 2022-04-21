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
class UsersViewReports extends JViewLegacy
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

		$model = $this->getModel();
		$project_main_landingpage = $model->getProjectByID(21);
		$this->project_main_landingpage = $project_main_landingpage->price;

		$this->level1         = $model->getUsersByLevel(1);
		$this->level2         = $model->getUsersByLevel(2);
		$this->level3         = $model->getUsersByLevel(3);
		$this->level4         = $model->getUsersByLevel(4);
		$this->level5         = $model->getUsersByLevel(5);

		$this->level1Money = $model->getUsersByLevelAndMoney(1,$project_main_landingpage->price);
		$this->level2Money = $model->getUsersByLevelAndMoney(2,$project_main_landingpage->price);
		$this->level3Money = $model->getUsersByLevelAndMoney(3,$project_main_landingpage->price);
		$this->level4Money = $model->getUsersByLevelAndMoney(4,$project_main_landingpage->price);
		$this->level5Money = $model->getUsersByLevelAndMoney(5,$project_main_landingpage->price);

		$this->level1Autobuy         = $model->getUsersByLevelAndAutobuy(1);
		$this->level2Autobuy         = $model->getUsersByLevelAndAutobuy(2);
		$this->level3Autobuy         = $model->getUsersByLevelAndAutobuy(3);
		$this->level4Autobuy         = $model->getUsersByLevelAndAutobuy(4);
		$this->level5Autobuy         = $model->getUsersByLevelAndAutobuy(5);



		$this->level1AutobuyBuytotal         = $model->getUsersByLevelAndAutobuyAndBuyTotal(1,25,0);
		$this->level2AutobuyBuytotal         = $model->getUsersByLevelAndAutobuyAndBuyTotal(2,25,0);
		$this->level3AutobuyBuytotal         = $model->getUsersByLevelAndAutobuyAndBuyTotal(3,25,0);
		$this->level4AutobuyBuytotal         = $model->getUsersByLevelAndAutobuyAndBuyTotal(4,25,0);
		$this->level5AutobuyBuytotal         = $model->getUsersByLevelAndAutobuyAndBuyTotal(5,25,0);

		$this->level1AutobuyBuytotalMoney         = $model->getUsersByLevelAndAutobuyAndBuyTotal(1,25,$project_main_landingpage->price);
		$this->level2AutobuyBuytotalMoney         = $model->getUsersByLevelAndAutobuyAndBuyTotal(2,25,$project_main_landingpage->price);
		$this->level3AutobuyBuytotalMoney         = $model->getUsersByLevelAndAutobuyAndBuyTotal(3,25,$project_main_landingpage->price);
		$this->level4AutobuyBuytotalMoney         = $model->getUsersByLevelAndAutobuyAndBuyTotal(4,25,$project_main_landingpage->price);
		$this->level5AutobuyBuytotalMoney         = $model->getUsersByLevelAndAutobuyAndBuyTotal(5,25,$project_main_landingpage->price);

		$this->level1AutobuyBuytotalLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(1,25,0);
		$this->level2AutobuyBuytotalLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(2,25,0);
		$this->level3AutobuyBuytotalLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(3,25,0);
		$this->level4AutobuyBuytotalLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(4,25,0);
		$this->level5AutobuyBuytotalLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(5,25,0);

		$this->level1AutobuyBuytotalMoneyLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(1,25,$project_main_landingpage->price);
		$this->level2AutobuyBuytotalMoneyLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(2,25,$project_main_landingpage->price);
		$this->level3AutobuyBuytotalMoneyLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(3,25,$project_main_landingpage->price);
		$this->level4AutobuyBuytotalMoneyLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(4,25,$project_main_landingpage->price);
		$this->level5AutobuyBuytotalMoneyLevel1         = $model->getUsersByLevelAndAutobuyAndBuyTotalLevel1(5,25,$project_main_landingpage->price);

		$this->levelAllAutobuyBuytotalFromTo         = $model->getUsersByLevelAndAutobuyAndBuyTotalFromTo(26,1000,0);
		$this->levelAllAutobuyBuytotalMoneyFromTo         = $model->getUsersByLevelAndAutobuyAndBuyTotalFromTo(26,1000,$project_main_landingpage->price);

		$this->landingpage_channel = $model->countLandingpageChannel();
		$this->marketing_channel = $model->countMarketingChannel();




		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');

		$month_default =  $this->state->get('filter.month', '');
		$year_default =  $this->state->get('filter.year', '');

		$gr_id_default =  $this->state->get('filter.gr_id', 3);

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
			$app->redirect('index.php?option=com_users&view=reports&filter[search]=');
		}

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		//$this->addToolbar();

		JToolBarHelper::title("Thống kê Đại lý (TVV)");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);

	}

}
