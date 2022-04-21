<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileHelperField
{
	public static function save($userid, $fieldValues, $fieldPrivacy)
	{
		if(empty($fieldValues)){
			return true;
		}

		// delete the fields first 
		$field_ids = array_keys($fieldValues);
		$filter = array('`field_id` IN ('.implode(', ', $field_ids).')', '`user_id` = '.$userid);
		
		$db = JFactory::getDbo();
		//@TODO : use model if required
		$query = "DELETE FROM `#__joomprofile_field_values` WHERE ".implode(' AND ', $filter);
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}
		
		$fieldValues = (array)$fieldValues;
		$query = 'INSERT INTO `#__joomprofile_field_values` (`field_id`, `user_id`, `value`, `privacy`) VALUES ';
		$hasValues = false;
		foreach($fieldValues as $field_id => $values){
			if(is_array($values)){
				foreach($values as $value){
				    if ($value !== "") {
                        $query .= '(' . $field_id . ', ' . $userid . ', ' . $db->quote($value) . ', ' . $db->quote(@$fieldPrivacy[$field_id]) . '),';
                        $hasValues = true;
                    }
				}	
			}
			else{
                if ($values !== "") {
                    $query .= '(' . $field_id . ', ' . $userid . ', ' . $db->quote($values) . ', ' . $db->quote(@$fieldPrivacy[$field_id]) . '),';
                    $hasValues = true;
                }
			}
		}

		if ($hasValues) {
            $query = rtrim($query, ",");
            $db->setQuery($query);
            if ($db->query()) {
                return true;
            }
        }

		return true;
	}
	
	// IMP : Add privacy at index 10 or later
	static $privacy = array();
	public static function getPrivacyOptions()
	{
		self::$privacy[0] = JText::_('Public');
		self::$privacy[1] = JText::_('Site Members');
		self::$privacy[2] = JText::_('Only Me');

		return self::$privacy;
	}
}
