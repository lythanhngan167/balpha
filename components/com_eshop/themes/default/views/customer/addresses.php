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
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (!$tag)
{
	$tag = 'en-GB';
}

if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_ADDRESS_HISTORY'); ?></h1>
<?php
if (!count($this->addresses))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_NO_ADDRESS'); ?></div>
	<?php
}
else
{
	?>
	<div class="<?php echo $rowFluidClass; ?>">
		<form id="adminForm" class="order-list">
			<?php
			foreach ($this->addresses as $address)
			{
				?>
				<div class="content">
					<table class="list">
						<tr>
							<td class="left" width="80%">
								<?php
								$addressText = $address->firstname;
								if (EshopHelper::isFieldPublished('lastname') && $address->lastname != '')
								{
									$addressText .= " " . $address->lastname;
								}
								$addressText .= "<br />" . $address->address_1;
								if (EshopHelper::isFieldPublished('address_2') && $address->address_2 != '')
								{
									$addressText .= ", " . $address->address_2;
								}
								if (EshopHelper::isFieldPublished('city') && $address->city != '')
								{
									$addressText .= "<br />" . $address->city;
								}
								if (EshopHelper::isFieldPublished('postcode') && $address->postcode != '')
								{
									$addressText .= ", " . $address->postcode;
								}
								$addressText .= "<br />" . $address->email;
								if (EshopHelper::isFieldPublished('zone_id') && $address->zone_name != '')
								{
									$addressText .= "<br />" . $address->zone_name;
								}
								if (EshopHelper::isFieldPublished('country_id') && $address->country_name != '')
								{
									$addressText .= "<br />" . $address->country_name;
								}
								if (EshopHelper::isFieldPublished('telephone') && $address->telephone != '')
								{
									$addressText .= "<br />" . $address->telephone;
								}
								if (EshopHelper::isFieldPublished('fax') && $address->fax != '')
								{
									$addressText .= "<br />" . $address->fax;
								}
								if (EshopHelper::isFieldPublished('company_id') && $address->company_id != '')
								{
									$addressText .= "<br />" . $address->company_id;
								}
								if (EshopHelper::isFieldPublished('company') && $address->company != '')
								{
									$addressText .= "<br />" . $address->company;
								}
								echo $addressText;
								?>
							</td>
							<td class="right" width="20%">
								<input type="button" value="<?php echo JText::_('ESHOP_EDIT'); ?>" id="button-edit-address" class="<?php echo $btnClass; ?> btn-primary" onclick="window.location.assign('<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=address&aid='.$address->id); ?>');" />&nbsp; 
								<input type="button" value="<?php echo JText::_('ESHOP_DELETE'); ?>" id="<?php echo $address->id; ?>" class="button-delete-address btn btn-primary <?php echo $pullRightClass; ?>" />
							</td>
						</tr>
					</table>
				</div>	
				<?php
			}
			?>
		</form>
	</div>
	<?php
}
?>
<div class="<?php echo $rowFluidClass; ?>">
	<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-address" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullLeftClass; ?>" />
	<input type="button" value="<?php echo JText::_('ESHOP_ADD_ADDRESS'); ?>" id="button-new-address" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullRightClass; ?>" />
</div>
<script type="text/javascript">
	Eshop.jQuery(function($){
		$(document).ready(function(){
			$('#button-back-address').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
				$(location).attr('href', url);
			});

			$('#button-new-address').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=address'); ?>';
				$(location).attr('href', url);
			});

			//process user
			$('.button-delete-address').on('click', function() {
				var id = $(this).attr('id');
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=customer.deleteAddress<?php echo EshopHelper::getAttachedLangLink(); ?>&aid=' + id,
					type: 'post',
					data: $("#adminForm").serialize(),
					dataType: 'json',
					success: function(json) {
							$('.warning, .error').remove();
							if (json['return']) {
								window.location.href = json['return'];
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
		})
	});
</script>