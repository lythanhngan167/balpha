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
$input  = JFactory::getApplication()->input;
$bootstrapHelper        = $this->bootstrapHelper;
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<h1 id="ask-question-title"><?php echo JText::_('ESHOP_ASK_QUESTION'); ?></h1>
<div class="ask-question-intro"><?php echo sprintf(JText::_('ESHOP_ASK_QUESTION_INTRO'), $this->item->product_name); ?></div>
<div id="ask-question-area">
	<form method="post" name="adminForm" id="adminForm" action="index.php" class="form form-horizontal">
		<div class="<?php echo $controlGroupClass; ?>">
			<label class="<?php echo $controlLabelClass; ?>" for="name"><span class="required">*</span><?php echo JText::_('ESHOP_NAME'); ?>:</label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" class="input-large" name="name" id="name" value="" />
				<span style="display: none;" class="error name-required"><?php echo JText::_('ESHOP_NAME_REQUIRED'); ?></span>
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label class="<?php echo $controlLabelClass; ?>" for="email"><span class="required">*</span><?php echo JText::_('ESHOP_EMAIL'); ?>:</label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" class="input-large" name="email" id="email" value="" />
				<span style="display: none;" class="error email-required"><?php echo JText::_('ESHOP_EMAIL_REQUIRED'); ?></span>
				<span style="display: none;" class="error email-invalid"><?php echo JText::_('ESHOP_EMAIL_INVALID'); ?></span>
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label class="<?php echo $controlLabelClass; ?>" for="company"><?php echo JText::_('ESHOP_COMPANY'); ?>:</label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" class="input-large" name="company" id="company" value="" />
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label class="<?php echo $controlLabelClass; ?>" for="phone"><?php echo JText::_('ESHOP_PHONE'); ?>:</label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" class="input-large" name="phone" id="phone" value="" />
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label class="<?php echo $controlLabelClass; ?>" for="message"><span class="required">*</span><?php echo JText::_('ESHOP_MESSAGE'); ?>:</label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<textarea rows="5" cols="5" name="message" id="message"></textarea>
				<span style="display: none;" class="error message-required"><?php echo JText::_('ESHOP_MESSAGE_REQUIRED'); ?></span>
			</div>
		</div>
		<input type="hidden" name="product_id" id="product_id" value="<?php echo $input->getInt('id'); ?>" />
		<input type="button" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullLeftClass; ?>" id="button-ask-question" value="<?php echo JText::_('ESHOP_SUBMIT'); ?>" />
		<span class="wait"></span>
	</form>
</div>
<script type="text/javascript">
	function isValidEmail(emailAddress)
	{
	    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
	    return pattern.test(emailAddress);
	}
	Eshop.jQuery(function($){
		$('#button-ask-question').click(function(){
			$('#success').hide();
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var contactName = $('#name').val();
			var contactEmail = $('#email').val();
			var contactMessage = $('#message').val();
			var validated = true;
			if(contactName == '')
			{
				validated = false;
				$('.name-required').show();
			}
			else
			{
				$('.name-required').hide();
			}
			
			if(contactEmail == '')
			{
				validated = false;
				$('.email-required').show();
			}
			else if (!isValidEmail(contactEmail))
			{
				validated = false;
				$('.email-required').hide();
				$('.email-invalid').show();
			}
			else
			{
				$('.email-required').hide();
				$('.email-invalid').hide();
			}
			
			if (contactMessage == '')
			{
				validated = false;
				$('.message-required').show();
			}
			else
			{
				$('.message-required').hide();
			}
	
			if (validated)
			{
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type :'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=product.processAskQuestion<?php echo EshopHelper::getAttachedLangLink(); ?>',
					data: $('#ask-question-area input[type=\'text\'], #ask-question-area input[type=\'hidden\'], #ask-question-area input[type=\'radio\']:checked, #ask-question-area input[type=\'checkbox\']:checked, #ask-question-area select, #ask-question-area textarea'),
					beforeSend: function() {
						$('.wait').html('<img src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/images/loading.gif" alt="" />');
					},
					success : function(html) {
						$('#ask-question-area').html('<div class="success"><?php echo JText::_('ESHOP_ASK_QUESTION_SUCCESSFULLY')?></div>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});
	});
</script>