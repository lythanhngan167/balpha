<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldTermsofservice extends JoomprofileLibField
{
	public $name = 'termsofservice';
	public $location = __DIR__;
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE "1" ';
		$query->where('('.$sql.')');
		return true;
	}
	
	public function getAppliedSearchHtml($fielddata, $values)
	{
		return JText::_('JYES');
	}
	
	public function getViewHtml($fielddata, $value, $user_id)
	{
		return $value ? JText::_('JYES') : '';
	}
}