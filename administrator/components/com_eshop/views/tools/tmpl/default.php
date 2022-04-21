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
JToolBarHelper::title(JText::_('ESHOP_TOOLS'), 'generic.png');
?>
<script type="text/javascript">
	function confirmation(message, destnUrl) {
		var answer = confirm(message);
		if (answer) {
			window.location = destnUrl;
		}
	}
</script>
<div class="clearfix">
	<div style="width: 100%; float: left;">
		<div class="bs-example bs-shop-tools">
			<table class="table dashboard-table">
				<tbody>
					<tr>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.migrateFromJoomla', 'icon-48-tools-migrate-from-joomla.png', JText::_('ESHOP_MIGRATE_FROM_JOOMLA'), JText::_('ESHOP_MIGRATE_FROM_JOOMLA_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.migrateFromMembershipPro', 'icon-48-tools-migrate-from-membership.png', JText::_('ESHOP_MIGRATE_FROM_MEMBERSHIP'), JText::_('ESHOP_MIGRATE_FROM_MEMBERSHIP_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.cleanData', 'icon-48-tools-clean-data.png', JText::_('ESHOP_CLEAN_DATA'), JText::_('ESHOP_CLEAN_DATA_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.addSampleData', 'icon-48-install.png', JText::_('ESHOP_ADD_SAMPLE_DATA'), JText::_('ESHOP_ADD_SAMPLE_DATA_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.synchronizeData', 'icon-48-tools-synchronize-data.png', JText::_('ESHOP_SYNCHRONIZE_DATA'), JText::_('ESHOP_SYNCHRONIZE_DATA_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.migrateVirtuemart', 'icon-48-tools-migrate_virtuemart.png', JText::_('ESHOP_MIGRATE_VIRTUEMART'), JText::_('ESHOP_MIGRATE_VIRTUEMART_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.resetHits', 'icon-48-tools-reset-hits.png', JText::_('ESHOP_RESET_HITS'), JText::_('ESHOP_RESET_HITS_CONFIRM')); ?>
							</div>
						</td>
						<td width="12%">
							<div id="cpanel">
								<?php $this->quickiconButton('index.php?option=com_eshop&task=tools.purgeUrls', 'icon-48-tools-purge-urls.png', JText::_('ESHOP_PURGE_URLS'), JText::_('ESHOP_PURGE_URLS_CONFIRM')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width="12%">
							<?php echo JText::_('ESHOP_MIGRATE_FROM_JOOMLA_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_MIGRATE_FROM_MEMBERSHIP_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_CLEAN_DATA_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_ADD_SAMPLE_DATA_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_SYNCHRONIZE_DATA_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_MIGRATE_VIRTUEMART_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_RESET_HITS_HELP'); ?>
						</td>
						<td width="12%">
							<?php echo JText::_('ESHOP_PURGE_URLS_HELP'); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>