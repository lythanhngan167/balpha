<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */



use api\model\dao\NotificationDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceNotifydetail extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'notifydetail/';

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
        $dao = new NotificationDao();
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);

        $params = array();
        $params['where'][] = 'id = ' . (int)$id;

        $result = $dao->getContentInfo($params);
        $this->plugin->setResponse($result);

    }


}
