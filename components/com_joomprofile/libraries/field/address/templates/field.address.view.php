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
?>
<address>
	<?php if(@$params['show_line']) : ?>		
		<?php echo !empty($address->line) ? str_replace( "\n", "<br />", $address->line)."<br/>" : '';?>
	<?php endif; ?>	
	<?php if(@$params['show_city']) : ?>
		<?php echo !empty($address->city) ? $address->city."<br/>" : '';?>
	<?php endif; ?>			
	<?php if(@$params['show_zipcode']) : ?>		
		<?php echo !empty($address->zipcode) ? $address->zipcode."<br/>" : '';?>
	<?php endif; ?>	
	<?php if(@$params['show_state']) : ?>		
		<?php echo !empty($address->state) ? $address->state."<br/>" : '';?>
	<?php endif; ?>
	<?php if(@$params['show_country']) : ?>
		<?php echo !empty($address->country) ? $address->country."<br/>" : '';?>
	<?php endif; ?>
</address>
<?php 