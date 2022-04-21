<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\RegistrationsDao;

jimport('joomla.user.user');
jimport('joomla.access.access');

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceRegistrations extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'registrations/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    public function post()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        $userGroup = JAccess::getGroupsByUser($user->id, false);
        $result = array();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;

        if ($user->id > 0 && (int)$userGroup[0] == 3 && $user->block_landingpage == 0) {
            $params['where'][] = 'created_by = ' . (int)$user->id;
            if(isset($data['searchText'])){
                $searchText = strtoupper($data['searchText']);
                $params['where'][] = "UPPER(o.name) like UPPER('%".$searchText."%') OR UPPER(o.phone) like UPPER('%".$searchText."%')";
            }
            $dao = new RegistrationsDao();
            $result = $dao->getRegistrations($params);
        } else {
            $result['notSupport'] = true;
        }
        $this->plugin->setResponse($result);
    }
}
