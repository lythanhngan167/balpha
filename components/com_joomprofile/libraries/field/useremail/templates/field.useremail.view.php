<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params = $data->fielddata->params;
$email = $data->email;
?>

<a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>


<?php 