<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldJoomlalastlogindate extends JoomprofileLibField
{
	public $name = 'joomlalastlogindate';
	public $location = __DIR__;

	public function format($field, $value, $userid, $on)
	{
	    if(!empty($value)){
	        $date = new JDate($value);
	        $value = $date->format('Y-m-d');
	    }
	    
	    return parent::format($field, $value, $userid, $on);
	}
	
	public function getUserEditHtml($fielddata, $value, $user_id)
	{
		return $this->getViewHtml($fielddata, $value, $user_id);
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
	    if (!empty($user_id)) {
	        $user = JFactory::getUser($user_id);
	    } else {
	        return;
	    }
	    
	    $format = !empty($fielddata->params['login_date_display_format']) ? $fielddata->params['login_date_display_format'] : 'Y-m-d';
	    
	    $lastVisiteDate = $this->getLastLoginDate($user_id);

	    if($lastVisiteDate != '0000-00-00 00:00:00'){
            return JHtml::_('date', $lastVisiteDate, $format);
	    }
        else{
            return $lastVisiteDate;
        }

	}

	public function buildSearchQuery($fielddata, $query, $value)
	{	
	    if($value == null){
	        throw new Exception($fielddata->title.' : Invlaid value to search by Datepicker field');
	    }
	    
	    $values = explode(":", $value);
	    
	    //add start and end time in dates
	    $values[0] = $values[0]. ' 00:00:00';
	    $values[1] = $values[1]. ' 23:59:59';
	    
	    sort($values);
	    
	    $db      = JFactory::getDbo();
	    $where  = '`lastvisitDate` BETWEEN '.$db->quote($values[0]);
	    $where .= ' AND '.$db->quote($values[1]);
	    
	    $query->clear();
	    $query->select('DISTINCT id as user_id')
	    ->from('#__users')
	    ->where('('.$where.')');
	    return true;
	}

	public function getAssets($on, $field)
	{
	    if($on == self::ON_SEARCH){
	        // do not load multiple times
	        static $loaded = false;
	        if($loaded == true){
	            return '';
	        }
	        $path 		= $this->location.'/templates';
	        $template 	= new JoomprofileTemplate(array('path' => $path));
	        $template->set('field', $field->toArray());
	        
	        return $template->render('field.'.$this->name.'.search.assets');
	    }
	    
	    return parent::getAssets($on, $field);
	}
	
	public function getAppliedSearchHtml($fieldObj, $values)
	{
	    $format = !empty($fieldObj->params['login_date_display_format']) ? $fieldObj->params['login_date_display_format'] : 'Y-m-d';
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

	private function getLastLoginDate($userid)
	{   
	    $user = JoomprofileHelperJoomla::getUser(array('`id` = '.JFactory::getDbo()->quote($userid)), 'lastvisitDate');
	    
	    return $user->lastvisitDate;
	}
}
