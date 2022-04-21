<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopCustomerDao;
use api\model\form\shop\AddressDefaultForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopdefaultaddress extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopdefaultaddress/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopdefaultaddress",
     *     tags={"Shop"},
     *     summary="Set default address",
     *     description="Set default address",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login to system",
     *         @OA\JsonContent(ref="#/components/schemas/AddressDefaultForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/AddressDefaultForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel"),
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
        $form = new AddressDefaultForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $user = JFactory::getUser();
            $params = array(
                'user_id' => $user->id,
                'address_id' => $form->id
            );
            $dao = new ShopCustomerDao();

            if ($dao->setDefaultAddress($params)) {
                $this->plugin->setResponse('');
                return true;
            } else {
                ApiError::raiseError('301', 'Error save');
                return false;
            }
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;
    }
}
