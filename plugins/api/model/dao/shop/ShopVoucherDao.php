<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopAddressBiz;
use api\model\dao\shop\ShopCustomerDao;

class ShopVoucherDao extends AbtractDao
{
    public $select = array(
        'ad.id',
        'ad.firstname as name',
        'ad.telephone as phone',
        'ad.address_1',
        'ad.country_id',
        'ad.zone_id',
        'z.zone_name',
        'c.country_name',
        'CONCAT_WS(\', \', ad.address_1, z.zone_name, c.country_name) as address'
    );

    public function getTable()
    {
        return '#__eshop_addresses';
    }

    public function getVoucherData($code)
    {
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_vouchers')
            ->where('voucher_code = ' . $db->quote($code))
            ->where('(voucher_start_date = "0000-00-00 00:00:00" OR voucher_start_date < NOW())')
            ->where('(voucher_end_date = "0000-00-00 00:00:00" OR voucher_end_date > NOW())')
            ->where('published = 1');
        $db->setQuery($query);
        $voucher = $db->loadObject();

        if (!$voucher)
        {
            return array();
        }

        $voucherAmount = $voucher->voucher_amount;

        //Get total used amount of this voucher
        $query->clear()
            ->select('ABS(SUM(amount))')
            ->from('#__eshop_voucherhistory')
            ->where('voucher_id = ' . intval($voucher->id));
        $db->setQuery($query);
        $usedAmount = $db->loadResult();

        if ($voucherAmount <= $usedAmount)
        {
            return array();
        }

        return array(
            'voucher_id'         => $voucher->id,
            'voucher_code'       => $voucher->voucher_code,
            'voucher_amount'     => $voucherAmount - $usedAmount,
            'voucher_start_date' => $voucher->voucher_start_date,
            'voucher_end_date'   => $voucher->voucher_end_date);
    }



}
