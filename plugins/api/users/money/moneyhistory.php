<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\RechargeDao;
use api\model\form\AddMoneyForm;
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceMoneyhistory extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'moneyhistory/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/home",
     *     tags={"User"},
     *     summary="Get home page",
     *     description="Get home page",
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
    public function post()
    {
        $data = $this->getRequestData();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $dao = new RechargeDao();

        $lang = JFactory::getLanguage();
        $extension = 'com_recharge';
        $base_dir = JPATH_SITE;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);


        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        if (@$data['start_date']) {
            $params['where'][] = 'created_time >= ' . $db->quote($data['start_date'].' 00:00:00');
        }
        if (@$data['end_date']) {
            $params['where'][] = 'created_time <= ' . $db->quote($data['end_date'].' 23:59:59');
        }


        $params['where'][] = 'created_by = '.$user->id;
        $params['order'] = 'id DESC';

        $result = $dao->getHistory($params);
        $this->plugin->setResponse($result);

        $app  = Factory::getApplication();
        $paramsRecharge = $app->getParams('com_recharge');
        $bank_info =  '<h3>Thông tin chuyển khoản</h3>'.$paramsRecharge['info_bank'];
        $this->plugin->set('response_id', $bank_info);

        return true;

    }



}
