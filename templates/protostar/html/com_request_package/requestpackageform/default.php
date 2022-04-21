<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//fix searching
//HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_request_package', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_request_package/js/form.js');

$user    = Factory::getUser();
$canEdit = Request_packageHelpersRequest_package::canUserEdit($this->item, $user);
//
// if (!$user->id > 0) {
// 	echo "<script>alert('Phải đăng nhập trước!');</script>";
// 	header('Location:index.php?Itemid=130');
// 	//JError::raiseError( 4711, 'A severe error occurred' );
// 	// JFactory::getApplication()->enqueueMessage('Phải đăng nhập trước', 'error');
// }
// else {
// 	$gr = 0;
// 	$groups = $user->get('groups');
// 	foreach ($groups as $group)
// 	{
// 	    $gr = $group;
// 	}
// 	if ($gr > 0) {
// 		if ($gr != 2) {
// 			// echo "<script>alert('Chức năng chỉ dành cho khách hàng!');</script>";
// 			// return false;
// 			$message = JText::sprintf('Chức năng chỉ dành cho khách hàng');
// 			$app->redirect(JRoute::_('index.php?Itemid=130', false), $message, 'error');
// 		}
// 	}
// }


?>

<div class="requestpackage-edit front-end-edit">
	<div class="requestpackage-icon">
	<i class="fa fa-life-ring" aria-hidden="true" style="font-size:24px;color:#EE7D30"></i>
	</div>
	<div class="requestpackage-title">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_REQUEST_PACKAGE_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_REQUEST_PACKAGE_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_REQUEST_PACKAGE_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>
	</div>
	<div class="requestpackage-shortdesc">Đã có 5000+ khách hàng gửi yêu cầu tư vấn và đã được chúng tôi phục vụ.</div>
	<form id="form-requestpackage"
			  action="<?php echo Route::_('index.php?option=com_request_package&task=requestpackage.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo isset($this->item->ordering) ? $this->item->ordering : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo isset($this->item->checked_out) ? $this->item->checked_out : ''; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo isset($this->item->checked_out_time) ? $this->item->checked_out_time : ''; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>

	<div class="requestpackage-col form-service"><?php echo $this->form->renderField('services'); ?></div>
	<div class="requestpackage-col form-company"><?php echo $this->form->renderField('company'); ?></div>
	<div class="requestpackage-col form-name"><?php echo $this->form->renderField('name'); ?></div>

	<div class="requestpackage-col form-email"><?php echo $this->form->renderField('email'); ?></div>

	<div class="requestpackage-col form-phone"><?php echo $this->form->renderField('phone'); ?></div>

	<div class="hidden-request">
		<?php echo $this->form->renderField('job'); ?>
	</div>

	<div class="requestpackage-col form-address"><?php echo $this->form->renderField('address'); ?></div>

	<div class="requestpackage-col form-note"><?php echo $this->form->renderField('note'); ?></div>

	<div class="requestpackage-col form-province"><?php echo $this->form->renderField('province'); ?></div>
	<div class="requestpackage-col form-counselor_id"><?php echo $this->form->renderField('counselor_id'); ?></div>

	<input type="hidden" name="url" value="	<?php echo $url; ?>"/>

<div class="hidden-request">
	<?php echo $this->form->renderField('status'); ?>
</div>



				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','request_package')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','request_package')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-requestpackage").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group requestpackage-submit">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo "Gửi yêu cầu"; ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo Route::_('index.php?option=com_request_package&task=requestpackageform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>


			<input type="hidden" name="option" value="com_request_package"/>
			<input type="hidden" name="task"
				   value="requestpackageform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
<style>
.hidden-request{
	display:none;
}
</style>

<script>
// Get parameter from URL
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

var catid = getUrlParameter('catid');
var counselor_id = getUrlParameter('user_id');
jQuery('#jform_counselor_id').val(counselor_id);

//selected catid option choose from previous page
jQuery('select#jform_services option').each(function() {
    if(jQuery(this).val() == catid) {
        jQuery(this).prop("selected", true);
    }
});



if(catid==9){
	jQuery('#jform_company').html('<option value="AIA Việt Nam">AIA Việt Nam</option><option value="Aviva Việt Nam">Aviva Việt Nam</option><option value="BIDV MetLife">BIDV MetLife</option><option value="Bảo Việt Nhân Thọ">Bảo Việt Nhân Thọ</option><option value="Cathay Life">Cathay Life</option><option value="Chubb Life Việt Nam">Chubb Life Việt Nam</option><option value="Dai-ichi Life Việt Nam">Dai-ichi Life Việt Nam</option><option value="FWD Vietnam">FWD Vietnam</option><option value="Fubon Việt Nam">Fubon Việt Nam</option><option value="Generali Việt Nam">Generali Việt Nam</option><option value="Hanwha Life Việt Nam">Hanwha Life Việt Nam</option><option value="MAP Life">MAP Life</option><option value="Manulife Việt Nam">Manulife Việt Nam</option><option value="Phú Hưng Life">Phú Hưng Life</option><option value="Prudential">Prudential</option><option value="Sun Life Việt Nam">Sun Life Việt Nam</option><option value="VCLI">VCLI</option>');
}
if(catid==10){
	jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="GIC">GIC</option><option value="Generali Việt Nam">Generali Việt Nam</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VBI">VBI</option>');
}
if(catid==11){
	jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Cathay">Cathay</option><option value="Chubb Insurance">Chubb Insurance</option><option value="GIC">GIC</option><option value="MIC">MIC</option><option value="MSIG">MSIG</option><option value="PTI">PTI</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VASS">VASS</option><option value="VBI">VBI</option><option value="XTI">XTI</option>');
}
if(catid==12){
	jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="ABIC">ABIC</option><option value="BHV">BHV</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="Bảo hiểm Phú Hưng">Bảo hiểm Phú Hưng</option><option value="GIC">GIC</option><option value="MIC">MIC</option><option value="PTI">PTI</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VBI">VBI</option><option value="VNI">VNI</option><option value="XTI">XTI</option>');
}
if(catid==13){
	jQuery('#jform_company').html('<option value="BIC">BIC</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="MIC">MIC</option><option value="VBI">VBI</option>');
}
if(catid==14){
	jQuery('#jform_company').html('<option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="Chubb Insurance">Chubb Insurance</option><option value="PTI">PTI</option>');
}
if(catid==15){
	jQuery('#jform_company').html('<option></option>');
}

	jQuery('#jform_services').on('change', function() {
	  // alert("Handler for");
		jQuery(this).prop("selected", true);
		var catid = jQuery(this).attr('value');
		if(catid==9){
			jQuery('#jform_company').html('<option value="AIA Việt Nam">AIA Việt Nam</option><option value="Aviva Việt Nam">Aviva Việt Nam</option><option value="BIDV MetLife">BIDV MetLife</option><option value="Bảo Việt Nhân Thọ">Bảo Việt Nhân Thọ</option><option value="Cathay Life">Cathay Life</option><option value="Chubb Life Việt Nam">Chubb Life Việt Nam</option><option value="Dai-ichi Life Việt Nam">Dai-ichi Life Việt Nam</option><option value="FWD Vietnam">FWD Vietnam</option><option value="Fubon Việt Nam">Fubon Việt Nam</option><option value="Generali Việt Nam">Generali Việt Nam</option><option value="Hanwha Life Việt Nam">Hanwha Life Việt Nam</option><option value="MAP Life">MAP Life</option><option value="Manulife Việt Nam">Manulife Việt Nam</option><option value="Phú Hưng Life">Phú Hưng Life</option><option value="Prudential">Prudential</option><option value="Sun Life Việt Nam">Sun Life Việt Nam</option><option value="VCLI">VCLI</option>');
		}
		if(catid==10){
			jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="GIC">GIC</option><option value="Generali Việt Nam">Generali Việt Nam</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VBI">VBI</option>');
		}
		if(catid==11){
			jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Cathay">Cathay</option><option value="Chubb Insurance">Chubb Insurance</option><option value="GIC">GIC</option><option value="MIC">MIC</option><option value="MSIG">MSIG</option><option value="PTI">PTI</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VASS">VASS</option><option value="VBI">VBI</option><option value="XTI">XTI</option>');
		}
		if(catid==12){
			jQuery('#jform_company').html('<option value="AAA">AAA</option><option value="ABIC">ABIC</option><option value="BHV">BHV</option><option value="BIC">BIC</option><option value="Bảo Long">Bảo Long</option><option value="Bảo Minh">Bảo Minh</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="Bảo hiểm Phú Hưng">Bảo hiểm Phú Hưng</option><option value="GIC">GIC</option><option value="MIC">MIC</option><option value="PTI">PTI</option><option value="Pjico">Pjico</option><option value="UIC">UIC</option><option value="VBI">VBI</option><option value="VNI">VNI</option><option value="XTI">XTI</option>');
		}
		if(catid==13){
			jQuery('#jform_company').html('<option value="BIC">BIC</option><option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="MIC">MIC</option><option value="VBI">VBI</option>');
		}
		if(catid==14){
			jQuery('#jform_company').html('<option value="Bảo hiểm Bảo Việt">Bảo hiểm Bảo Việt</option><option value="Bảo hiểm Liberty">Bảo hiểm Liberty</option><option value="Chubb Insurance">Chubb Insurance</option><option value="PTI">PTI</option>');
		}
		if(catid==15){
			jQuery('#jform_company').html('<option></option>');
		}
	});

	jQuery('#jform_name').attr('value','<?php echo $user->name; ?>');
	jQuery('#jform_name').attr('readonly','true');
	jQuery('#jform_phone').attr('value','<?php echo $user->username; ?>');
	jQuery('#jform_phone').attr('readonly','true');
	jQuery('#jform_email').attr('value','<?php echo $user->email; ?>');
	jQuery('#jform_address').attr('value','<?php echo $user->address; ?>');
	//jQuery('#jform_address').attr('readonly','true');
	<?php
	if ($user->email != '') { ?>
		jQuery('#jform_email').attr('readonly','true');
	<?php }
	if ($user->address != '') { ?>
		jQuery('#jform_address').attr('readonly','true');
	<?php }
	 ?>

	 jQuery( document ).ready(function() {
  	<?php if($this->disableForm == 1){ ?>
			jQuery("#form-requestpackage :input").prop("disabled", true);
		<?php } ?>
		});

</script>
