<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\form\shop\VoucherForm;
use api\model\dao\shop\ShopVoucherDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');
require_once(JPATH_SITE . '/components/com_eshop/helpers/coupon.php');

class UsersApiResourceShopvoucher extends ApiResource
{
    public $error = array();
    public $voucherData;

    static public function routes()
    {
        $routes[] = 'shopvoucher/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopvoucher",
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
        $form = new VoucherForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $result = $this->checkVoucher($form->code);
            if ($result) {
                $this->plugin->setResponse($this->voucherData);
                return true;
            }
            ApiError::raiseError('200', $this->error);
            return false;
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;


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
