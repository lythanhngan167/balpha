<?php
defined('_JEXEC') or die('Restricted access');
?><form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm">
	<div id="acym__campaigns" class="acym__content">
        <?php
        $workflow = acym_get('helper.workflow');
        echo $workflow->displayTabs($this->tabs, 'campaigns');

        if (empty($data['allCampaigns']) && empty($data['search']) && empty($data['status']) && empty($data['tag'])) {
            include acym_getView('campaigns', 'listing_empty', true);
        } else {
            include acym_getView('campaigns', 'listing_header', true);
            include acym_getView('campaigns', 'listing_listing', true);
        }
        ?>
	</div>
    <?php acym_formOptions(true, 'campaigns'); ?>
</form>

