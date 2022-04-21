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
$user_list = $data->user_list;
?>

    <input type="hidden"
           id="joomprofile-field-<?php echo $fielddata->id;?>"
           name="joomprofile-field[<?php echo $fielddata->id;?>][]"
           value="" />

<?php if($fielddata->params['multi_mailchimp_list']) :?>

    <?php foreach ($data->allowed_list as $list) : ?>
        <?php if(empty($list)):?>
            <?php continue;?>
        <?php endif;?>
        <label class="checkbox stacked">
            <input type="checkbox"
                   name="joomprofile-field[<?php echo $fielddata->id;?>][]"
                   id="joomprofile-field-<?php echo $fielddata->id;?>"
                   value="<?php echo $list['id'];?>"
                <?php echo ($fielddata->mapping->required == true) ? 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_CHECKONE').'"' : '';?>
                <?php echo (is_array($user_list) && in_array($list['id'], $user_list)) ? 'checked="checked"' : '';?>/>
            <?php echo $list['name'];?>
        </label>
    <?php endforeach;?>

<?php else:?>
    <div class="controls">
        <select
                name="joomprofile-field[<?php echo $fielddata->id;?>][]"
                id="joomprofile-field-<?php echo $fielddata->id;?>"
            <?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>>
            <option value=""><?php echo JText::_('COM_JOOMPROFILE_SELECT_OPTION');?></option>
            <?php foreach ($data->allowed_list as $list) : ?>
                <option value="<?php echo $list['id']?>" <?php echo (is_array($user_list) && in_array($list['id'], $user_list)) ? 'selected="selected"' : '';?>><?php echo $list['name'];?></option>
            <?php endforeach;?>
        </select>
    </div>
<?php endif;?>
<?php

