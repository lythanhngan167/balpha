<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldNumeric extends JoomprofileLibField
{
	public $name = 'numeric';
	public $location = __DIR__;
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		if(!is_array($value)){
			throw new Exception($fielddata->title.' : Invlaid value to search by Numeric field');
		}
		$values = $value;
		if(count($value)==1){
			$values[] = array_shift($value);
		}
		
		sort($values);
		
		$db = JoomprofileHelperJoomla::getDBO();
		$sql  = '`value` BETWEEN '.intval($values[0]);
		$sql .= ' AND '.intval($values[1]);
		$query->where('('.$sql.')');
		return true;
	}
	
	public function getAppliedSearchHtml($fielddata, $values)
	{
		$value = $values;
		if(count($value)==1){
			$value[] = array_shift($values);
		}
		
		sort($value);

		return $value[0] . ' - ' . $value[1];
	}
}