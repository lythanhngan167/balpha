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
$controlGroupClass      = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('form-control');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
$methods                =  os_payments::getPaymentMethods();
$user                   = JFactory::getUser();
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script type="text/javascript">
	Eshop.jQuery(document).ready(function($){
		$(".colorbox").colorbox({
			overlayClose: true,
			opacity: 0.5,
		});
	});
</script>
<?php
if (count($methods))
{
	?>
	<div class="payment_method_wrap">
		<p><?php echo JText::_('ESHOP_PAYMENT_METHOD_TITLE'); ?></p>
		<?php
		for ($i = 0 , $n = count($methods); $i < $n; $i++)
		{
			$checked = '';
			$paymentMethod = $methods[$i];
			if ($this->paymentMethod != '')
			{
				if ($paymentMethod->getName() == $this->paymentMethod)
				{
					$checked = ' checked="checked" ';
				}
			}
			else
			{
				if ($i == 0)
				{
					$checked = ' checked="checked" ';
				}
			}
			?>
			<label class="radio payment_method_radio">
				<input type="radio" name="payment_method" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> />
					<?php
					if ($paymentMethod->iconUri != '')
					{
						?>
						<img alt="<?php echo $paymentMethod->title; ?>" src="<?php echo $paymentMethod->iconUri; ?>" />
						<?php
					}
					else
					{
						echo JText::_($paymentMethod->title);
					}
					?>
				<br />
			</label>
			<?php
            $params = $paymentMethod->getParams();
            if ($params['payment_info']) {?>
                <div class="payment_info"  id="<?php echo $paymentMethod->getName(); ?>">
                    <?php echo 'Tài khoản: '.$user->username ?>
                    <br>
                    <?php echo 'BizXu hiện tại: <span style="color:red;font-weight: bold">'.number_format($user->money,0,'.','.').'<span> BizXu'?>
<!--                    --><?php //var_dump(nl2br($params['payment_info'])); die; echo nl2br($params['payment_info']); ?>
                </div>
           <?php }
		}
		?>
	</div>
	<?php
}
if (EshopHelper::getConfigValue('enable_checkout_donate'))
{
	?>
	<br />
	<div>
		<p><?php echo JText::_('ESHOP_CHECKOUT_DONATE_INTRO'); ?></p>
		<?php
		if (EshopHelper::getConfigValue('donate_amounts') != '')
		{
			$donateAmounts = explode("\n", EshopHelper::getConfigValue('donate_amounts'));
			$donateExplanations = explode("\n", EshopHelper::getConfigValue('donate_explanations'));
			for ($i = 0 , $n = count($donateAmounts); $i < $n; $i++)
			{
				?>
				<label class="radio">
					<?php
					if ($donateAmounts[$i] > 0)
					{
						?>
						<input type="radio" name="donate_amount" value="<?php echo trim($donateAmounts[$i]); ?>" /> <?php echo $this->currency->format(trim($donateAmounts[$i])) . (isset($donateExplanations[$i]) && $donateExplanations[$i] != '' ? ' (' . trim($donateExplanations[$i]) . ')' : ''); ?><br />
						<?php
					}
					else
					{
						?>
						<input type="radio" checked="checked" name="donate_amount" value="<?php echo trim($donateAmounts[$i]); ?>" /> <?php echo (isset($donateExplanations[$i]) && $donateExplanations[$i] != '' ? trim($donateExplanations[$i]) : ''); ?><br />
						<?php
					}
					?>
				</label>
				<?php
			}
			?>
				<label class="radio">
					<input type="radio" name="donate_amount" value="other_amount" /><?php echo JText::_('ESHOP_DONATE_OTHER_AMOUNT'); ?><br />
				</label>
				<input type="text" name="other_amount" id="other_amount" class="input-small" />
			<?php
		}
		else
		{
			?>
			<label for="other_amount" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_DONATE_AMOUNT'); ?></label>
			<input type="text" name="other_amount" id="other_amount" class="input-small" />
			<?php
		}
		?>
	</div>
	<?php
}
if (false && EshopHelper::getConfigValue('allow_coupon'))
{
	?>
	<br />
	<div class="<?php echo $controlGroupClass; ?>">
		<label for="coupon_code" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_COUPON_TEXT'); ?></label>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" id="coupon_code" name="coupon_code" class="input-large" value="<?php echo htmlspecialchars($this->coupon_code, ENT_COMPAT, 'UTF-8'); ?>">
		</div>
	</div>
	<?php
}
if (EshopHelper::getConfigValue('allow_voucher') || EshopHelper::getConfigValue('allow_coupon'))
{
    $code = $this->coupon_code ? $this->coupon_code : $this->voucher_code;
	?>
    <div class="voucher_box <?php echo $controlGroupClass; ?>">
        <label for="voucher_code" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_VOUCHER_TEXT'); ?></label>
        <div class="<?php echo $controlsClass; ?>">
            <div class="voucher_input">
                <input type="text" id="code" name="code" class="input-large" value="<?php echo htmlspecialchars($code, ENT_COMPAT, 'UTF-8'); ?>">
                <div class="input-group-btn">
                    <button type="button" class="btn" onclick="applyVoucher();" id="apply-voucher"><i class="fa fa-check"></i> <?php echo JText::_('ESHOP_VOUCHER_APPLY'); ?></button>
                </div>
            </div>
        </div>
        <div class="code_message"></div>
    </div>
	<?php
}
?>
<br />
<div class="<?php echo $controlGroupClass; ?>">
	<label for="textarea" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_COMMENT_ORDER'); ?></label>
    <textarea rows="8" id="textarea" class="<?php echo $controlsClass; ?>" name="comment"><?php echo $this->comment; ?></textarea>
</div>
<?php
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
<!--	<div class="delivery-day">-->
<!--		<label for="textarea" >--><?php //echo JText::_('ESHOP_DELIVERY_DATE'); ?><!--</label>-->
<!--		<div class="delivery-day-input">-->
<!--			--><?php
//			$this->delivery_date = date('Y-m-d', strtotime('+1 day', time()));
//			echo JHtml::_('calendar', $this->delivery_date ? $this->delivery_date : '', 'delivery_date', 'delivery_date', '%Y-%m-%d', array('class'=>'payment-calendar','readonly'=>'readonly')); ?>
<!--		</div>-->
<!--	</div>-->
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
<?php
if (EshopHelper::getConfigValue('show_privacy_policy_checkbox'))
{
    ?>
    <div class="<?php echo $controlGroupClass; ?> eshop-privacy-policy">
    	<div class="<?php echo $controlLabelClass; ?>">
        	<?php
        	if (isset($this->privacyPolicyArticleLink) && $this->privacyPolicyArticleLink != '')
        	{
        	    ?>
        	    <a class="colorbox cboxElement" href="<?php echo $this->privacyPolicyArticleLink; ?>"><?php echo JText::_('ESHOP_PRIVACY_POLICY'); ?></a>
        	    <?php
        	}
        	else
        	{
        	    echo JText::_('ESHOP_PRIVACY_POLICY');
        	}
        	?>
    	</div>
    	<div class="<?php echo $controlsClass; ?>">
    		<input type="checkbox" name="privacy_policy_agree" value="1" />
			<?php
			$agreePrivacyPolicyMessage = JText::_('ESHOP_AGREE_PRIVACY_POLICY_MESSAGE');

			if (strlen($agreePrivacyPolicyMessage))
			{
			?>
                <div class="eshop-agree-privacy-policy-message alert alert-info"><?php echo $agreePrivacyPolicyMessage;?></div>
			<?php
			}
			?>
    	</div>
    </div>
    <?php
}

if (EshopHelper::getConfigValue('acymailing_integration') || EshopHelper::getConfigValue('mailchimp_integration'))
{
    ?>
    <div class="<?php echo $controlGroupClass; ?> eshop-newsletter-interest">
    	<label for="textarea" class="checkbox">
    		<input type="checkbox" value="1" name="newsletter_interest" /><?php echo JText::_('ESHOP_NEWSLETTER_INTEREST'); ?>
    	</label>
    </div>
    <?php
}

if (isset($this->checkoutTermsLink) && $this->checkoutTermsLink != '')
{
    ?>
    <div class="<?php echo $controlGroupClass; ?> eshop-checkout-terms">
    	<label for="textarea" class="checkbox">
    		<input type="checkbox" value="1" name="checkout_terms_agree" <?php echo ($this->checkout_terms_agree) ? $this->checkout_terms_agree : ''; ?>/>
			<?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE'); ?>&nbsp;<a class="colorbox cboxElement" href="<?php echo $this->checkoutTermsLink; ?>"><?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE_TITLE'); ?></a>
    	</label>
    </div>
    <?php
}
?>
<button type="button" class="btn btn-outline-warning" style="display: none" id="button-payment-method" >Lưu lại <?php // echo JText::_('ESHOP_CONTINUE'); ?></button>
