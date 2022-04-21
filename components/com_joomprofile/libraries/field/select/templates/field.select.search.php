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
$class = $fielddata->css_class;
// TODO :
?>
<?php ob_start();?>
<div class="col-lg-4">
    <select
        class="<?php echo $class;?> form-control"
        name="joomprofile-searchfield[<?php echo $fielddata->id;?>]"
        id="joomprofile-searchfield-<?php echo $fielddata->id;?>"
        data-f90-field-id="<?php echo $fielddata->id;?>">
        <option value="">Chọn <?php echo JText::_($fielddata->title);?></option>
        <?php foreach ($fielddata->options as $option) : ?>
            <option
                <?php echo in_array($option->id, $data->value) ? 'selected' : '';?>
                    value="<?php echo $option->id;?>">
                <?php echo JText::_($option->title);?>
            </option>
        <?php endforeach;?>
    </select>
</div>

<?php $fieldhtml = ob_get_contents(); ?>
<?php ob_end_clean();?>

<?php echo $fieldhtml; ?>

<?php 
