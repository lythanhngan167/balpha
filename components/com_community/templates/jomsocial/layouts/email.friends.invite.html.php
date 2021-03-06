<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

if (isset($message) && !empty($message)) {
    $message = JText::sprintf('COM_COMMUNITY_INVITE_EMAIL_SENT_MESSAGE', $message);
} else {
    $message = '';
}

echo JText::sprintf('COM_COMMUNITY_INVITE_EMAIL_SENT_CONTENT', $displayName, $sitename, $message);

?>

---------------------------------------------------
<a href="{url}" class="joms-button" style="padding: 5px 8px; width: 100%; background: #5677fc; color: #fff; border-radius: 3px; display: block; text-align: center;">
    <b><?php echo JText::sprintf('COM_COMMUNITY_INVITE_EMAIL_SENT_FOOTER', $sitename); ?></b>
</a>
