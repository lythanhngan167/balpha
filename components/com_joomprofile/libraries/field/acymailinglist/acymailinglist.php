<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
    echo 'This module can not work without the AcyMailing Component';
}

class JoomprofileLibFieldAcymailinglist extends JoomprofileLibField
{
	public $name = 'acymailinglist';
	public $location = __DIR__;

    protected function _validate($field, $value, $user_id)
    {
        if ((bool)$field->params['multi_acymailing_list'] && is_array()) {
            return array(JText::_('COM_JOOMPROFILE_VALIDATION_INVALID_VALUE'));
        }

        return array();
    }

    public function onBeforeFieldSave($data, $user_id)
    {
        if (!isset($data['params']['acymailing_list'])) {
            $data['params']['acymailing_list'] = [];
        }
        
        return parent::onBeforeFieldSave($data, $user_id);
    }

	public function format($field, $value, $userid, $on)
	{
	    foreach($value as $key => $val){
	        $val = trim($val);
	        if(empty($val)){
	            unset($value[$key]);
	        }
	    }
	    $status = $this->__updateSubscription($value, $userid);
	    
	    return $value;
	}
	
	public function getUserEditHtml($fielddata, $value, $userid)
	{
	    $params     = $fielddata->params;
	    $path 		= $this->location.'/templates';
	    $template 	= new JoomprofileTemplate(array('path' => $path));
	    
	    $allowed_list       = $this->getListName($params['acymailing_list']);
	    $user_list          = is_array($value)? $value : array($value);
	    
	    $template->set('fielddata', $fielddata)
	           ->set('allowed_list', $allowed_list)
	           ->set('user_list', $user_list);
	    
	    return $template->render('field.'.$this->name.'.user.edit');
	}
	
	public function getViewHtml($fielddata, $value, $userid)
	{
	    // if there's no data is available in field_values table, then return this..
	    if(!empty($value)){
	        //get user's list names using listIds
	        $lists = $this->getListName($value);
	        
	        foreach ($lists as $list){
	            $list_name[] = $list->name;
	        }
	        
	        return JText::_(implode(',', $list_name));
	    }

	    return '';
	}

    public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
    {
        $params     = $fielddata->params;
        $fielddata->options = $this->getListName($params['acymailing_list']);

        $value = !is_array($value) ? array($value) : $value;
        return parent::getSearchHtml($fielddata, $value, $onlyFieldHtml);
    }

    public static function getOptions($field_id = null)
    {
        static $options = null;

        if($options === null){
            $db = JoomprofileHelperJoomla::getDBO();
            $query = "SELECT * FROM `#__joomprofile_fieldoption` ORDER BY `title`";
            $db->setQuery($query);

            $results = $db->loadObjectList();
            foreach($results as $result){
                $options[$result->field_id][$result->id] = $result;
            }
        }

        if($field_id){
            if(isset($options[$field_id])){
                return $options[$field_id];
            }

            return array();
        }

        return $options;
    }

    public function getAppliedSearchHtml($fielddata, $values)
    {
        return $this->getViewHtml($fielddata, $values, 0);
    }
	
	private function __getUserListSubscription($user_id)
	{
	    $db = JoomprofileHelperJoomla::getDBO();
	    
	    $query = 'SELECT `name`, `listid` FROM `#__acymailing_list`' .
	           'WHERE `listid` IN (SELECT `listid` FROM `#__acymailing_listsub`' .
	           'WHERE `subid` IN (SELECT `subid` FROM `#__acymailing_subscriber` WHERE `userid` =' .$user_id.' ))';
	    
	    $db->setQuery($query);
	    $result = $db->loadObjectList('listid');
	    return $result;
	}
	
	protected function getListName($listids)
	{
	    if (empty($listids)) {
	        return [];
        }

	    $listids = is_array($listids) ? $listids : (array)$listids;

	    $db = JoomprofileHelperJoomla::getDBO();
	    $query = $db->getQuery(true);
	    $query->select('`name`, `listid`')
                ->from('`#__acymailing_list`')
                ->orderBy('`ordering`');

	    if (!empty($listids) && !in_array(0, $listids)) {
            $query->where('`listid` IN (' . implode(',', $listids) . ')');
        }
	    
	    $db->setQuery($query);
	    $result = $db->loadObjectList('listid');
	    return $result;
	}
	
	private function __updateSubscription($lists, $userid)
	{
        //get user name and email
	    $user = JoomprofileHelperJoomla::getUser(array('`id` = '.JoomprofileHelperJoomla::getDbo()->quote($userid)), '`name`, `email`');
	    
	    //save in subscription table
	    $subscriberClass = acymailing_get('class.subscriber');
	    $subid = $subscriberClass->save($user);
	    
	    //check if user already have any subscriptions
	    $old_subscriptions  = $this->__getUserListSubscription($userid);
	    
	    if($old_subscriptions){
	        foreach($old_subscriptions as $subscription){
	            //check if user is already subscribed to new lists
	            //no need to subscribe again, unset listid
	            if(($key = array_search($subscription->listid, $lists)) !== false){
	                unset($lists[$key]);
	            }
	            else{
	                //unsubscribe to old lists
	                $newList = null;
	                $newList['status'] = 0;
	                $newSubscription[$subscription->listid] = $newList;
	            }
	        }
	    }
	    
	    foreach($lists as $listid){
	        $newList = null;
	        $newList['status'] = 1;
	        $newSubscription[$listid] = $newList;
	    }
	    
	    return $subscriberClass->saveSubscription($subid,$newSubscription);
	}
}
