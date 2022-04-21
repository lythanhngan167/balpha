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
	<h1><?php echo JText::_('COM_JOOMPROFILE_REGISTRATION_THANK_YOU');?></h1>
	<?php foreach ($data->messages as $message):?>
		<div>
			<?php echo $message;?>
		</div>
	<?php endforeach;?>
</div>
<?php 