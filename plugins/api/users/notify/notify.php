<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\ContentDao;
use api\model\dao\NotificationDao;

jimport('joomla.access.access');
jimport('joomla.user.user');

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceNotify extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'notify/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/notify",
     *     tags={"User"},
     *     summary="Get config",
     *     description="Get config",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {
        $dao = new ContentDao();
        $data = $this->getRequestData();

        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;

        $params['where'][] = 'catid = 14';
        $params['order'] = 'id DESC';
        $result = $dao->getContent($params);
        $this->plugin->setResponse($result);

    }

    public function post()
    {
        $dao = new NotificationDao();
        $data = $this->getRequestData();
        $params = array();
        $user = JFactory::getUser();
        $userGroup = JAccess::getGroupsByUser($user->id, false);

        if($user->id) {
            switch((int)$userGroup[0]){
                case 2:
                    $params['where'][] = 'category in (' . NOTI_ALL_GROUP . ', ' . NOTI_CUSTOMER_GROUP .')';
                    break;
                case 3:
                    $params['where'][] = 'category in (' . NOTI_ALL_GROUP . ', ' . NOTI_AGENT_GROUP .')';
                    break;
            }
        } else {
            $params['where'][] = 'category in (' . NOTI_ALL_GROUP . ', ' . NOTI_CUSTOMER_GROUP .')';
        }

        
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;

        $params['order'] = 'id DESC';
        $result = $dao->getContent($params);
        $this->plugin->setResponse($result);

    }



}
