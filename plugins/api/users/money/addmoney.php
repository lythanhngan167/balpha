<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\ContentDao;
use api\model\form\AddMoneyForm;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceAddmoney extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'addmoney/';

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
        $form = new AddMoneyForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $user = JFactory::getUser();
            $timezone = JFactory::getUser()->getTimezone();;
            $obj = new stdClass();
            $obj->code = $this->generateRandomString(10);
            $obj->state = 1;
            $obj->created_by = $user->id;
            $obj->bank_name = $form->bank;
            $obj->amount = $form->money;
            $obj->note = $form->note;
            $obj->status = 'unconfirm';
            $obj->type = 'bank_tranfer';
            $obj->created_time = JFactory::getDate()->setTimezone($timezone)->toSql(true);
            $db = JFactory::getDbo();
            $result = $db->insertObject('#__recharge', $obj, 'id');
            $this->plugin->setResponse('Yêu cầu nạp tiền thành công.');
            return true;


        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }

        ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
        return false;

    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
