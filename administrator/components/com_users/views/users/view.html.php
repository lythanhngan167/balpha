<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport( 'joomla.access.access' );

/**
 * View class for a list of users.
 *
 * @since  1.6
 */
class UsersViewUsers extends JViewLegacy
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
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->canDo         = JHelperContent::getActions('com_users');
		$this->db            = JFactory::getDbo();

		if(@$_REQUEST['project_id']){
		    $model = $this->getModel();
		    $this->projectInfo = $model->getProjectInfo($_REQUEST['project_id']);
    }

		
		$user = JFactory::getUser();
		$this->userGroup = JAccess::getGroupsByUser($user->id, false);

		if($_REQUEST['import'] == 1){
			//administrator/index.php?option=com_users&view=users&import=1
			$getAllUser 		 = $this->get('AllUsers');
			$this->readExcelBiznetID();
			$this->readExcelInvitedID();
			echo "Xong";
			die;
		}


		UsersHelper::addSubmenu('users');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = $this->canDo;
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_USERS_VIEW_USERS_TITLE'), 'users user');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('user.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('user.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::publish('users.activate', 'COM_USERS_TOOLBAR_ACTIVATE', true);
			JToolbarHelper::unpublish('users.block', 'COM_USERS_TOOLBAR_BLOCK', true);
			JToolbarHelper::custom('users.unblock', 'unblock.png', 'unblock_f2.png', 'COM_USERS_TOOLBAR_UNBLOCK', true);
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'users.delete', 'JTOOLBAR_DELETE');
			JToolbarHelper::divider();
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_users')
			&& $user->authorise('core.edit', 'com_users')
			&& $user->authorise('core.edit.state', 'com_users'))
		{
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_users');
			JToolbarHelper::divider();
		}

		JToolbarHelper::help('JHELP_USERS_USER_MANAGER');
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.name'          => JText::_('COM_USERS_HEADING_NAME'),
			'a.username'      => JText::_('JGLOBAL_USERNAME'),
			'a.block'         => JText::_('COM_USERS_HEADING_ENABLED'),
			'a.activation'    => JText::_('COM_USERS_HEADING_ACTIVATED'),
			'a.email'         => JText::_('JGLOBAL_EMAIL'),
			'a.lastvisitDate' => JText::_('COM_USERS_HEADING_LAST_VISIT_DATE'),
			'a.registerDate'  => JText::_('COM_USERS_HEADING_REGISTRATION_DATE'),
			'a.id'            => JText::_('JGRID_HEADING_ID'),
		);
	}

	function readExcelBiznetID(){
		require_once 'Classes/PHPExcel.php';

		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.'thanhvien3.xlsx';
		$filename = $path_file;
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);

		$objReader->setReadDataOnly(true);

		/**  Load $inputFileName to a PHPExcel Object  **/
		$objPHPExcel    = $objReader->load("$filename");
		$objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow    = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$arrayError = array();
		for ($row = 2; $row <= $highestRow; $row++){
			$params = array(
				'STT' => '',
				'IDBiznet' => '',
				'HoTen' => '',
				'IDCu' => '',
				'NgayThamGia' => '',
				'DoanhThu' => '',
				'NguoiTuyenDung' => '',
				'IDNguoiTuyenDung' => '',
				'CapBacKhoiTao' => '',
				'IDThanhVienCu' => '',
				'IDNguoiTuyenDungCu' => '',
			);
			for ($col = 0; $col < $highestColumnIndex;++$col)
			{
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				$userId = 0;
				if($value){
					//$arraydata[$row-1][$col] = trim($value," ");
					if($col == 0){
						$params['STT'] = trim($value," ");
					}
					if($col == 1){
						$params['IDBiznet']	= trim($value," ");
					}
					if($col == 2){
						$params['HoTen']	= trim($value," ");
					}
					if($col == 3){
						$params['IDCu'] = trim($value," ");
					}
					if($col == 4){
						$params['NgayThamGia'] = trim($value," ");
					}
					if($col == 5){
						$params['DoanhThu'] = trim($value," ");
					}
					if($col == 6){
						$params['NguoiTuyenDung'] = trim($value," ");
					}
					if($col == 7){
						$params['IDNguoiTuyenDung'] = trim($value," ");
					}
					if($col == 8){
						$params['CapBacKhoiTao'] = trim($value," ");
					}
					if($col == 9){
						$params['IDThanhVienCu'] = trim($value," ");
					}
					if($col == 10){
						$params['IDNguoiTuyenDungCu'] = trim($value," ");
					}
				}
			}

			$fields = array(
				$db->quoteName('id_biznet') . ' = ' . $db->quote($params['IDBiznet'])
			);

			$conditions = array(
				$db->quoteName('bric_code') . ' = ' . $db->quote($params['IDThanhVienCu'])
			);
			$query->clear();
			$query->update($db->quoteName('#__users'))->set($fields)->where($conditions);

			$db->setQuery($query);
			$result = $db->execute();
		}
	}

	function readExcelInvitedID(){
		require_once 'Classes/PHPExcel.php';

		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.'thanhvien3.xlsx';
		$filename = $path_file;
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);

		$objReader->setReadDataOnly(true);

		/**  Load $inputFileName to a PHPExcel Object  **/
		$objPHPExcel    = $objReader->load("$filename");
		$objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow    = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$arrayError = array();
		for ($row = 2; $row <= $highestRow; $row++){
			$params = array(
				'STT' => '',
				'IDBiznet' => '',
				'HoTen' => '',
				'IDCu' => '',
				'NgayThamGia' => '',
				'DoanhThu' => '',
				'NguoiTuyenDung' => '',
				'IDNguoiTuyenDung' => '',
				'CapBacKhoiTao' => '',
				'IDThanhVienCu' => '',
				'IDNguoiTuyenDungCu' => '',
			);
			for ($col = 0; $col < $highestColumnIndex;++$col)
			{
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				$userId = 0;
				if($value){
					//$arraydata[$row-1][$col] = trim($value," ");
					if($col == 0){
						$params['STT'] = trim($value," ");
					}
					if($col == 1){
						$params['IDBiznet']	= trim($value," ");
					}
					if($col == 2){
						$params['HoTen']	= trim($value," ");
					}
					if($col == 3){
						$params['IDCu'] = trim($value," ");
					}
					if($col == 4){
						$params['NgayThamGia'] = trim($value," ");
					}
					if($col == 5){
						$params['DoanhThu'] = trim($value," ");
					}
					if($col == 6){
						$params['NguoiTuyenDung'] = trim($value," ");
					}
					if($col == 7){
						$params['IDNguoiTuyenDung'] = trim($value," ");
					}
					if($col == 8){
						$params['CapBacKhoiTao'] = trim($value," ");
					}
					if($col == 9){
						$params['IDThanhVienCu'] = trim($value," ");
					}
					if($col == 10){
						$params['IDNguoiTuyenDungCu'] = trim($value," ");
					}
				}
			}

			$sql = 'SELECT `id`
				FROM #__users
				WHERE id_biznet=' . $db->quote($params['IDNguoiTuyenDung']);
			$query->clear();
			$userId = (int)$db->setQuery($sql)->loadResult();

			$fields1 = array();

			if($userId > 0) {
				$fields1[] = $db->quoteName('invited_id') . ' = ' . $userId;
			}

			$fields2 = array(
				$db->quoteName('bricid_parent') . ' = ' . $db->quote($params['IDNguoiTuyenDungCu']),
				$db->quoteName('bricdatecreated') . ' = ' . $db->quote($this->standardDate($params['NgayThamGia'])),
				$db->quoteName('briclevel') . ' = ' . $db->quote($params['CapBacKhoiTao']),
			);

			$fields = array_merge($fields1, $fields2);

			$conditions = array(
				$db->quoteName('id_biznet') . ' = ' . $db->quote($params['IDBiznet'])
			);
			$query->clear();
			$query->update($db->quoteName('#__users'))->set($fields)->where($conditions);

			$db->setQuery($query);
			$updateInvite = $db->execute();
		}


	}

	function standardDate($date) {
		$day = substr($date, 0 ,2);
		$month = substr($date, 3, 2);
		$year = substr($date, 6, 4);
		return $year. '-' . $month . '-' . $day;
	}

}
