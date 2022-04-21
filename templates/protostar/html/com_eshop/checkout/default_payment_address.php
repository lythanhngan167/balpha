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
    // if (EshopHelper::isFieldPublished('email')) {
    //     echo $this->form->getField('email')->getControlGroup();
    // }

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
    //        if (EshopHelper::isFieldPublished('telephone')) {
    //            echo $this->form->getField('telephone')->getControlGroup();
    //        }
    // field fax
    if (EshopHelper::isFieldPublished('fax')) {
        echo $this->form->getField('fax')->getControlGroup();
    }

    // field address 2
    if (EshopHelper::isFieldPublished('postcode')) {
        echo $this->form->getField('postcode')->getControlGroup();
    }

	?>
    <input type="hidden" id="telephone" name="telephone" />
    <button type="button" class="btn btn-outline-warning" id="button-payment-address" >Lưu lại <?php //echo JText::_('ESHOP_CONTINUE'); ?></button>
</div>
