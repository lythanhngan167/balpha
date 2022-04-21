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
<script>

jQuery(document).ready(function(){
	joomprofile.address.initialize('joomprofile-searchfield-<?php echo $fielddata->id;?>');
	jQuery('#joomprofile-searchfield-<?php echo $fielddata->id;?>-address').on('focus', joomprofile.address.geolocate());
});

</script>

<?php ob_start();?>
	<?php $class = $fielddata->css_class;?>
	<input 
		type="text"
		value="<?php echo @$data->value[0];?>"
		class="<?php echo $class;?> no-search"
		name="joomprofile-searchfield[<?php echo $fielddata->id;?>][address]"
		id="joomprofile-searchfield-<?php echo $fielddata->id;?>-address"
		data-f90-field-id="<?php echo $fielddata->id;?>"
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_SEARCH_ADDRES_ENTER_A_LOCATION');?>"
		/>
	<div>
		<input 
			type="number"
			value="<?php echo isset($data->value[1]) ? $data->value[1] : @$data->fielddata->params['default_distance'];?>"
			class="<?php echo $class;?> no-search input-mini"
			name="joomprofile-searchfield[<?php echo $fielddata->id;?>][distance]"
			id="joomprofile-searchfield-<?php echo $fielddata->id;?>-distance"
			data-f90-field-id="<?php echo $fielddata->id;?>"
			placeholder="<?php echo JText::_('COM_JOOMPROFILE_SEARCH_ADDRES_DISTANCE');?>"
			/>

        <?php $distanceUnit =  isset($data->value[2]) ? $data->value[2] : @$data->fielddata->params['default_unit'];?>
		<select data-f90-field-id="<?php echo $fielddata->id;?>"
                name="joomprofile-searchfield[<?php echo $fielddata->id;?>][range]"
                id="joomprofile-searchfield-<?php echo $fielddata->id;?>-range"
                class="<?php echo $class;?> no-search input-mini" >
            <option value="km" <?php echo $distanceUnit == 'km' ? 'selected="selected"' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_ADDRES_KMS');?></option>
            <option value="mile" <?php echo $distanceUnit == 'mile' ? 'selected="selected"' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_ADDRES_MILES');?></option>
        </select>
	</div>
	<input 
		type="hidden"
		value="<?php echo @$data->value[3];?>"
		class="<?php echo $class;?> no-search"
		name="joomprofile-searchfield[<?php echo $fielddata->id;?>][formatted]"
		id="joomprofile-searchfield-<?php echo $fielddata->id;?>-formatted"
		data-f90-field-id="<?php echo $fielddata->id;?>"
		/>

	<input 
		type="hidden"
		value="<?php echo @$data->value[4];?>"
		class="<?php echo $class;?> no-search"
		name="joomprofile-searchfield[<?php echo $fielddata->id;?>][latitude]"
		id="joomprofile-searchfield-<?php echo $fielddata->id;?>-latitude"
		data-f90-field-id="<?php echo $fielddata->id;?>"
		/>

	<input 
		type="hidden"
		value="<?php echo @$data->value[5];?>"
		class="<?php echo $class;?> no-search"
		name="joomprofile-searchfield[<?php echo $fielddata->id;?>][longitude]"
		id="joomprofile-searchfield-<?php echo $fielddata->id;?>-longitude"
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
	</div>
	<div id="jp-collapse<?php echo $fielddata->id;?>" class="accordion-body collapse in">
		<div class="accordion-inner">		
			<?php echo $fieldhtml; ?>
			<button class="btn btn-primary" type="button" data-f90-field-id="<?php echo $fielddata->id;?>"><?php echo JText::_('COM_JOOMPROFILE_GO');?></button>
		</div>
	</div>
</div>
<?php else: ?>
	<?php echo $fieldhtml; ?>
<?php endif; ?>
<?php 

