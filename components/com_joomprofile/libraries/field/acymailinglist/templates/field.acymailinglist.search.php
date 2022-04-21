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
<?php $class = 'checkbox '.$fielddata->css_class;?>
<?php $counter = 0;?>
<?php foreach ($fielddata->options as $option) : ?>
    <label class="<?php echo $class;?>">
        <input
                type="checkbox"
                name="joomprofile-searchfield[<?php echo $fielddata->id;?>][]"
                id="joomprofile-searchfield-<?php echo $fielddata->id;?>-<?php echo $counter++;?>"
                value="<?php echo $option->listid;?>"
            <?php echo in_array($option->listid, $data->value) ? 'checked="checked"' : '';?>
                data-f90-field-id="<?php echo $fielddata->id;?>"
        />
        <?php echo ' '. JText::_($option->name);?>
    </label>
<?php endforeach;?>
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
                <?php echo $fieldhtml; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php echo $fieldhtml; ?>
<?php endif; ?>
<?php
