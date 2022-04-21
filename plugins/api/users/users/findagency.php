<?php

/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');

use api\model\dao\UserDao;

class UsersApiResourceFindAgency extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'findagency/';

        return $routes;
    }

    public function get()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        $params = array();
        $params['id'] = $data['id'];
        $params['phone'] = $data['phone'];

        $dao = new UserDao();
        $result = $dao->getAgencyByIdOrPhonenumber($params['id'], $params['phone']);
        if (isset($result)) {
            $this->plugin->setResponse($result);
        }else{
            ApiError::raiseError('100', 'Không tìm thấy Đại lý. Vui lòng nhập đúng ID Biznet hoặc Số điện thoại');
            return false;
        }
    }
}
