<?php

jimport('joomla.access.access');
jimport('joomla.user.user');

defined('_JEXEC') or die('Restricted access');

use api\model\dao\UserNotificationDao;

class UsersApiResourceUserNotification extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'usernotification/';

        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $data = $this->getRequestData();
        $dao = new UserNotificationDao();
        
        $sql = 'SELECT COUNT(*) FROM #__notifications_user WHERE `user_id` = ' . $db->quote($user->id)
                    . ' AND seen_flag = ' . $db->quote(0);
        $not_seen_count = $db->setQuery($sql)->loadResult();
        $params = array();

        $params['where'][] = 'user_id =' . $db->quote($user->id);
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;

        $params['order'] = 'id DESC';
        $result['notifications'] = $dao->getContent($params);
        $result['not_seen_count'] = $not_seen_count;
        $this->plugin->setResponse($result);
    }
}
