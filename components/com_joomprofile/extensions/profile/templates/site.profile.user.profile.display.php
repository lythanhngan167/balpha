<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('jquery.framework');
Jhtml::_('bootstrap.framework');
JHtml::_('behavior.framework');

JHtml::script("com_joomprofile/joomprofile.js", false, true);
JHtml::script("com_joomprofile/validation.js", false, true);
JHtml::script("com_joomprofile/uploader.js", false, true);
JHtml::script("components/com_joomprofile/extensions/profile/templates/js/profile.js");
JHtml::stylesheet('com_joomprofile/joomprofile.css', array(), true);
JHtml::stylesheet("com_joomprofile/font-awesome.css", array(), true);

$document  = JFactory::getDocument();
$document->setTitle($data->user->name.'\'s Profile');
$document->setMetadata('title', $data->user->name.'\'s Profile');
$document->setMetadata('description', $data->user->name.'\'s Profile');
$document->setMetadata('og:title', $data->user->name.'\'s Profile', 'property');
$document->setMetadata('og:description', $data->user->name.'\'s Profile', 'property');
$document->setMetadata('og:type', 'profile', 'property');
$canUrl = '<link href="' . $data->profileUrl . '" rel="canonical" />';
$document->addCustomTag($canUrl);
$document->setMetadata('og:url', JUri::root().$data->profileUrl, 'property');
$document->setMetadata('og:image', !empty($data->avatar) ? $data->avatar : JUri::root().'media/com_joomprofile/images/default.png', 'property');
$document->setMetadata('profile:first_name', $data->firstName, 'property');
if (!empty($data->lastName)) {
    $document->setMetadata('profile:last_name', $data->lastName, 'property');
}
?>
<link href="components/com_joomprofile/extensions/profile/templates/css/profile.css" rel="stylesheet">

<script>
	(function($){
		$(document).ready(function(){
			joomprofile.profile.getfieldGroupViewHtml(<?php echo $data->user->id;?>, 0);
		});
	})(jQuery);
</script>

<div class="jp-wrap col-md-12">
<div id="f90pro" class="clearfix ">

		&nbsp;

</div>
</div>
<?php
