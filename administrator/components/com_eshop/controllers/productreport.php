<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage    EShop
 * @since 1.5
 */
class EShopControllerProductreport extends JControllerLegacy
{

    /**
     * Constructor function
     *
     * @param array $config
     */
    function __construct($config = array())
    {
        parent::__construct($config);

    }

    function excel()
    {
        $input = JFactory::getApplication()->input;
        $datarange = $input->get('daterange', '', 'STRING');
        $fileName = str_replace(' ', '', $datarange);
        //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
        require(JPATH_ROOT . '/administrator/components/com_eshop/models/productreports.php');
        $model = new EShopModelProductreports(array());
        $query = $model->_buildQuery();
        $model->getDbo()->setQuery($query, 0, 100000);

        $data = $model->getDbo()->loadAssocList();
        $path = JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/Classes/PHPExcel/IOFactory.php';
        include($path);
        $objPHPExcel = new PHPExcel();
        $header = array("Tên sản phẩm", "Mã sản phẩm", "Đơn giá", "Số lượng", "Đơn vị", "Tổng tiền");
        //take new main array and set header array in it.


        $sheets = array();

        foreach ($data as $item) {
            $tmparray = array();
            array_push($tmparray, $item['product_name']);
            array_push($tmparray, (string)$item['product_sku']);
            array_push($tmparray, (string)$item['price']);
            array_push($tmparray, $item['num']);
            array_push($tmparray, $item['weight_name']);
            array_push($tmparray, $item['price'] * $item['num']);

            if (!isset($sheets[$item['manufacturer_id']])) {
                $sheets[$item['manufacturer_id']] = array(
                    'title' => $item['manufacturer_name'] ? $item['manufacturer_name'] : 'Sheet ' . $item['manufacturer_id'],
                    'data' => array($header)
                );
            }
            array_push($sheets[$item['manufacturer_id']]['data'], $tmparray);
        }
        $sheets = array_values($sheets);
        foreach ($sheets as $k => $sheet) {

            /*$objPHPExcel->createSheet();
            $sheet = $objPHPExcel->setActiveSheetIndex($sheet);
            $sheet->setTitle("$value");*/


            $objWorkSheet = $objPHPExcel->createSheet($k); //Setting index when creating
            //$objWorkSheet->setTitle($sheet['title']);
            $wSheet = $objWorkSheet->setTitle($sheet['title']);
            foreach ($sheet['data'] as $row => $columns) {
                foreach ($columns as $column => $data) {
                    if($column == 1){
                        $wSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($column) . ($row + 1))->setValueExplicit($data, PHPExcel_Cell_DataType::TYPE_STRING);
                        //$wSheet->setCellValueByColumnAndRow($column, $row + 1, $data );
                    }else{
                        $wSheet->setCellValueByColumnAndRow($column, $row + 1, $data);
                    }

                }
            }

        }
        //print_r($sheets);die;
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');


        die();

    }
}
