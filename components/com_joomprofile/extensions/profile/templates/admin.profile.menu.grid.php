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
		<button class="btn btn-success f90-flat" onclick="location.href='index.php?option=com_joomprofile&view=profile&task=<?php echo $data->ext_name;?>.edit';">
			<i class="icon-new icon-white"></i>
			<?php echo JText::_('JTOOLBAR_NEW');?>
		</button>
	</div>
	<?php if(!empty($data->records)):?>
	<div class="btn-group pull-left">
		<button class="btn f90-flat" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ Joomla.submitbutton('<?php echo $data->ext_name;?>.publish')};">
			<span class="text-success">
				<i class="fa fa-check-circle-o"></i>
				<?php echo JText::_('JTOOLBAR_PUBLISH');?>
			</span>
		</button>
	</div>
	
	<div class="btn-group pull-left">
		<button class="btn text-error f90-flat" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ Joomla.submitbutton('<?php echo $data->ext_name;?>.unpublish')};">
			<span class="text-error ">
				<i class="fa fa-dot-circle-o"></i>
				<?php echo JText::_('JTOOLBAR_UNPUBLISH');?>
			</span>
		</button>
	</div>
	
	<div class="btn-group pull-left">
		<button class="btn btn-danger f90-flat" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ Joomla.submitbutton('<?php echo $data->ext_name;?>.remove')};">
			<i class="fa fa-trash-o"></i>
			<?php echo JText::_('JTOOLBAR_REMOVE');?>
		</button>
	</div>
	<?php endif;?>
<?php 