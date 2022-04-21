<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldSelect extends JoomprofileLibField
{
	public $name = 'select';
	public $location = __DIR__;

    public function buildSearchQuery($fielddata, $query, $value)
    {
        $db = JoomprofileHelperJoomla::getDBO();

        $sql ='`value` = '.$db->quote($value);
        $query->where('('.$sql.')');
        return true;
    }

	public function onAfterFieldSave($field_id, $data, $userid)
	{		
		//TODO : delete users values, if any field is removed
		if(!$this->removeOptions($field_id)){
			throw new Exception('NOT ABLE TO DELETE OPTIONS');
		}
		
		if(!isset($data['params']) || !isset($data['params']['options'])){
			return true;
		}
		
		$options = $data['params']['options'];
		return $this->saveOptions($field_id, $options);		
	}
	
	public function getUserEditHtml($fielddata, $value, $userid)
	{
		$fielddata->options = $this->getOptions($fielddata->id);
		
		$value = !is_array($value) ? array($value) : $value;
		return parent::getUserEditHtml($fielddata, $value, $userid);
	}
	
	public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
	{		
		$fielddata->options = $this->getOptions($fielddata->id);
		
		$value = !is_array($value) ? array($value) : $value;
		return parent::getSearchHtml($fielddata, $value, $onlyFieldHtml);
	}
	
	public function getViewHtml($fielddata, $value, $userid)
	{	
		$options = $this->getOptions($fielddata->id);
		
		if(empty($options)){
			return '';
		}
		
		if(!is_array($value)){
			$value = array($value);
		}	
		
		$ret = array();
		foreach($value as $v){
			if(isset($options[$v])){
				$ret[] = JText::_($options[$v]->title);
			}		
		}
		
		return implode(", ", $ret);
	}
	
	public function getAppliedSearchHtml($fielddata, $values)
	{
		return $this->getViewHtml($fielddata, $values, 0); 
	}
}