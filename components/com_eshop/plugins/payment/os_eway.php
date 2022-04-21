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

/**
 * Eway payment class
 *
 */
class os_eway extends EShopOmnipayPayment
{

	protected $omnipayPackage = 'Eway_Direct';
	
	/**
	 * Constructor
	 *
	 * @param JRegistry $params
	 * @param array     $config
	 */
	public function __construct($params, $config = array('type' => 1))
	{
		$config['params_map'] = array(
			'customerId'	=> 'eway_customer_id',
			'testMode'		=> 'eway_mode'
		);
			
		parent::__construct($params, $config);
	}
}