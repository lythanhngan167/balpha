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
// TODO :
?>
<?php $class = 'checkbox '.$fielddata->css_class;?>
<label class="<?php echo $class;?>">
	<input  
		type="hidden" 
		name="joomprofile-field[<?php echo $fielddata->id;?>]"
		id="joomprofile-field-<?php echo $fielddata->id;?>"
		value="0"/>
	
	<input 
	 	<?php echo ($fielddata->mapping->required == true) ? 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_ACEEPT_TERMS_AND_CONDITION').'"' : '';?> 
	type="checkbox" 
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	value="1"
	<?php echo ($data->value) ? 'checked="checked"' : '';?>/>

	<?php $title = ' '. JText::_($fielddata->params['title']);?>
	<?php if(isset($fielddata->params['link']) && !empty($fielddata->params['link'])) : ?>
		<a href="<?php echo $fielddata->params['link'];?>" target="_blank"><?php echo $title; ?></a>
	<?php else : ?>	
		<?php echo $title;?>
	<?php endif; ?>
</label>

<?php if(isset($fielddata->params['article_id']) && !empty($fielddata->params['article_id'])) : ?>
	<div>
		<?php
		$table_plan         = JTable::getInstance('Content', 'JTable');
		$table_plan_return  = $table_plan->load(array('id'=>$fielddata->params['article_id']));
		print_r($table_plan->introtext);
		?>
	</div>
<?php endif; ?>
<?php 
