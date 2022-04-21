<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldPassword extends JoomprofileLibField
{
	public $name = 'password';
	public $location = __DIR__;
	
	public function getViewHtml($fielddata, $value, $userid)
	{
		return '********';
	}
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE "%" ';
		$query->where('('.$sql.')');
		return true;
	}
	
	public function getAppliedSearchHtml($fielddata, $values)
	{
		return JText::_('JYES');
	}
}