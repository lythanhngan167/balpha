<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

	<select name="etag" <?php if($onchange) : ?>onchange="document.K2EasyFilter.submit()"<?php endif; ?>>
		<option value=""><?php echo JText::_('MOD_K2_EF_SELECT_TAG_DEFAULT'); ?></option>
		<?php
			foreach ($tags as $tag) {
				echo '<option ';
				if (JRequest::getVar('etag') == $tag->tag) { echo 'selected="selected"'; }
				echo '>'.$tag->tag.'</option>';
			}
		?>
	</select>
    


