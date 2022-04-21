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
defined( '_JEXEC' ) or die(); 
?>
<h1><?php echo JText::_('ESHOP_PAYMENT_FAILURE_TITLE'); ?></h1>
<p>
	<?php
	$session = JFactory::getSession();
	echo $session->get('omnipay_payment_error_reason');
	?>
</p>