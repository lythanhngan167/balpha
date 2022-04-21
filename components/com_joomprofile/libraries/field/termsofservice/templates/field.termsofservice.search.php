<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$fielddata = $data->fielddata;
?>
<?php ob_start();?>
	<?php $class = $fielddata->css_class;?>
	<input 
		type="checkbox" 
		name="joomprofile-searchfield[<?php echo $fielddata->id;?>][]"
		id="joomprofile-searchfield-<?php echo $fielddata->id;?>"
		value="1"
		<?php echo is_array($data->value) && in_array(1, $data->value) ? 'checked="checked"' : '';?>
		data-f90-field-id="<?php echo $fielddata->id;?>"
		/>
<?php $fieldhtml = ob_get_contents(); ?>
<?php ob_end_clean();?>

<?php if($data->onlyFieldHtml == false) : ?>
<div class="accordion-group">
	<div class="accordion-heading jps-title">
		<a class="accordion-toggle" href="#" onClick="return false;">
    	<?php echo JText::_($fielddata->title);?>
    	</a>
		<?php echo $fieldhtml; ?>
	</div>
</div>
<?php else: ?>
	<?php echo $fieldhtml; ?>
<?php endif; ?>
<?php 