<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopStockUserDao;
use api\model\dao\UserDao;
use api\model\dao\shop\ShopOrderDetailDao;
use api\model\dao\shop\ShopOrderProductDao;
use api\model\form\shop\OrderForm;
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

class UsersApiResourceShopcancelorder extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopcancelorder/';

        return $routes;
    }

    /**
     * @OA\Get(
     *     path="/api/users/shoporderdetail",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/OrderForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/OrderForm"),
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
        $user = JFactory::getUser();
        $config = new Sconfig();
        $data = $this->getRequestData();
        $db = JFactory::getDbo();
        $sql = 'SELECT * FROM #__eshop_orders WHERE id = ' . (int)$data['id'] . ' AND customer_id = ' . (int)$user->id;
        $order = $db->setQuery($sql)->loadAssoc();
        if ($order) {
            $canCancel = SUtil::canCancelOrder($order['order_status_id'], $order['payment_status']);
            if ($canCancel) {
                $sql = 'UPDATE #__eshop_orders SET order_status_id = ' . (int)$config->orderCancleStatus . ' WHERE id =' . (int)$data['id'];
                $db->setQuery($sql)->execute();
                \EshopHelper::trackHistory(array('id' => $data['id'], 'order_status_id' => (int)$config->orderCancleStatus));
                // Check sys cancel order
                if (EshopHelper::checkSysCancelOrder($order['id'])) {
                    $cancelFile = EshopHelper::makeCancelOrderXml($order['id']);
                    if ($cancelFile) {
                        EshopHelper::syncOrder($cancelFile);
                    }
                }


                $this->plugin->setResponse('Hủy đơn hàng thành công.');
                return true;
            }
        }
        ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
        return false;
    }
}
