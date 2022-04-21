<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileEvent extends JEvent
{
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if($isnew && $success){
			$session = JFactory::getSession()->set('JOOMPROFILE_PROFILE_REGISTERED_USER', $user['id']);
		}
	}
}