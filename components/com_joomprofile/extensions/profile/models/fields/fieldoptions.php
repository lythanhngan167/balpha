<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
class JoomprofileFormFieldFieldoptions extends JFormField
{	
	protected $type = 'Fieldoptions';
	
	protected function getInput()
	{
		$field_id = JFactory::getApplication()->input->get('id', 0, 'INT');
		
		$options = array();
		if($field_id){
			$field = new JoomprofileLibField();
			$options = $field->getOptions($field_id);
		}
		
		ob_start();
		$counter = 0;
		foreach ($options as $option){
			?>
			<div class="row-fluid" id="<?php echo $this->id ;?>-<?php echo $counter;?>-option">
				<input 
					type="text" 
					name="<?php echo $this->name;?>[<?php echo $counter;?>][title]" 
					id="<?php echo $this->id ;?>-<?php echo $counter;?>-title" 
					value="<?php echo $option->title?>" />
				<input 
					type="hidden" 
					name="<?php echo $this->name;?>[<?php echo $counter;?>][id]" 
					id="<?php echo $this->id ;?>-<?php echo $counter;?>-id" value="<?php echo $option->id?>" />
				<button onClick="jQuery('#<?php echo $this->id ;?>-<?php echo $counter;?>-option').remove();"><i class="icon-remove"></i></button> 
			</div>
			<?php 
			$counter++;
		}
		
		?>
		<script>
			(function($){
				$('#<?php echo $this->id ;?>_add_option').live('click', function(){
					var counter = $(this).attr('counter');
					var html = '';
					html += '<div class="row-fluid" id="<?php echo $this->id ;?>-'+counter+'-option">';
					html += '<input ';
					html += 'type="text" ';
					html += 'name="<?php echo $this->name;?>['+counter+'][title]" '; 
					html += 'id="<?php echo $this->id ;?>-'+counter+'-title" ';
					html += 'value="" /> ';
					html += '<button onClick="jQuery(\'#<?php echo $this->id ;?>-'+counter+'-option\').remove();">';
					html += '<i class="icon-remove"></i></button>'; 
					html += '</div>';
					$(html).insertBefore($(this).parent());
					$(this).attr('counter', parseInt(counter)+1);
					return false;
				});
			})(jQuery);
		</script>
		
		<div class="row-fluid">
			<button id="<?php echo $this->id ;?>_add_option" counter="<?php echo $counter;?>"><i class="icon-new"></i></button>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
} 