<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
?>
<form method="post" action="<?php echo JURI::base(); ?>index.php?option=com_eshop&task=currency.change">
	<div id="currency" class="eshop-currency<?php echo $params->get( 'moduleclass_sfx' ); ?>">
		<?php
		for ($i = 0; $n = count($currencies), $i < $n; $i++)
		{
			$currency = $currencies[$i];
			if ($currency->currency_code == $currencyCode)
			{
				?>
				<a title="<?php echo $currency->currency_name; ?>">
					<b><?php echo $currency->currency_code; ?></b>
				</a>
				<?php
			}
			else 
			{
				?>
				<a onclick="jQuery('input[name=\'currency_code\']').attr('value', '<?php echo $currency->currency_code; ?>'); jQuery(this).parent().parent().submit();" title="<?php echo $currency->currency_name; ?>">
					<?php echo $currency->currency_code; ?>
				</a>
				<?php
			}
		}
		?>
		<input type="hidden" value="" name="currency_code" />
		<input type="hidden" value="<?php echo base64_encode(JURI::getInstance()->toString()); ?>" name="return" />
	</div>
</form>