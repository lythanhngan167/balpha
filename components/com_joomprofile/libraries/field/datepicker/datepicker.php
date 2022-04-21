<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldDatepicker extends JoomprofileLibField
{
	public $name = 'datepicker';
	public $location = __DIR__;
	
	public function format($field, $value, $userid, $on)
	{
		if(!empty($value)){
			$date = new JDate($value);
			$value = $date->format('Y-m-d');
		}

		return parent::format($field, $value, $userid, $on);
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
		$format = !empty($fielddata->params['display_format']) ? $fielddata->params['display_format'] : 'Y-m-d';
		if(!empty($value)){
			$date = new JDate($value);
			$value = $date->format($format);
		}

		return parent::getViewHtml($fielddata, $value, $user_id);
	}

	public function buildSearchQuery($fielddata, $query, $value)
	{
	    if($value == null){
	        throw new Exception($fielddata->title.' : Invlaid value to search by Datepicker field');
	    }
	    
	    $values = explode(":", $value);
		
		sort($values);
		
		$db = JoomprofileHelperJoomla::getDBO();
		$sql  = '`value` BETWEEN DATE('.$db->quote($values[0]).')';
		$sql .= ' AND DATE('.$db->quote($values[1]).')';
		$query->where('('.$sql.')');
		return true;
	}

	public function getAppliedSearchHtml($fieldObj, $values)
	{
	    $format = !empty($fieldObj->params['display_format']) ? $fieldObj->params['display_format'] : 'Y-m-d';
	    $values = explode(":", $values);
		
		sort($values);

        $date1 = JFactory::getDate($values[0]);
        $date2 = JFactory::getDate($values[1]);

        $today = JFactory::getDate()->format('Y-m-d');
        $yesterday = JFactory::getDate('now -1 day')->format('Y-m-d');

        if($date1->format('Y-m-d') == $date2->format('Y-m-d') && $date1->format('Y-m-d') == $today){
            return JText::_('JOOMPROFILE_DATERANGEPICKER_TODAY');
        }
        elseif($date1->format('Y-m-d') == $date2->format('Y-m-d') && $date1->format('Y-m-d') == $yesterday){
            return JText::_('JOOMPROFILE_DATERANGEPICKER_YESTERDAY');
        }
        else{
            return $date1->format($format).' '.JText::_('COM_JOOMPROFILE_TO').' '.$date2->format($format);
        }
	}
}