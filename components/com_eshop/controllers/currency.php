<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopControllerCurrency extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Change currency
	 */
	public function change()
	{
		$session = JFactory::getSession();

		$currencyCode = $this->input->post->getString('currency_code', null);

		if (!$session->get('currency_code') || $session->get('currency_code') != $currencyCode)
		{
			$session->set('currency_code', $currencyCode);
		}

		$cookieCurrencyCode = $this->input->cookie->getString('currency_code');

		if (!$cookieCurrencyCode || $cookieCurrencyCode != $currencyCode)
		{
			setcookie('currency_code', $currencyCode, time() + 60 * 60 * 24 * 30);

			$this->input->cookie->set('currency_code', $currencyCode);
		}

		$return = base64_decode($this->input->getString('return'));

		JFactory::getApplication()->redirect($return);
	}
}