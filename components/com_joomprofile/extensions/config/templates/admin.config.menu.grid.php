<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
	<div class="btn-group pull-left">
		<button class="btn f90-flat" onclick="joomprofile.edit.form.submit('joomprofile-edit-form', '<?php echo $data->ext_name;?>.save');">
			<span class="text-success">
				<i class="icon-save "></i>
				<?php echo JText::_('JTOOLBAR_APPLY');?>
			</span>
		</button>
	</div>
	
	<div class="btn-group pull-left">
		<button class="btn f90-flat" onclick="joomprofile.edit.form.submit('joomprofile-edit-form', '<?php echo $data->ext_name;?>.cancel');">
			<span class="text-error">
				<i class="icon-cancel "></i>
				<?php echo JText::_('JTOOLBAR_CANCEL');?>
			</span>
		</button>
	</div>
<?php 