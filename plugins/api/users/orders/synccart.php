<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\ProjectDao;
use api\model\dao\shop\ShopProductOptionDao;
use api\model\dao\shop\ShopProductOptionDiscountDao;

require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/defines.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/list.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/checkboxes.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/countries.php');

require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/radio.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/text.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/textarea.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/zone.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/html.php');

require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/validator/validator.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/form.php');
require_once(JPATH_SITE . '/components/com_eshop/plugins/payment/os_payment.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/tables/eshop.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/voucher.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/payment.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/coupon.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/donate.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/shipping.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/discount.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/tax.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/image.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/currency.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/inflector.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/mvc/model.php');
require_once(JPATH_SITE . '/components/com_eshop/models/checkout.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/cart.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/currency.php');

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceSynccart extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'synccart/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    public function post()
    {
        $data = $this->getRequestData();
        $updateList = array();

        $optionDao = new ShopProductOptionDao();
        foreach ($data as $k => $item) {
            $info = $this->getProductInfo($item['id']);
            $updateList[$item['uid']] = array('options' => array());
            $params = array();
            $params['where'][] = 'ov.product_id = ' . (int)$item['id'];
            $params['where'][] = 'od.`language` = \'vi-VN\'';
            $options = $optionDao->getProductOptions($params);

            if ($item['op']) {
                foreach ($item['op'] as $op) {
                    foreach ($options as $new_op) {
                        foreach ($new_op->options as $sub_op) {
                            if ($op['id'] == $sub_op->id) {
                                $sub_op->selected = true;
                            }
                        }

                    }
                }
            }
            if ($info['total_weigth'] > 0) {
                $updateList[$item['uid']]['check_weight'] = true;
            } else {
                $updateList[$item['uid']]['check_quantity'] = true;
            }
            $updateList[$item['uid']]['in_stock'] = $info['product_quantity'];
            $updateList[$item['uid']]['total_weigth'] = $info['product_total_weight'];


            $productPriceArray = \EshopHelper::getProductPriceArray($item['id'], $info['product_price']);
            $result['ori_price'] = $updateList[$item['uid']]['product_price'];
            if ($productPriceArray['salePrice'] >= 0) {
                $updateList[$item['uid']]['price'] = $productPriceArray['salePrice'];
                $updateList[$item['uid']]['base_price'] = $productPriceArray['basePrice'];
            } else {
                $updateList[$item['uid']]['price'] = $productPriceArray['basePrice'];
            }


            $updateList[$item['uid']]['options'] = $options;
            $updateList[$item['uid']]['ori_price'] = $updateList[$item['uid']]['price'];
            if ($info['published'] == 0) {
                $updateList[$item['uid']]['message'] = 'Sản phẩm tạm ngưng kinh doanh.';
            }elseif($updateList[$item['uid']]['price'] != $item['ori_price']){
                $updateList[$item['uid']]['message'] = 'Đã cập nhật giá sản phẩm.';
            }
        }

        $this->plugin->setResponse(array('update_product' => $updateList));

        return true;

    }

    private function getProductInfo($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT `product_quantity`, 0 as `product_total_weight`, `product_weight`, `product_price`, `published` FROM #__eshop_products WHERE id = ' . $id;
        return $db->setQuery($sql)->loadAssoc();
    }

    private function getProductOption($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT * FROM #__eshop_productoptionvalues WHERE id = ' . $id;
        return $db->setQuery($sql)->loadAssoc();
    }

    /**
     * @OA\Get(
     *     path="/api/users/projects/{id}",
     *     tags={"Projects"},
     *     summary="Get Project information",
     *     description="Get Project information",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project id",
     *         required=false,
     *         @OA\Schema(
     *           type="int",
     *           default="null"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryBiz")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {


        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $params = array();
        $params['id'] = (int)$id;
        $params['level_id'] = $this->_getLevelId();
        $params['max_pick'] = $this->_getDefaultMaxPick();

        $dao = new ProjectDao();
        $result = $dao->getProjectInfo($params);
        $this->plugin->setResponse($result);
    }

    private function _getLevelId()
    {
        $user = JFactory::getUser();
        $level = (int)$user->get('level_tree');
        switch ($level) {
            case 1:
                $id = 167;
                break;
            case 2:
                $id = 168;
                break;
            case 3:
                $id = 169;
                break;
            case 4:
                $id = 170;
                break;
            case 5:
                $id = 171;
                break;
            default:
                $id = 0;
        }
        return $id;
    }

}
