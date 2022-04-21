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
?>
<div class="jp-wrap">
<div class="row-fluid">
	<form enctype='multipart/form-data' class="f90-validate-form" id="joomprofile-site-profile-registration-form" action="index.php?option=com_joomprofile&view=profile" method="POST">		
		<?php echo $this->render('site.profile.user.registration.profile');?>
	</form>
</div>
</div>
<?php 