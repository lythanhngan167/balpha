<?php
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
 * @version    CVS: 1.0.0
 * @package    Com_Export_customer
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Export_customer.
 *
 * @since  1.6
 */
class Export_customerViewExportcustomers extends JViewLegacy
{
    protected $items;

    protected $pagination;

    protected $state;

    protected $form;

    /**
     * Display the view
     *
     * @param string $tpl Template name
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
        $this->form = $this->get('Form');
        jimport('phpexcel.library.PHPExcel');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        Export_customerHelper::addSubmenu('exportcustomers');

        $this->addToolbar();
        $this->project = $this->getListProject();
        $this->sidebar = JHtmlSidebar::render();
        if ($_POST) {
            $customers = $this->exportCustomer($_POST);
            if($customers){
                $this->exportExcel($customers,$_POST);
            }else{
                echo '<div style="text-align:center; color:green; font-weight:bold;">Không có dữ liệu!</div>';
            }
        }
        //print_r($_POST);
        echo '<input type="hidden" id="h_created_by" name="h_created_by" value="'.$_POST['jform']['created_by'].'" >';
        echo '<input type="hidden" id="h_month" name="h_month" value="'.$_POST['jform']['month'].'" >';
        echo '<input type="hidden" id="h_year" name="h_year" value="'.$_POST['jform']['year'].'" >';
        echo '<input type="hidden" id="h_project" name="h_project" value="'.$_POST['project'].'" >';
        echo '<input type="hidden" id="h_status_id" name="h_status_id" value="'.$_POST['status_id'].'" >';
        parent::display($tpl);
    }

    function exportExcel($customers,$post)
    {
        $objPHPExcel = new PHPExcel();

        // Set properties

        $objPHPExcel->getProperties()->setDescription("Export Customer");
        $objPHPExcel->getProperties()->setCreator("nganly");

        // Add some data
        if (count($customers) > 0) {
            $sale_name = '';
            $project_name = '';
            $status_name = '';
            $month = '';
            $year = '';

            if($post['jform']['created_by'] != ''){
              $sale_name = 'Sale:'.$this->getNameSale($post['jform']['created_by']);
            }else{
              $sale_name = 'Sale: Tất cả';
            }

            if($post['project'] != ''){
              $project_name = 'Dự án:'.$this->getNameProject($post['project']);
            }else{
              $project_name = 'Dự án: Tất cả';
            }

            if($post['status_id'] != ''){
              $status_name = 'Trạng thái:'.$this->getNameStatus($post['status_id']);
            }else{
              $status_name = 'Trạng thái: Tất cả';
            }

            if($post['jform']['month'] != ''){
              $month = 'Tháng:'.$post['jform']['month'];
            }else{
              $month = 'Tháng: Tất cả';
            }

            if($post['jform']['year'] != ''){
              $year = 'Năm:'.$post['jform']['year'];
            }else{
              $year = 'Năm: Tất cả';
            }

            $title_excel = $sale_name.' , '.$project_name.' , '.$status_name.' , '.$month.' , '.$year;

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:O1');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $title_excel);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()
            ->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
                                ->setName('Arial')
                                ->setSize(12)
                                ->getColor()->setRGB('0000FF');

            $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Họ tên');
            $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Điện thoại');
            $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Email/Facebook');
            $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Địa chỉ/Khác');
            $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Sale');
            $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Danh mục');
            $objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Dự án');
            $objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Tỉnh/TP');
            $objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Trạng thái');
            $objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Doanh thu');
            $objPHPExcel->getActiveSheet()->SetCellValue('L2', 'Ngày tạo');
            $objPHPExcel->getActiveSheet()->SetCellValue('M2', 'Ngày bán');
            $objPHPExcel->getActiveSheet()->SetCellValue('N2', 'Ngày cập nhật');
            $objPHPExcel->getActiveSheet()->SetCellValue('O2', 'Ghi chú');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O2')->setAutoSize(false);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('B2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('C2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('D2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('E2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('F2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('G2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('H2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('G2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('K2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('L2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('M2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('N2')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O2')->setWidth("120");
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('O2')->setAutoSize(false);


            foreach ($customers as $key => $customer) {
                $key = $key + 3;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $key, $customer->id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $key, $customer->name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $key, $customer->phone,PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $key, $customer->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $key, $customer->place);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $key, $this->getNameSale($customer->sale_id),PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $key, $this->getNameCat($customer->category_id));
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $key, $this->getNameProject($customer->project_id));
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $key, $this->getNameProvince($customer->province));
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $key, $this->getNameStatus($customer->status_id));
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $key, $customer->total_revenue);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $key, date('d-m-Y H:i',strtotime($customer->create_date)));
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $key, date('d-m-Y H:i',strtotime($customer->buy_date)));
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $key, date('d-m-Y H:i',strtotime($customer->modified_date)));

                $c_note = '';
                $c_note_object = $this->getNoteCustomer($customer->id);
                if($c_note_object->id > 0){
                  $c_note = $c_note_object->note;
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $key,  $c_note);
            }
        }

        // Rename sheet

        $objPHPExcel->getActiveSheet()->setTitle('Khach Hang Biznet');

        // Save Excel 2007 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'khachhangbiznet.xlsx');

        echo '<div style="text-align:center; color:green; font-weight:bold;">Xuất dữ liệu Khách hàng thành công!</div>';
        echo '<br><div style="text-align:center;"> <a href="' . JUri::base() . '/export/khachhangbiznet.xlsx"><button type="button" class="btn btn-warning">Tải File Excel về</button></a><div>';

        //$objWriter->save(str_replace('.php', '.xlsx', __FILE__));


    }

    function getNameSale($sale_id)
    {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from($db->quoteName('#__users'));
      $query->where($db->quoteName('id') . " = " . $sale_id);
      $query->where($db->quoteName('block') . " = 0");
      $db->setQuery($query);
      $result = $db->loadObject();
      return $result->username;
    }

    function getNameProject($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result->title;
    }

    function getNameCat($cat_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('id') . " = " . $cat_id);
        $query->where($db->quoteName('published') . " = 1");
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result->title;
    }

    function getNameProvince($province_id)
    {
        $province_text = JText::_('COM_CUSTOMER_FORM_PROVINCE_' . strtoupper($province_id));
        return $province_text;
    }

    function getNoteCustomer($customer_id)
    {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query
          ->select('*')
          ->from('#__notes')
          ->where('custommer_id = ' . $customer_id)
          ->order('id DESC')
         ->setLimit(1);
      $db->setQuery($query);
      return $db->loadObject();
    }

    function getNameStatus($status_id)
    {
        if ($status_id == '1') {
            return 'New (Mới)';
        }
        if ($status_id == '2') {
            return 'Shilly – Shally (Lưỡng lự)';
        }
        if ($status_id == '3') {
            return 'Interested (Quan tâm)';
        }
        if ($status_id == '4') {
            return 'Very Interested (Rất Quan tâm)';
        }
        if ($status_id == '6') {
            return 'Return (Trả lại)';
        }
        if ($status_id == '7') {
            return 'Done (Hoàn thành)';
        }
        if ($status_id == '8') {
            return 'Cancel (Hủy)';
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
        $canDo = Export_customerHelper::getActions();

        JToolBarHelper::title(JText::_('COM_EXPORT_CUSTOMER_TITLE_EXPORTCUSTOMERS'), 'exportcustomers.png');

        // Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/exportcustomer';

        if (file_exists($formPath)) {
            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('exportcustomer.add', 'JTOOLBAR_NEW');

                if (isset($this->items[0])) {
                    JToolbarHelper::custom('exportcustomers.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
                }
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('exportcustomer.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('exportcustomers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('exportcustomers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } elseif (isset($this->items[0])) {
                // If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'exportcustomers.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('exportcustomers.archive', 'JTOOLBAR_ARCHIVE');
            }

            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('exportcustomers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        // Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'exportcustomers.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } elseif ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('exportcustomers.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_export_customer');
        }

        // Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_export_customer&view=exportcustomers');
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
        );
    }

    /**
     * Check if state is set
     *
     * @param mixed $state State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }

    public function getListProject()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function getListCustomer($project_id, $status_id)
    {
        $PHPExcel = new PHPExcel();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__customers'));
        if($project_id > 0){
          $query->where($db->quoteName('project_id') . " = " . $project_id);
        }

        if($status_id == ''){

        }else{
          $query->where($db->quoteName('status_id') . " = " . $status_id);
        }

        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function exportCustomer($data)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__customers'));
        if (@$data['project'] > 0) {
            $query->where($db->quoteName('project_id') . " = " . (int)$data['project']);
        }
        if (@$data['status_id'] > 0) {
            $query->where($db->quoteName('status_id') . " = " . (int)$data['status_id']);
        }

        if (@$data['jform']['created_by'] > 0) {
            $query->where($db->quoteName('sale_id') . " = " . (int)$data['jform']['created_by']);
        }



        if (@$data['jform']['month'] > 0 || $data['jform']['year'] > 0) {

            if((int)$data['jform']['year'] > 0){
              $year = $data['jform']['year'];
              // if($data['jform']['year'] > 0){
              //   $month = "-". $data['jform']['month']);
              // }else{
              //   $month = $data['jform']['month']);
              // }
              $month = $data['jform']['month'];
              if($month != ''){
                $query->where('DATE_FORMAT(create_date, \'%Y%m\')' . " = " . $db->quote($year .$month));
              }
              if($month == ''){
                $query->where('DATE_FORMAT(create_date, \'%Y\')' . " = " . $db->quote($year));
              }
            }
        }

        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        //echo $query->__toString();die;
        $result = $db->loadObjectList();
        return $result;
    }


}
