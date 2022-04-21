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
class UsersViewSearch extends JViewLegacy
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
		$model = $this->getModel();
		$user = JFactory::getUser();
		$groups = \JAccess::getGroupsByUser($user->id, false);
		$phone_biznet_id = $_REQUEST['keyword'];
		$this->isBDM = 0;
		if($phone_biznet_id != ''){
			if($groups[0] == 15){
				$this->groupBDM = 15;
				$this->userActive = $model->getUserByPhoneOrBiznetID($phone_biznet_id);

				if($this->userActive->id > 0){
					switch($user->id){
						case 3609: //ducphuc
							$bdm_user_id = 598;
							break;
						case 3607: // huynhchi
							$bdm_user_id = 843;
							break;
						case 3608: // mytri
							$bdm_user_id = 685;
							break;
						case 3606: // ngohieu
							$bdm_user_id = 587;
							break;
						case 3610: //nguyenha
							$bdm_user_id = 634;
							break;
						case 3605: //thuha
							$bdm_user_id = 704;
							break;
						case 3611: //thuphong
							$bdm_user_id = 605;
							break;
						case 3612: //tranvanduc
							$bdm_user_id = 3373;
							break;
						case 3604: //tuyetchinh
							$bdm_user_id = 1495;
							break;
						default:
						$this->userActive = new stdClass;
					}
					$this->isBDM = 1;
					//$model->findParentUser($this->userActive->id, $bdm_user_id);
				}

			}else{
				$this->userActive = $model->getUserByPhoneOrBiznetID($phone_biznet_id);
			}

		}
		$this->db            = JFactory::getDbo();

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		//$this->addToolbar();

		JToolBarHelper::title("Tìm thông tin Đại lý (TVV) theo Số ĐT hoặc ID Biznet");
		JHtmlSidebar::addEntry(
						'',
						'#',1
				);
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);

	}

}
