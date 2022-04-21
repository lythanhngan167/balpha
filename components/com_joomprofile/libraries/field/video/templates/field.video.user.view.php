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
	<?php if(!empty($data->videoType) && (!empty($data->videoId))) :
	           if($data->videoType == "Youtube"){
	               $src = "https://www.youtube.com/embed/".$data->videoId;
	           }
	           elseif($data->videoType == "Vimeo"){
	               $src="https://player.vimeo.com/video/".$data->videoId;
	           }?>
        <iframe
            class="jp-field-iframe-100"
            name="joomprofile-field-<?php echo $fielddata->id;?>-video" 
            width="220"
            height="255"
            src=<?php echo $src;?>>
        </iframe>
    <?php endif;?>
<?php endif;?>
<?php
