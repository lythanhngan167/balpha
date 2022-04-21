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
JHtml::_('behavior.tabstate');
EshopHelper::chosen();

JHtml::_('bootstrap.tooltip');
$document = JFactory::getDocument();
$document->addStyleDeclaration(".hasTip{display:block !important}");

$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;

echo $this->loadTemplate('javascript');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'product', array('active' => 'general-page'));

	echo JHtml::_('bootstrap.addTab', 'product', 'general-page', JText::_('ESHOP_GENERAL', true));
	echo $this->loadTemplate('general');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'data-page', JText::_('ESHOP_DATA', true), "datapage");
	echo $this->loadTemplate('data');
	echo JHtml::_('bootstrap.endTab');
	//nganly
	echo JHtml::_('bootstrap.addTab', 'product', 'attributes-page', JText::_('ESHOP_ATTRIBUTES', true));
	echo $this->loadTemplate('attributes');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'options-page', JText::_('ESHOP_OPTIONS', true));
	echo $this->loadTemplate('options');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'discounts-page', JText::_('ESHOP_DISCOUNT', true));
	echo $this->loadTemplate('discounts');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'special-page', JText::_('ESHOP_SPECIAL', true));
	echo $this->loadTemplate('special');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'images-page', JText::_('ESHOP_IMAGES', true));
	echo $this->loadTemplate('images');
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'product', 'attachments-page', JText::_('ESHOP_ATTACHMENTS', true));
	echo $this->loadTemplate('attachments');
	echo JHtml::_('bootstrap.endTab');

	if (EshopHelper::getConfigValue('acymailing_integration') && JComponentHelper::isEnabled('com_acymailing'))
	{
		echo JHtml::_('bootstrap.addTab', 'product', 'acymailing-page', JText::_('ESHOP_ACYMAILING', true));
		echo $this->loadTemplate('acymailing');
		echo JHtml::_('bootstrap.endTab');
	}

	if (EshopHelper::getConfigValue('mailchimp_integration') && EshopHelper::getConfigValue('api_key_mailchimp') != '')
	{
		echo JHtml::_('bootstrap.addTab', 'product', 'mailchimp-page', JText::_('ESHOP_MAILCHIMP', true));
		echo $this->loadTemplate('mailchimp');
		echo JHtml::_('bootstrap.endTab');
	}

	if (EshopHelper::getConfigValue('product_custom_fields'))
	{
		echo JHtml::_('bootstrap.addTab', 'product', 'custom_fields-page', JText::_('ESHOP_EXTRA_INFORMATION', true));
		echo $this->loadTemplate('custom_fields');
		echo JHtml::_('bootstrap.endTab');
	}

	if (count($this->plugins))
	{
		$count = 0;

		foreach ($this->plugins as $plugin)
		{
			$count++;
			echo JHtml::_('bootstrap.addTab', 'product', 'tab_' . $count, JText::_($plugin['title'], true));
			echo $plugin['form'];
			echo JHtml::_('bootstrap.endTab');
		}
	}

	echo JHtml::_('bootstrap.endTabSet');
	?>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<?php
	if ($translatable)
	{
		foreach ($this->languages as $language)
		{
			$langCode = $language->lang_code;
		?>
			<input type="hidden" name="details_id_<?php echo $langCode; ?>" value="<?php echo intval(isset($this->item->{'details_id_' . $langCode}) ? $this->item->{'details_id_' . $langCode} : ''); ?>" />
		<?php
		}
	}
	elseif ($this->translatable)
	{
	?>
		<input type="hidden" name="details_id" value="<?php echo intval(isset($this->item->{'details_id'}) ? $this->item->{'details_id'} : ''); ?>" />
	<?php
	}
	?>
	<input type="hidden" name="task" value="" />
	<div id="date_html_container" style="display: none;">
		<?php echo JHtml::_('calendar', '', 'tmp_date_picker_name', 'tmp_date_picker_id', '%Y-%m-%d', array('class' => 'input-small')); ?>
	</div>
</form>

<style>

a[href^="#data-page"] {
   display: none!important;
}
a[href^="#attributes-page"] {
   display: none!important;
}

a[href^="#discounts-page"] {
   display: none!important;
}
a[href^="#special-page"] {
   display: none!important;
}
a[href^="#attachments-page"] {
   display: none!important;
}
/* a[href^="#options-page"] {
   display: none!important;
} */


</style>
