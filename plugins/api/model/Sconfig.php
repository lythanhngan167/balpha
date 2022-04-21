<?php
/**
 * @package     api\model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace api\model;


class Sconfig
{
  public $evn = 'develop';
	public $hotline = '0938908432';
	public $siteName = 'eBiznet';
  	public $deeplink = 'ebiznet';
	public $onesignalAppKey = '7d2c606e-95ad-40f8-834e-4bcd085eddd9';
	public $onesignalRestKey = 'OTcyYjk0MTYtYTVlOS00NWM4LWJlZmItM2JjNTU2MWFiOTZk';
	public $address = '70 Đường Số 1, Phường 4, Quận Gò Vấp, TP. Hồ Chí Minh';
	public $service = array(
		array('text' => 'Được kiểm tra hàng trước khi thanh toán', 'icon' => 'ios-checkbox-outline'),
		array('text' => 'Đuợc giảm giá', 'icon' => 'ios-checkbox-outline'),
		array('text' => 'Giao hàng nhanh chóng', 'icon' => 'ios-checkbox-outline')

	);

}
