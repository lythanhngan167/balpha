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
if(!empty($data->value)) :?>

<a
	id="joomprofile-field-<?php echo $fielddata->id;?>-file" 
	href="<?php echo JUri::root().$data->value;?>" 
	><?php echo JText::_($fielddata->title);?></a>
<?php endif;?>
<?php 
