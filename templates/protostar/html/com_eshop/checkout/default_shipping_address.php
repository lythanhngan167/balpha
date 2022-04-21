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
$user = JFactory::getUser();
$fields  = EshopHelper::getFormFields('A');
$form    = new RADForm($fields);
if (isset($this->lists['address_id']))
{
	?>
	<label class="radio">
		<input id="shipping_address-existing" type="radio" value="existing" name="shipping_address" checked="checked"> <?php echo JText::_('ESHOP_EXISTING_ADDRESS'); ?>
	</label>
	<div id="shipping-existing">
		<?php echo $this->lists['address_id']; ?>
	</div>
	<label class="radio">
		<input type="radio" id="shipping_address-new" value="new" name="shipping_address"> <?php echo JText::_('ESHOP_NEW_ADDRESS'); ?>
	</label>
	<?php
}
?>

<div id="shipping-new" style="display: <?php echo ( (isset($this->lists['address_id']) || count($this->lists['address_id']) == 0) ? 'none' : 'block' ); ?>;" class="form-horizontal">
	<?php
	
		//echo $form->render();
    // field first name
    if (EshopHelper::isFieldPublished('firstname'))
    {
        echo  $form->getField('firstname')->getControlGroup();
    }

    // field last name
    if (EshopHelper::isFieldPublished('lastname'))
    {
        echo  $form->getField('lastname')->getControlGroup();
    }

    // field email
    if (EshopHelper::isFieldPublished('email')) {
        echo $form->getField('email')->getControlGroup();
    }

    // field country_id
    if (EshopHelper::isFieldPublished('country_id')) {
        echo $form->getField('country_id')->getControlGroup();
    }

    // field zone_id
    if (EshopHelper::isFieldPublished('zone_id')) {
        echo $form->getField('zone_id')->getControlGroup();
    }

    // field address 1
    if (EshopHelper::isFieldPublished('address_1')) {
        echo $form->getField('address_1')->getControlGroup();
    }
    // field address 2
    if (EshopHelper::isFieldPublished('address_2')) {
        echo $form->getField('address_2')->getControlGroup();
    }

    // field postcode
    if (EshopHelper::isFieldPublished('postcode')) {
        echo $form->getField('postcode')->getControlGroup();
    }
    // field company
    if (EshopHelper::isFieldPublished('company')) {
        echo $form->getField('company')->getControlGroup();
    }

    // field telephone
           // if (EshopHelper::isFieldPublished('telephone')) {
           //     echo $form->getField('telephone')->getControlGroup();
           // }
    // field fax
    if (EshopHelper::isFieldPublished('fax')) {
        echo $form->getField('fax')->getControlGroup();
    }

    // field address 2
    if (EshopHelper::isFieldPublished('postcode')) {
        echo $form->getField('postcode')->getControlGroup();
    }
	?>
    <input type="hidden" id="telephone" name="telephone" value="<?php echo $user->username; ?>">
</div>
