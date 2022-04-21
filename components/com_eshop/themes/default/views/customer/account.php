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
$name = explode(' ', $this->user->get('name'));
$firstName = isset($name[0]) ? $name[0] : '';
$lastName = isset($name[1]) ? $name[1] : '';
$bootstrapHelper        = $this->bootstrapHelper;
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.processUser'); ?>" class="form-horizontal" method="post">
	<div id="process-user">
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="firstname" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><?php echo JText::_('ESHOP_FIRST_NAME'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="firstname" id="firstname" value="<?php echo isset($this->userInfo->firstname) ? htmlspecialchars($this->userInfo->firstname, ENT_COMPAT, 'UTF-8') : htmlspecialchars($firstName, ENT_COMPAT, 'UTF-8'); ?>">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="lastname" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><?php echo JText::_('ESHOP_LAST_NAME'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="lastname" id="lastname" value="<?php echo isset($this->userInfo->lastname) ? htmlspecialchars($this->userInfo->lastname, ENT_COMPAT, 'UTF-8') : htmlspecialchars($lastName, ENT_COMPAT, 'UTF-8'); ?>">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="username" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><?php echo JText::_('ESHOP_USERNAME'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="username" id="username" value="<?php echo htmlspecialchars($this->userInfo->username, ENT_COMPAT, 'UTF-8'); ?>">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="password1" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="password" name="password1" id="password1" value="">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="password2" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_CONFIRM_PASSWORD'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="password" name="password2" id="password2" value="">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="email" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><?php echo JText::_('ESHOP_EMAIL'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="email" id="email" value="<?php echo isset($this->userInfo->email) ? htmlspecialchars($this->userInfo->email, ENT_COMPAT, 'UTF-8') : htmlspecialchars($this->user->get('email'), ENT_COMPAT, 'UTF-8'); ?>">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="telephone" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_TELEPHONE'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="telephone" id="telephone" value="<?php echo isset($this->userInfo->telephone) ? htmlspecialchars($this->userInfo->telephone, ENT_COMPAT, 'UTF-8') : ''; ?>">
			</div>
		</div>
		<div class="<?php echo $controlGroupClass; ?>">
			<label for="fax" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_FAX'); ?></label>
			<div class="<?php echo $controlsClass; ?> docs-input-sizes">
				<input type="text" name="fax" id="fax" value="<?php echo isset($this->userInfo->fax) ? htmlspecialchars($this->userInfo->fax, ENT_COMPAT, 'UTF-8') : ''; ?>">
			</div>
		</div>
		<?php
		if (isset($this->customergroup_id))
		{
			?>
			<div class="<?php echo $controlGroupClass; ?>">
				<label for="fax" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
				<div class="<?php echo $controlsClass; ?> docs-input-sizes">
					<?php echo $this->customergroup_id; ?>
				</div>
			</div>
			<?php
		}
		elseif (isset($this->default_customergroup_id))
		{
			?>
			<input type="hidden" name="customergroup_id" value="<?php echo $this->default_customergroup_id; ?>" />
			<?php
		}
		?>
	</div>
	<div class="no_margin_left <?php echo $pullLeftClass; ?>">
		<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-user-infor" class="<?php echo $btnClass; ?> btn-primary">
		<input type="button" value="<?php echo JText::_('ESHOP_SAVE'); ?>" id="button-user-infor" class="<?php echo $btnClass; ?> btn-primary">
		<input type="hidden" name="id" value="<?php echo isset($this->userInfo->id) ? $this->userInfo->id : ''; ?>">
	</div>
</form>
<script type="text/javascript">
	Eshop.jQuery(function($){
		$(document).ready(function(){
			$('#button-back-user-infor').click(function(){
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
				$(location).attr('href',url);
			});
		})
	
		//process user
		$('#button-user-infor').on('click', function() {
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=customer.processUser<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $("#adminForm").serialize(),
				dataType: 'json',
				success: function(json) {
						$('.warning, .error').remove();
						if (json['return']) {
							window.location.href = json['return'];
						} else if (json['error']) {
						//Firstname error
						if (json['error']['firstname']) {
							$('#process-user input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
						}
						//Lastname error
						if (json['error']['lastname']) {
							$('#process-user input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
						}
						//Username error
						if (json['error']['username']) {
							$('#process-user input[name=\'username\']').after('<span class="error">' + json['error']['username'] + '</span>');
						}
						if (json['error']['username_existed']) {
							$('#process-user input[name=\'username\']').after('<span class="error">' + json['error']['username_existed'] + '</span>');
						}
						//Password error
						if (json['error']['password']) {
							$('#process-user input[name=\'password1\']').after('<span class="error">' + json['error']['password'] + '</span>');
						}
						//Confirm password error
						if (json['error']['confirm']) {
							$('#process-user input[name=\'password2\']').after('<span class="error">' + json['error']['confirm'] + '</span>');
						}
						//Email validate
						if (json['error']['email']) {
							$('#process-user input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
						}
						//Email error
						if (json['error']['email_existed']) {
							$('#process-user input[name=\'email\']').after('<span class="error">' + json['error']['email_existed'] + '</span>');
						}
							
					} else {
						$('.error').remove();
						$('.warning, .error').remove();
						
					}	  
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});	
		});

		
	});
</script>