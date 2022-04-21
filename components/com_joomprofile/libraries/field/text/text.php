<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldText extends JoomprofileLibField
{
	public $name = 'text';
	public $location = __DIR__;
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		$db  = JoomprofileHelperJoomla::getDBO();
		$sql = 'MATCH(`value`) AGAINST ('.$db->quote($value).' IN BOOLEAN MODE)';
		$query->where('('.$sql.')');
		return true;
	}
}