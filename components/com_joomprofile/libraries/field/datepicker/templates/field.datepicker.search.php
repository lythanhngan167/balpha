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

static $dp_flag = false;
if($dp_flag == false){
    JHtml::script("com_joomprofile/moment.min.js", false, true);
    JHtml::script("com_joomprofile/daterangepicker.min.js", false, true);
    JHtml::stylesheet('com_joomprofile/daterangepicker.min.css', array(), true);
    $dp_flag = true;
}
ob_start();
$class = $fielddata->css_class;?>
    <input
            type="text"
            value="<?php echo @$data->value;?>"
            class="<?php echo $class;?> no-search"
            name="joomprofile-searchfield[<?php echo $fielddata->id;?>]"
            id="joomprofile-searchfield-<?php echo $fielddata->id;?>"
            data-f90-field-id="<?php echo $fielddata->id;?>"
    />

    <?php $displayData = array('field_id' => 'joomprofile-searchfield-'.$fielddata->id);?>
    <?php echo JLayoutHelper::render('daterangepicker', $displayData);?>

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
