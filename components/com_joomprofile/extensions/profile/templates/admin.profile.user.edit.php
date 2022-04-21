<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::script("components/com_joomprofile/extensions/profile/templates/js/profile.js");
?>
<link href="../components/com_joomprofile/extensions/profile/templates/css/profile.css" rel="stylesheet">

<script>
	(function($){
		$(document).ready(function(){
			joomprofile.profile.getfieldGroupViewHtml(<?php echo $data->item->id;?>, 0);
		});
	})(jQuery);
</script>

<div id="f90pro" class="clearfix jp-wrap">

		&nbsp;

</div>
<?php
