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
<?php $class = $fielddata->css_class;?>
<?php
$otherAttribute = '';
switch ($data->parentField->getType()) {
    case 'password': $fieldType = 'password'; break;
    case 'email':
    case 'useremail':
        $fieldType = 'text';
    $otherAttribute = 'data-validation-email-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_VALIDEMAIL').'"';
        break;
    case 'text':
    default:
        $fieldType = 'text';
}
?>
<input 
	type="<?php echo $fieldType;?>"
    <?php echo $otherAttribute;?>
	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
		placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
	<?php endif;?>
	
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>

	value=""
    data-validation-matches-match="joomprofile-field[<?php echo $data->parentField->getId();?>]"
    data-validation-matches-message="<?php echo !empty($fielddata->params['validation_message']) ? JText::_($fielddata->params['validation_message']) : JText::sprintf('COM_JOOMPROFILE_VALIDATION_NOT_SAME', $data->parentField->getTitle());?>"
    class="<?php echo $class;?>"
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	/>
<?php 
