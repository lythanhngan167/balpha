<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params = $data->fielddata->params;
$address = $data->address;

$display = array();
?>
	<?php if(@$params['show_line'] && !empty($address->line)) : ?>		
		<?php $display[] = str_replace( "\n", ",", $address->line);?>
	<?php endif; ?>	
	<?php if(@$params['show_city'] && !empty($address->city)) : ?>
		<?php $display[] = $address->city;?>
	<?php endif; ?>			
	<?php if(@$params['show_zipcode'] && !empty($address->zipcode)) : ?>		
		<?php $display[] = $address->zipcode;?>
	<?php endif; ?>	
	<?php if(@$params['show_state'] && !empty($address->state)) : ?>		
		<?php $display[] = $address->state;?>
	<?php endif; ?>
	<?php if(@$params['show_country'] && !empty($address->country)) : ?>
		<?php $display[] = $address->country;?>
	<?php endif; ?>
<?php 
echo implode(", ", $display);