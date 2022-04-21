<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$fielddata = $data->fielddata;
?>
<?php $class = $fielddata->css_class;?>
<input 
	type="email"
	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
		placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
	<?php endif;?>
		
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		
	<?php $value = $data->value;?>
	<?php if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
		<?php $value = $fielddata->params['default_value'];?>
	<?php endif;?>
	value="<?php echo $value;?>"

	<?php if(isset($fielddata->params['pattern']) && !empty($fielddata->params['pattern'])): ?>
		pattern="<?php echo $fielddata->params['pattern'];?>"
		data-validation-pattern-message="<?php echo !empty($fielddata->params['pattern_message']) ? JText::_($fielddata->params['pattern_message']) : JText::_('COM_JOOMPROFILE_VALIDATION_NOT_IN_EXPECTED_FORMAT');?>"
	<?php endif;?>

	class="<?php echo $class;?>"
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	
	data-validation-ajax-ajax="<?php echo JUri::base();?>index.php?option=com_joomprofile&view=profile&task=field.validate&format=json&id=<?php echo $fielddata->id;?>&user_id=<?php echo $data->user_id;?>"
	/>

    <?php $app = JFactory::getApplication(); ?>
    <?php if($app->isSite() && isset($fielddata->params['enable_verification']) && !empty($fielddata->params['enable_verification'])): ?>
        <button type="button" class="btn" id="jp-sendvalidationcode-<?php echo $fielddata->id;?>" disabled="disabled"
                data-loading-text="<?php echo JText::_('COM_JOOMPROFILE_PROCESSING_TEXT'); ?>">
            <?php echo JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_SEND_CODE'); ?>
        </button>
        <p class="err-jp-sendvalidation-code-<?php echo $fielddata->id;?> text-error"> </p>

        <div class="jp-validate-code-<?php echo $fielddata->id;?> hide">
            <input
                   class=" jp-checkValidationCode-<?php echo $fielddata->id;?>"
                   type="text"
                   name="jp-field-validationcode-<?php echo $fielddata->id;?>"
                   id="jp-field-validationcode-<?php echo $fielddata->id;?>"
                   placeholder="<?php echo JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_VALIDATE_CODE_TEXT'); ?>">

            <button type="button" class="btn" id="jp-validationcode-<?php echo $fielddata->id;?>"
                    data-loading-text="<?php echo JText::_('COM_JOOMPROFILE_PROCESSING_TEXT'); ?>">
                <?php echo JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_VALIDATE_CODE'); ?>
            </button>
            <div class="err-jp-code-validation-<?php echo $fielddata->id;?>"></div>
        </div>
    <?php endif; ?>


<?php if($app->isSite() && isset($fielddata->params['enable_verification']) && !empty($fielddata->params['enable_verification'])): ?>

    <script type="text/javascript">
    (function($){

        $(document).ready(function(){

            $('#joomprofile-field-<?php echo $fielddata->id;?>').on('input propertychange paste', function() {
                if($(this).jqBootstrapValidation('hasErrors') === false) {
                    $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').attr('disabled', false);
                } else {
                    $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').attr('disabled', true);
                }
            });

            $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').click(function(){
                var email 	= $('input[type="email"]').val();
                var id 		= <?php echo $fielddata->id;?>;
                var user_id = <?php echo $data->user_id;?>;

                var $button = $(this)
                $button.button('loading');
                $.ajax({
                     url: "index.php?option=com_joomprofile&view=profile&task=field.trigger&triggerName=sendVerificationEmail&format=json&id="+id+"&user_id="+user_id,
                     data:{data: {value:email}},
                    type: 'POST',
                     }).done(function(data) {
                         $button.button('reset');
                         $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').text('<?php echo JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_SEND_AGAIN'); ?>');
                            $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').removeAttr('disabled');

                            data = $.parseJSON(data);
                            if(data.error == true){
                                if(data.html !== undefiend) {
                                    jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').removeClass('hide').html('<span class="danger">'+data.html+'</span>');
                                }
                            }
                            else{
                                jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').removeClass('hide').html(data.html);
                                jQuery('.jp-validate-code-<?php echo $fielddata->id;?>').removeClass('hide');
                            }
                            jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').toggleClass('text-error', data.error);
                            jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').toggleClass('text-success', !data.error);
                     }).fail(function() {
                            $button.button('reset');
                            $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').val('<?php echo JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_SEND_AGAIN');?>');
                            $('#jp-sendvalidationcode-<?php echo $fielddata->id;?>').removeAttr('disabled');
                            $('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').removeClass('hide').html('Error in coneection');
                     });
            });

            $('#jp-validationcode-<?php echo $fielddata->id;?>').click(function(){
                var id 		= <?php echo $fielddata->id;?>;
                var user_id = <?php echo $data->user_id;?>;
                var code 	= jQuery('#jp-field-validationcode-<?php echo $fielddata->id;?>').val();
                var email 	= $('input[type="email"]').val();

                var $button = $(this)
                $button.button('loading');
                 $.ajax({
                     url: "index.php?option=com_joomprofile&view=profile&task=field.trigger&triggerName=validateCode&format=json&id="+id+"&user_id="+user_id,
                      data:{data: {code:code, value:email}},
                      type: 'POST',
                     }).done(function(data) {
                         $button.button('reset');
                         $('#jp-validationcode-<?php echo $fielddata->id;?>').text('Validate');
                            $('#jp-validationcode-<?php echo $fielddata->id;?>').removeAttr('disabled');

                            data = $.parseJSON(data);
                            if(data.error == true){
                                jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').html(data.html)
                            }
                            else{
                                jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').removeClass('hide').html(data.html);
                                jQuery('.jp-validate-code-<?php echo $fielddata->id;?>').addClass('hide');

                                $('#joomprofile-field-<?php echo $fielddata->id;?>').parent().find('.help-block').html('');
                                var $field = $('#joomprofile-field-<?php echo $fielddata->id;?>')
                                $field.jqBootstrapValidation();
                                $field.trigger("change.validation", {submitting: true});
                            }
                             jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').toggleClass('text-error', data.error);
                             jQuery('.err-jp-sendvalidation-code-<?php echo $fielddata->id;?>').toggleClass('text-success', !data.error);
                         }).fail(function() {
                            $button.button('reset');
                            $('#jp-validationcode-<?php echo $fielddata->id;?>').val(Joomla.JText._('COM_JOOMPROFILE_PREEMAILVALIDATION_VALIDATE_CODE'));
                            $('#jp-validationcode-<?php echo $fielddata->id;?>').removeAttr('disabled');
                            $('.err-jp-code-validation-<?php echo $fielddata->id;?>').removeClass('hide').html('Error in coneection');
                     });
            });

        });

    })(jQuery);
	</script>
<?php endif; ?>
<?php 

