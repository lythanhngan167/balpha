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
<?php $src = !empty($data->value) ? $data->value : @$fielddata->params['default_image'];?>

<img 
	id="joomprofile-field-<?php echo $fielddata->id;?>-img" 
	src="<?php if (strpos($src, "http://") === 0 || strpos($src, "https://") === 0) 
	               echo $src;
	           else
	               echo JUri::root().$src;?>" 
	class="img-polaroid" 
	style="	<?php if(isset($fielddata->params['default_width'])) { ?>
				width: <?php echo $fielddata->params['default_width'];?>px;
			<?php } ?>
			<?php if(isset($fielddata->params['default_height'])) { ?>
				height: <?php echo $fielddata->params['default_height'];?>px;
			<?php } ?>
			"/>
<?php 
