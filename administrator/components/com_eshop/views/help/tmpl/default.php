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
JToolBarHelper::title(JText::_('ESHOP_HELP'), 'generic.png');
?>
<div class="control-group">
	<div class="control-label">
		<?php echo JText::_('ESHOP_HELP_RELEASED'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo JText::_('ESHOP_HELP_DEMO'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo JText::_('ESHOP_HELP_DOCUMENTATION'); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo JText::_('ESHOP_HELP_SUPPORT'); ?>
		<ol>
			<li><?php echo JText::_('ESHOP_HELP_SUPPORT_1'); ?></li>
			<li><?php echo JText::_('ESHOP_HELP_SUPPORT_2'); ?></li>
			<li><?php echo JText::_('ESHOP_HELP_SUPPORT_3'); ?></li>
		</ol>
	</div>
</div>