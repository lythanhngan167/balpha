<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" />

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery("input.datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
	
<input placeholder="<?php echo JText::_('MOD_K2_EF_SELECT_CREATED_DEFAULT'); ?>" class="datepicker" name="created-from" type="text" value="<?php echo JRequest::getVar('created-from'); ?>" <?php if($onchange) { ?>onchange="document.K2EasyFilter.submit()"<?php } ?> />
-
<input class="datepicker" name="created-to" type="text" value="<?php echo JRequest::getVar('created-to'); ?>"  <?php if($onchange) { ?>onchange="document.K2EasyFilter.submit()"<?php } ?> />

