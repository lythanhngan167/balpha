<?php

/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceChangeAgency extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'changeagency/';

        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        //param
        $customer_id = $data['customer_id'];
        $agency_id = $data['agency_id'];

        if (!isset($customer_id)) {
            ApiError::raiseError('100', 'Vui lòng chọn dữ liệu chuyển đổi');
            return false;
        }
        if (!isset($agency_id)) {
            ApiError::raiseError('100', 'Vui lòng chọn đại lý để chuyển đổi');
            return false;
        }
        try {
            $db->transactionStart();
            $sql = 'SELECT COUNT(*)
				FROM #__customers
                WHERE id = ' . $customer_id .
                ' AND sale_id = ' . $user->id;
            $count = $db->setQuery($sql)->loadResult();
            if ($count > 0) {
                //Update customer status and sale id
                $sql = 'UPDATE #__customers
                SET sale_id =' . $db->quote($agency_id) . ',
                status_id = 1 
                WHERE id = ' . $customer_id .
                    ' AND sale_id = ' . $user->id;
                $db->setQuery($sql)->loadResult();
                //Insert into userlogs
                $q = $db->getQuery(true);
                $now = JFactory::getDate()->toSql();
                $q->insert('#__userlogs')
                    ->columns($db->quoteName(array('customer_id', 'transfer_id', 'agent_id', 'created_date')))
                    ->values("'$customer_id', '$user->id','$agency_id','$now'");
                $db->setQuery($q);
                $db->execute();
                $db->transactionCommit();
                $this->plugin->setResponse(array(
                    "success" => true,
                    "msg" => "Đã chuyển đổi thành công"
                ));
            } else {
                ApiError::raiseError('100', 'Không tìm thấy thông tin dữ liệu khách hàng');
                return false;
            }
        } catch (Exception $e) {
            // catch any database errors.
            $db->transactionRollback();
            ApiError::raiseError('100', 'Xảy ra lỗi. ('.$e.')');
            die();
        }
    }
}
