<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopVoucherDao;
use api\model\form\shop\CouponForm;
use api\model\dao\shop\ShopCouponDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');
require_once(JPATH_SITE . '/components/com_eshop/helpers/coupon.php');

class UsersApiResourceShopcoupon extends ApiResource
{
    public $error = array();
    public $couponData;

    static public function routes()
    {
        $routes[] = 'shopcoupon/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopcoupon",
     *     tags={"User"},
     *     summary="Get customer address",
     *     description="Get customer address",
     *     operationId="get",
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
        $form = new CouponForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $result = $this->checkCoupon($form->toArray());
            if ($result) {
                $this->plugin->setResponse(array('type' => 'coupon', 'info' => $this->couponData, 'message' => JText::_('ESHOP_COUPON_APPLY_SUCCESS')));
                return true;
            } else {
                $result = $this->checkVoucher($form->code);
                if ($result) {
                    $this->plugin->setResponse(array('type' => 'voucher', 'info' => $this->voucherData, 'message' => JText::_('ESHOP_COUPON_APPLY_SUCCESS')));
                    return true;
                }
                ApiError::raiseError('200', $this->error);
                return false;
            }
            ApiError::raiseError('200', $this->error);
            return false;
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;


    }

    private function checkCoupon($params = array())
    {
        $language = \JFactory::getLanguage();
        $language->load('com_eshop', JPATH_SITE, 'vi-VN', true);
        if ($params['code'] != '') {
            $user = JFactory::getUser();
            $coupon = new ShopCouponDao();

            $couponData = $coupon->getCouponData($params);

            if (!count($couponData)) {
                $couponInfo = $coupon->getCouponInfo($params['code']);

                if (is_object($couponInfo) && $couponInfo->coupon_per_customer && !$user->get('id')) {
                    $this->error = JText::_('ESHOP_COUPON_IS_ONLY_FOR_REGISTERED_USER');
                } else {
                    $this->error = JText::_('ESHOP_COUPON_APPLY_ERROR');
                }
                return false;
            } else {
                $this->couponData = $couponData;
                return true;
            }
        }
    }

    private function checkVoucher($voucherCode)
    {
        $language = \JFactory::getLanguage();
        $language->load('com_eshop', JPATH_SITE, 'vi-VN', true);
        if ($voucherCode != '') {
            $voucher = new ShopVoucherDao();
            $voucherData = $voucher->getVoucherData($voucherCode);

            if (!count($voucherData)) {
                $this->error = JText::_('ESHOP_VOUCHER_APPLY_ERROR');
                return false;
            }
            $this->voucherData = $voucherData;
            return true;

        }
    }
}
