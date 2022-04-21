<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopAddressDao;
use api\model\form\shop\AddressForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopupdateaddress extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopupdateaddress/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shoporders",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
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
        $form = new AddressForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $user = JFactory::getUser();
            $formData = $form->toArray();
            $formData['customer_id'] = $user->id;
            $formData['firstname'] = $formData['name'];
            $formData['email'] = $user->email;
            $formData['telephone'] = $formData['phone'];
            $dao = new ShopAddressDao();
            if ($dao->upsert($formData)) {

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
