<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_authnet extends EShopOmnipayPayment
{
	protected $omnipayPackage = 'AuthorizeNet_AIM';

	/**
	 * Constructor
	 *
	 * @param JRegistry $params
	 * @param array     $config
	 */
	public function __construct($params, $config = array('type' => 1))
	{
		$config['params_map'] = array(
			'apiLoginId'     => 'x_login',
			'transactionKey' => 'x_tran_key',
			'developerMode'  => 'authnet_mode'
		);

		parent::__construct($params, $config);
	}
}