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
<?php ob_start();?>
	<?php $class = $fielddata->css_class;?>
	<?php $labels = array('FROM', 'TO'); ?>
	<?php if($fielddata->params['dom_type'] == 'selectbox' && 
			isset($fielddata->params['min_value']) && !empty($fielddata->params['min_value']) && 
			isset($fielddata->params['min_value']) && !empty($fielddata->params['min_value'])) :?>
				<?php $min = $fielddata->params['min_value'];?>
				<?php $max = $fielddata->params['max_value'];?>
				<?php if($min > $max): ?>
					<?php $temp = $min; ?>
					<?php $min  = $max; ?>
					<?php $max  = $temp;?>
				<?php endif;?>			
		<?php foreach (array(0,1) as $counter):?>
		<div><?php echo JText::_('COM_JOOMPROFILE_'.$labels[$counter]);?></div>
		<select 	
			class="<?php echo $class;?> no-search"
			name="joomprofile-searchfield[<?php echo $fielddata->id;?>][<?php echo $counter;?>]"
			id="joomprofile-searchfield-<?php echo $fielddata->id;?>-<?php echo $counter;?>"
			data-f90-field-id="<?php echo $fielddata->id;?>">
			<?php $value = @array_shift($data->value);?>
			<option value=""><?php echo JText::_('COM_JOOMPROFILE_SELECT_OPTION');?></option>
			<?php for($min_counter = $min; $min_counter <= $max; $min_counter++):?>
				<option value="<?php echo $min_counter;?>" <?php echo ($min_counter == $value) ? 'selected="selected"' : '';?>><?php echo $min_counter;?></option>
			<?php endfor;?>	
		</select>
	<?php endforeach;?>
	<?php else:?>
		<?php foreach (array(0,1) as $counter):?>
		<div><?php echo JText::_('COM_JOOMPROFILE_'.$labels[$counter]);?></div>
		<input 
			type="number"
			<?php $value = @array_shift($data->value);?>
			value="<?php echo $value;?>"				
		
			class="<?php echo $class;?> no-search"
			name="joomprofile-searchfield[<?php echo $fielddata->id;?>][<?php echo $counter;?>]"
			id="joomprofile-searchfield-<?php echo $fielddata->id;?>-<?php echo $counter;?>"
			data-f90-field-id="<?php echo $fielddata->id;?>"
			/>
		<?php endforeach;?>
	<?php endif;?>
<?php $fieldhtml = ob_get_contents(); ?>
<?php ob_end_clean();?>

<?php if($data->onlyFieldHtml == false) : ?>
<div class="accordion-group">
	<div class="accordion-heading jps-title">
		<a class="accordion-toggle" data-toggle="collapse" href="#jp-collapse<?php echo $fielddata->id;?>">
    	<?php echo JText::_($fielddata->title);?> 
    	</a>
	</div>
	<div id="jp-collapse<?php echo $fielddata->id;?>" class="accordion-body collapse in">
		<div class="accordion-inner">
			<?php echo $fieldhtml;?> 
			<button class="btn btn-primary" type="button" data-f90-field-id="<?php echo $fielddata->id;?>"><?php echo JText::_('COM_JOOMPROFILE_GO');?></button>
		</div>
	</div>
</div>
<?php else: ?>
	<?php echo $fieldhtml; ?>
<?php endif; ?>
<?php 
