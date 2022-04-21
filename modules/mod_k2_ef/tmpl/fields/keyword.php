<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<input placeholder="<?php echo JText::_('MOD_K2_EF_SELECT_KEYWORD_DEFAULT'); ?>" class="inputbox" name="keyword" type="text" <?php if (JRequest::getVar('keyword')) echo ' value="'.JRequest::getVar('keyword').'"'; ?> />

