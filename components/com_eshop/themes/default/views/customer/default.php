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
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_MY_ACCOUNT'); ?></h1>
<?php
if (EshopHelper::getConfigValue('customer_manage_account', '1') || EshopHelper::getConfigValue('customer_manage_order', '1') || EshopHelper::getConfigValue('customer_manage_download', '1') || EshopHelper::getConfigValue('customer_manage_address', '1'))
{
	?>
	<ul class="eshop-account">
		<?php
		if (EshopHelper::getConfigValue('customer_manage_account', '1'))
		{
			?>
			<li>
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=account'); ?>">
					<?php echo JText::_('ESHOP_EDIT_ACCOUNT'); ?>
				</a>
			</li>
			<?php
		}
		if (EshopHelper::getConfigValue('customer_manage_order', '1'))
		{
			?>
			<li>
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=orders'); ?>">
					<?php echo JText::_('ESHOP_ORDER_HISTORY'); ?>
				</a>
			</li>
			<?php
		}
		if (EshopHelper::getConfigValue('customer_manage_download', '1'))
		{
			?>
			<li>
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=downloads'); ?>">
					<?php echo JText::_('ESHOP_DOWNLOADS'); ?>
				</a>
			</li>
			<?php
		}
		if (EshopHelper::getConfigValue('customer_manage_address', '1'))
		{
			?>
			<li>
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=addresses'); ?>">
					<?php echo JText::_('ESHOP_MODIFY_ADDRESS'); ?>
				</a>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}
else
{
	echo JText::_('ESHOP_CUSTOMER_PAGE_NOT_AVAILABLE');
}
