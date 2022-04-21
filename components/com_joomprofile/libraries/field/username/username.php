<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldUsername extends JoomprofileLibField
{
	public $name = 'username';
	public $location = __DIR__;
	
	protected function _validate($field, $value, $userid)
	{
		$user = JoomprofileHelperJoomla::getUser(array('`username` = '.JFactory::getDbo()->quote($value)));
		
		if(!empty($userid)){
			$loggedinuser = JFactory::getUser($userid);
		}
		else{
			$loggedinuser = JFactory::getUser();
		}
		if(is_object($user) && isset($user->id) && $user->id && $loggedinuser->id != $user->id){	
			return array(JText::_('COM_JOOMPROFILE_ERROR_USERNAME_ALREADY_EXIST'));	
		}
		
		return array();
	}
	
	public function buildSearchQuery($fielddata, $query, $value)
	{		
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE '.$db->quote('%'.$value.'%');
		$query->where('('.$sql.')');
		return true;
	}
}