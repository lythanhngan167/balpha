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
<div class="row-fluid">
<?php $fieldSets = $data->form->getFieldsets('params'); ?>
<?php foreach ($fieldSets as $name => $fieldSet) : ?>

	<?php foreach ($data->form->getFieldset($name) as $field):?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?> </div>
			<div class="controls"><?php echo $field->input; ?></div>								
		</div>
	<?php endforeach;?>
<?php endforeach;?>
</div>
<?php 
