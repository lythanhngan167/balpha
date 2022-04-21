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
$language = JFactory::getLanguage();
$tag = $language->getTag();
$bootstrapHelper        = $this->bootstrapHelper;
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (!$tag)
{
	$tag = 'en-GB';
}

if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<div class="customer-box info_order">
    <div class="customer-box__content">
        <div class="customer-box__head">
            <h1 class="customer-box__head--title"><?php echo isset($this->address->id) ? JText::_('ESHOP_ADDRESS_EDIT') : JText::_('ESHOP_ADDRESS_NEW') ; ?></h1>
        </div>
        <form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.processAddress'); ?>" method="post">
            <div id="process-address">
                <?php
                echo $this->form->render();
                ?>
                <div class="<?php echo $controlGroupClass; ?>">
                    <label class="<?php echo $controlLabelClass; ?>" for="zone_id"><?php echo JText::_('ESHOP_DEFAULT_ADDRESS'); ?></label>
                    <div class="<?php echo $controlsClass; ?> docs-input-sizes">
                        <?php echo $this->lists['default_address']; ?>
                    </div>
                </div>
                <button type="button" id="button-back-address" class="btn btn-default" ><?php echo JText::_('ESHOP_BACK'); ?></button>
                <button type="button" id="button-continue-address" class="btn btn-success" ><?php echo JText::_('ESHOP_SAVE'); ?></button>
                <input type="hidden" name="id" value="<?php echo isset($this->address->id) ? $this->address->id : ''; ?>">


								<?php if($this->phonedefault->telephone != ''){ ?>
									<input type="hidden" name="default_telephone" value="<?php echo $this->phonedefault->telephone; ?>">
									<input type="hidden" name="default_firstname" value="<?php echo $this->phonedefault->firstname; ?>">
									<input type="hidden" name="default_email" value="<?php echo $this->phonedefault->email; ?>">
								<?php } ?>
						</div>
        </form>
    </div>
</div>
<script type="text/javascript">
	Eshop.jQuery(function($){
      //  $('#country_id option[value="230"]').prop("selected", true).change();
       // $("label[for='country_id']").parent('.form-group').hide();

		$(document).ready(function(){
			
				<?php if($this->phonedefault->telephone != ''){ ?>
					$('#firstname').val('<?php echo $this->phonedefault->firstname; ?>');
					$('#email').val('<?php echo $this->phonedefault->email; ?>');
					$('#telephone').val('<?php echo $this->phonedefault->telephone; ?>');
				<?php } ?>
           // $("#country_id").val(230).change();
			$('#button-back-address').click(function() {
				var url = '<?php echo str_replace('amp;','', JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses')); ?>';
				$(location).attr('href', url);
			});

			//process user
			$('#button-continue-address').on('click', function() {
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=customer.processAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
					type: 'post',
					data: $("#adminForm").serialize(),
					dataType: 'json',
					success: function(json) {
							$('.warning, .error').remove();
							if (json['return']) {
								window.location.href = json['return'].split('amp;').join("");
							} else if (json['error']) {
								if (json['error']['warning']) {
									$('#process-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
									$('.warning').fadeIn('slow');
								}
								var errors = json['error'];
								for (var field in errors) {
									errorMessage = errors[field];
									$('#process-address #' + field).after('<span class="error">' + errorMessage + '</span>');
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
			<?php
			if (EshopHelper::isFieldPublished('zone_id'))
			{
				?>
                // $(function () {
                //     $("#country_id").val(230).change().trigger('change');
                //     $("label[for='country_id']").parent('.form-group').hide();
                // });
				$('#process-address select[name=\'country_id\']').change(function() {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
						dataType: 'json',
						beforeSend: function() {
							$('#process-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						},
						complete: function() {
							$('.wait').remove();
						},
						success: function(json) {
							html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
							if (json['zones'] != '')
							{
								for (var i = 0; i < json['zones'].length; i++)
								{
				        			html += '<option value="' + json['zones'][i]['id'] + '"';
				        			<?php
				        			if (isset($this->address->zone_id))
									{
				        				?>
				        				if (json['zones'][i]['id'] == '<?php $this->address->zone_id; ?>')
										{
						      				html += ' selected="selected"';
						    			}
				        				<?php
				        			}
				        			?>
					    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
								}
							}
							$('select[name=\'zone_id\']').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
				<?php
			}
			?>
		})
	});
</script>
