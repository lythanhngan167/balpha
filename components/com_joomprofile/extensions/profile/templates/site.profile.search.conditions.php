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
<?php if(!empty($data->searchFields)):?>
<?php foreach($data->searchFields as $field): ?>
	<?php $fieldObj =$field->toObject();?>
	<?php if($fieldObj->published == false) :?>
		<?php continue;?>
	<?php endif;?>
	<?php $field_instance = JoomprofileLibField::get($fieldObj->type);?>
    <?php $values = isset($data->searchConditions[$fieldObj->id]) ? $data->searchConditions[$fieldObj->id] : '';?>
    <?php echo $field_instance->getSearchHtml($fieldObj, $values, '');?>
<?php endforeach;?>      
<?php endif;?>
<?php 