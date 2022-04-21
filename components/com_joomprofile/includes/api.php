<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once __DIR__.'/autoload.php';

class JoomProfileApi
{
	/** 
	 * Gets User object
	 * @return JoomprofileProfileLibUser 
	 */
	public static function getUser($userid)
	{
		$profile = JoomprofileExtension::get('profile', array());
		return $profile->getObject('user', $profile->getPrefix(), $userid);
	}

	/** 
	 * Gets Field object
	 * @return JoomprofileProfileLibField
	 */
	public static function getField($fieldid)
	{
		$profile = JoomprofileExtension::get('profile', array());
		return $profile->getObject('field', $profile->getPrefix(), $fieldid);
	}

	/** 
	 * Gets Fieldgroup object
	 * @return JoomprofileProfileLibFieldgroup
	 */
	public static function getFieldgroup($fieldgroupid)
	{
		$profile = JoomprofileExtension::get('profile', array());
		return $profile->getObject('fieldgroup', $profile->getPrefix(), $fieldgroupid);
	}

	/** 
	 * Gets Usergroup object
	 * @return JoomprofileProfileLibUsergroup
	 */
	public static function getUsergroup($usergroupid)
	{
		$profile = JoomprofileExtension::get('profile', array());
		return $profile->getObject('usergroup', $profile->getPrefix(), $usergroupid);
	}

	public static function getFields()
	{
		return JoomprofileProfileHelper::getFields();
	}

	public static function getFieldgroups()
	{
		return JoomprofileProfileHelper::getFieldgroups();
	}
}