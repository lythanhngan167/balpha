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
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
$controlGroupClass      = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('form-control');
//$this->shipping_required = 1;
$user = JFactory::getUser();
if (isset($this->lists['address_id']))
{
	?>
	<label class="radio">
		<input type="radio" value="existing" name="payment_address" checked="checked"> <?php echo JText::_('ESHOP_EXISTING_ADDRESS'); ?>
	</label>
	<div id="payment-existing">
		<?php echo $this->lists['address_id']; ?>
	</div>
	<label class="radio">
		<input type="radio" value="new" name="payment_address"> <?php echo JText::_('ESHOP_NEW_ADDRESS'); ?>
	</label>
	<?php
}
else
{
	?>
	<input type="hidden" name="payment_address" value="new" />
	<?php
}
?>
<div id="payment-new" style="display: <?php echo (isset($this->lists['address_id']) ? 'none' : 'block'); ?>;" class="form-horizontal">
	<?php
		//echo $this->form->render();
    // field first name
    if (EshopHelper::isFieldPublished('firstname'))
    {
        echo  $this->form->getField('firstname')->getControlGroup();
    }

    // field last name
    if (EshopHelper::isFieldPublished('lastname'))
    {
        echo  $this->form->getField('lastname')->getControlGroup();
    }

    // field email
    if (EshopHelper::isFieldPublished('email')) {
        echo $this->form->getField('email')->getControlGroup();
    }

    // field country_id
    if (EshopHelper::isFieldPublished('country_id')) {
        echo $this->form->getField('country_id')->getControlGroup();
    }

    // field zone_id
    if (EshopHelper::isFieldPublished('zone_id')) {
        echo $this->form->getField('zone_id')->getControlGroup();
    }

    // field address 1
    if (EshopHelper::isFieldPublished('address_1')) {
        echo $this->form->getField('address_1')->getControlGroup();
    }
    // field address 2
    if (EshopHelper::isFieldPublished('address_2')) {
        echo $this->form->getField('address_2')->getControlGroup();
    }

    // field postcode
    if (EshopHelper::isFieldPublished('postcode')) {
        echo $this->form->getField('postcode')->getControlGroup();
    }
    // field company
    if (EshopHelper::isFieldPublished('company')) {
        echo $this->form->getField('company')->getControlGroup();
    }

    // field telephone
           // if (EshopHelper::isFieldPublished('telephone')) {
           //     echo $this->form->getField('telephone')->getControlGroup();
           // }
    // field fax
    if (EshopHelper::isFieldPublished('fax')) {
        echo $this->form->getField('fax')->getControlGroup();
    }

    // field address 2
    if (EshopHelper::isFieldPublished('postcode')) {
        echo $this->form->getField('postcode')->getControlGroup();
    }

    if (isset($this->lists['customergroup_id']))
    {
        ?>
        <div class="<?php echo $controlGroupClass; ?>">
            <label class="<?php echo $controlLabelClass; ?>" for="customergroup_id"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
            <div class="<?php echo $controlsClass; ?>  docs-input-sizes">
                <?php echo $this->lists['customergroup_id']; ?>
            </div>
        </div>
        <?php
    }
    elseif (isset($this->lists['default_customergroup_id']))
    {
        ?>
        <input type="hidden" name="customergroup_id" value="<?php echo $this->lists['default_customergroup_id']; ?>" />
        <?php
    }
    ?>
    <!-- Need refactor by refresh code -->
    <div class="form-group">
        <label class="col-md-3 control-label" for="address_1"><span class="required">*</span>Điện thoại
        </label>
        <div class="col-md-9 docs-input-sizes">
            <input type="text" name="telephone" id="telephone" value="<?php echo $user->username; ?>">
        </div>
    </div>
    <button type="button" class="btn btn-outline-warning" id="button-payment-address" >Lưu địa chỉ</button>
		<div id="manager-address">

		<input type="checkbox" id="same-address" name="same-address" value="" readonly checked> Địa chỉ Giao hàng giống địa chỉ Thanh toán, HOẶC
		<div class="m-address"><a class="item" href="khach-hang/addresses.html">Quản lý Địa chỉ</a></div>
		</div>
</div>
<script type="text/javascript">
	// Payment Address
	Eshop.jQuery(function($){
        // step2 process data
        $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
           // alert(stepDirection)
            if (stepNumber == 0){
               processCheckout()
            }
        });
			//$("#payment-new #telephone").val('<?php echo $user->userrname; ?>');
		$("#payment-new #firstname").val('<?php echo $user->name; ?>');
		$("#payment-new #email").val('<?php echo $user->email; ?>');

		$('#payment-existing select').on('change', function() {
			$("#button-payment-address").click();
		});

		$("#address_1").blur(function(){
		  if($("#address_1").val() != ''){
				$('#address_1').parent().find('.error').hide();
			}
		});

		$('input[type=radio][name=payment_address]').change(function() {
        if (this.value == 'new') {
            $("#button-payment-address").css('display','block');
        }
    });

		$('#button-payment-address').click(function(){
           processCheckout();
		});

		$('#payment-address input[name=\'payment_address\']').change(function(){
			if (this.value == 'new') {
				$('#payment-existing').hide();
				$('#payment-new').show();
			} else {
				$('#payment-existing').show();
				$('#payment-new').hide();
			}
		});
		<?php
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			?>
			$('#payment-address select[name=\'country_id\']').bind('change', function() {
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						$('.wait').remove();
						$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('.wait').remove();
					},
					success: function(json) {
						$('#country_id').parent().find('.error').hide();

						html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
						if (json['zones'] != '')
						{
							for (var i = 0; i < json['zones'].length; i++)
							{
			        			html += '<option value="' + json['zones'][i]['id'] + '"';
							//	if (json['zones'][i]['id'] == '<?php $this->payment_zone_id; ?>')
								if (json['zones'][i]['id'] == 3780)
								{
				      				html += ' selected="selected"';
				    			}
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



        // ajax process data for checkout ======
        function processCheckout(){
            $('#button-confirm').prop('disabled', true);
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                url: siteUrl + 'index.php?option=com_eshop&task=checkout.processPaymentAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
                type: 'post',
                data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'],#payment-address input[type=\'hidden\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
                dataType: 'json',
                beforeSend: function() {
                    $('#button-payment-address').attr('disabled', true);
                    $('#button-payment-address').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    $('#button-payment-address').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function(json) {
                    $('.warning, .error').remove();
										$('#shipping-address').show();



                    if (json['return']) {
                        window.location.href = json['return'];
                    } else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#payment-address .box__content-body').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
                            $('.warning').fadeIn('slow');
                        }
                        var errors = json['error'];
                        for (var field in errors)
                        {
                            errorMessage = errors[field];
                            $('#payment-address #' + field).after('<span class="error">' + errorMessage + '</span>');
                        }
                    } else {
                    // CODE PHP CHECK SHIPPING REQUIRED =============
                    <?php  if ($this->shipping_required) { // process for shipping required enable
                                // show shipping address if required address shipping
                                if (EshopHelper::getConfigValue('require_shipping_address' , 1)) {
                    ?>
                        var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                        $.ajax({
                            url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                            dataType: 'html',
                            success: function(html) {
                                $('#shipping-address .box__content-body').html(html);
                                var address_payment_id =  $('#payment-existing select').val();
                                if (address_payment_id){
                                    $('#shipping-existing select').val(address_payment_id).trigger('change');
                                }else {
                                    var address = $('#shipping-existing select option:selected').val();
                                   $('#shipping-existing select').val(address).trigger('change');
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.responseText);
                            }
                        });
                    <?php } else { // if only required shipping method and not required shipping address  ?>
                        $.ajax({
                            url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                            dataType: 'html',
                            success: function(html) {
                                $('#shipping-method .box__content-body').html(html);
                                // on click process shipping
                                $('#button-shipping-method').click();
                               // $('#button-confirm').prop('disabled', false);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.responseText)
                            }
                        });
                    <?php  } // end processing shipping
                        // end required shipping
                        } else { // if not required shipping
                    ?>
                        var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                        if (json['total'] > 0)
                        {
                            $.ajax({
                                url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                                dataType: 'html',
                                success: function(html) {
                                    $('#payment-method .box__content-body').html(html);
                                    $('#button-payment-method').click();
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.log(xhr.responseText);
                                }
                            });
                        } else {
                            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                            $.ajax({
                                url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                                dataType: 'html',
                                success: function(html) {

                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                        <?php
                        }
                        ?>
                        var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                        $.ajax({
                            url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                            dataType: 'html',
                            success: function(html) {
                                $('#payment-address .box__content-body').html(html);
                                var address_payment_id =  $('#payment-existing select').val();
                                if (address_payment_id){
                                    $('#shipping-existing select').val(address_payment_id).trigger('change');
                                }
                                //$('#button-confirm').prop('disabled', false);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        }
	});

</script>
