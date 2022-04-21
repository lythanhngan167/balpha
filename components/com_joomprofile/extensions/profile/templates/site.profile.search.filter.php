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
		<?php $field_instance = JoomprofileLibField::get($fieldObj->type);?>
	    <?php $values = isset($data->searchConditions[$fieldObj->id]) ? $data->searchConditions[$fieldObj->id] : '';?>
	    <?php if(!empty($values)):?>
	    	<span class="jps-filter-applied"><?php echo JText::_($fieldObj->title);?></span> : 
                    <span class="jps-filter-applied-child">
                    <?php echo $field_instance->getAppliedSearchHtml($fieldObj, $values);?>	
                    <a href="#" onClick="return false;" class="jps-clear-search" data-f90-field-id="<?php echo $fieldObj->id;?>">x</a>
                    </span>						    
			    <?php endif;?>
	<?php endforeach;?>      
<?php endif; ?>
<?php 