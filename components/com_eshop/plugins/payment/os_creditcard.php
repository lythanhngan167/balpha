<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	Eshop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013-2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

class os_creditcard extends os_payment {	
    /**
     * Constructor function
     *
     * @param object $config
     */    
    function __construct($params)
    {
    	$config = array(
    		'type' => 1,
    		'show_card_type' => false,
    		'show_card_holder_name' => false
    	);
    	parent::__construct($params, $config);
    }
    /**
     * Process payment with the posted data
     *
     * @param array $data array
     * @return void
     */
    function processPayment($data)
    {    	    	
    	$row = JTable::getInstance('Eshop', 'Order');    	
    	$row->load($data['order_id']);    	
    	$expDate = str_pad($data['exp_month'], 2, '0', STR_PAD_LEFT) .'/'.substr($data['exp_year'], 2, 2) ;
    	$cvv = $data['cvv_code'];
    	$params = new JRegistry($row->params);
    	$params->set('cvv', $cvv);
    	$params->set('exp_date', $expDate);
    	$params->set('card_number', substr($data['card_number'], 0, strlen($data['card_number']) - 4));
    	$row->params = $params->toString();
    	$row->store();
    	EshopHelper::completeOrder($row);
    	JPluginHelper::importPlugin('eshop');
    	JFactory::getApplication()->triggerEvent('onAfterCompleteOrder', array($row));
    	//Send confirmation email here
    	if (EshopHelper::getConfigValue('order_alert_mail'))
    	{
    		EshopHelper::sendEmails($row);
    	}
    	JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout').'&layout=complete'));
    }    
}