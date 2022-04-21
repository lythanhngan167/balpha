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
$bootstrapHelper        = $this->bootstrapHelper;
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (isset($this->shipping_methods))
{
	?>
	<div>
		<p><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></p>
		<?php
		$firstShippingOption = true;
		foreach ($this->shipping_methods as $shippingMethod)
		{
			?>
			<div>
				<strong><?php echo $shippingMethod['title']; ?></strong><br />
				<?php
				foreach ($shippingMethod['quote'] as $quote)
				{
					$checkedStr = ' ';
					if ($quote['name'] == $this->shipping_method)
					{
						$checkedStr = ' checked = "checked" ';
					}
					else
					{
						if ($firstShippingOption)
						{
							$checkedStr = ' checked = "checked" ';
						}
					}
					$firstShippingOption = false;
					?>
					<label class="radio">
						<input type="radio" value="<?php echo $quote['name']; ?>" name="shipping_method" <?php echo $checkedStr; ?>/>
						<?php echo $quote['title'] . ($quote['text'] != '' ? ' (' . $quote['text'] . ')' : ''); ?>
					</label>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
else
{
	?>
	<div class="no-shipping-method"><?php echo JText::_('ESHOP_NO_SHIPPING_METHOD_AVAILABLE'); ?></div>
	<?php
}
if (EshopHelper::getConfigValue('delivery_date'))
{
	?>
	<script language="JavaScript" type="text/javascript">
		<?php
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
			?>
			elements = document.querySelectorAll(".field-calendar");
			for (i = 0; i < elements.length; i++) {
				JoomlaCalendar.init(elements[i]);
			}
			<?php
		}
		else
		{
			?>
			Calendar.setup({
				// Id of the input field
				inputField: "delivery_date",
				// Format of the input field
				ifFormat: "%Y-%m-%d",
				// Trigger for the calendar (button ID)
				button: "delivery_date_img",
				// Alignment (defaults to "Bl")
				align: "Tl",
				singleClick: true,
				firstDay: 0
			});
			<?php
		}
		?>
	</script>
	<div class="delivery-day">
		<label for="textarea" ><?php echo JText::_('ESHOP_DELIVERY_DATE'); ?></label>
		<div class="delivery-day-input">
			<?php
			$this->delivery_date = date('Y-m-d', strtotime('+1 day', time()));
			echo JHtml::_('calendar', $this->delivery_date ? $this->delivery_date : '', 'delivery_date', 'delivery_date', '%Y-%m-%d', array('class'=>'payment-calendar','readonly'=>'readonly')); ?>
		</div>
	</div>
	<!-- <div class="delivery-hour">
		<label for="textarea" ><?php echo JText::_('ESHOP_DELIVERY_HOUR'); ?></label>
		<select name="delivery_hour">
	  <option value="7 - 8 giờ" >7 - 8 giờ</option>
	  <option value="8 - 9 giờ">8 - 9 giờ</option>
	  <option value="9 - 10 giờ">9 - 10 giờ</option>
	  <option value="10 - 11 giờ">10 - 11 giờ</option>
		<option value="11 - 12 giờ" selected>11 - 12 giờ</option>
	</select>
	</div> -->
	<?php
}
?>
<div class="<?php echo $controlGroupClass; ?>">
	<label for="textarea" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_COMMENT_ORDER'); ?></label>
	<div class="<?php echo $controlsClass; ?>">
		<textarea rows="8" id="textarea" class="input-xlarge" name="comment"><?php echo $this->comment; ?></textarea>
	</div>
</div>
