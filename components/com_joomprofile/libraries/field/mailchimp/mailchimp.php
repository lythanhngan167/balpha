<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldMailchimp extends JoomprofileLibField
{
	public $name = 'mailchimp';
	public $location = __DIR__;
	
	public function format($field, $value, $userid, $on)
	{
	    foreach($value as $key => $val){
	        $val = trim($val);
	        if(empty($val)){
	            unset($value[$key]);
	        }
	    }
	    $status = $this->__updateSubscription($value, $userid, $field);
	    
	    //if data is not updated in mailchimp, no need to update it in our table
	    if ($status['error']) {
	        return;
	    }
	    
	    return $value;
	}
	
	public function getUserEditHtml($fielddata, $value, $userid)
	{
	    $params     = $fielddata->params;
	    $path 		= $this->location.'/templates';
	    $template 	= new JoomprofileTemplate(array('path' => $path));
	    
	    $allowed_list   = $this->__getList($params['mailchimp_list'], $params['mailchimp_api_key']);
	    $user_list      = is_array($value)? $value : array($value);
	    
	    $template->set('fielddata', $fielddata)
	           ->set('allowed_list', $allowed_list)
	           ->set('user_list', $user_list);
	    
	    return $template->render('field.'.$this->name.'.user.edit');
	}
	
	public function getViewHtml($fielddata, $value, $userid)
	{
	    // if there's no data is available in field_values table, then return this..
	    if(empty($value)){
	        return '';
	    }
	    else{
	        //get user's list names using listIds
	        $list_name = $this->__getList($value, $fielddata->params['mailchimp_api_key'], 'name');
	        
	        return JText::_(implode(',', $list_name));
	    }
	}
	
    //get user's current subscriptions lists-id
    private function __getUserListSubscription($user_id, $field_id)
    {
        $db = JoomprofileHelperJoomla::getDBO();
        
        $query = 'SELECT `value` FROM `#__joomprofile_field_values` ' .
            'WHERE `field_id` = '.$field_id.' AND `user_id` = '.$user_id;
        
        $db->setQuery($query);
        $result = $db->loadColumn();
        
        if($result == null){
            $result = array();
        }
        
        return $result;
    }
    
	//get selected lists using listIds
	private function __getList($listIds, $apiKey, $what = '')
	{
	    $lists   = $this->__getAllLists($apiKey);
	    
	    $listIds = is_array($listIds) ? $listIds : array($listIds);
	    
	    foreach($lists as $key => $list){
	        //if we need only list name, then send this only
	        if($what == 'name'){
	            $lists[$key] = $list['name'];
	        }
	        
	        if(!in_array($list['id'], $listIds)){
	            unset($lists[$key]);
	        }
	    }
	    return $lists;
	}
	
	//get all lists using API Key
	private function __getAllLists($apiKey)
	{
	    static $lists = [];

	    if (!isset($lists[$apiKey])) {
            $auth = 'Basic ' . base64_encode('user:' . $apiKey);

            $dataCenter = $this->getDataCenter($apiKey);

            $url = 'https://' . $dataCenter . 'api.mailchimp.com/3.0/lists/';
            $result = $this->_sendRequest($url, false, $auth, 'get');

            if ($result['error']) {
                return $result;
            }

            $data = json_decode($result['data'], true);

            $lists[$apiKey] = [];
            if (isset($data['lists'])) {
                $lists[$apiKey] = $data['lists'];
            }
        }
	    
	    return $lists[$apiKey];
	}
	
	private function __updateSubscription($lists, $userid, $field)
	{
        //get user name and email
	    $user = JoomprofileHelperJoomla::getUser(array('`id` = '.JoomprofileHelperJoomla::getDbo()->quote($userid)), '`name`, `email`');
	    
	    $params     = $field->params;
	    $auth       = 'Basic '.base64_encode('user:'.$params['mailchimp_api_key']);
	    $dataCenter = $this->getDataCenter($params['mailchimp_api_key']);
	    $memberId   = md5(strtolower($user->email));
	    
	    //get user's current subscriptions lists
	    $current_lists = $this->__getUserListSubscription($userid, $field->id);
	    
	    //lists to subscribe user
	    $subscribeToLists = array_diff($lists, $current_lists);
	    
	    //lists to delete user
	    $deleteFromLists = array_diff($current_lists, $lists);
	    
	    $args = array(
	        'apikey'        => $params['mailchimp_api_key'],
	        'email_address' => $user->email,
	        'status'        => 'subscribed',
	        'merge_fields'  => array(
	            'FNAME' => $user->name,
	            'LNAME' => ""
	        )
	    );
	    
	    $json_args = json_encode($args);
	    
	    foreach($subscribeToLists as $subList){
	        $url    = 'https://'.$dataCenter.'api.mailchimp.com/3.0/lists/'.$subList.'/members/';
	        $result = $this->_sendRequest($url, $json_args, $auth, 'post');
	    }
	    
	    foreach($deleteFromLists as $delList){
	        $url    = 'https://'.$dataCenter.'api.mailchimp.com/3.0/lists/'.$delList.'/members/'.$memberId;
	        $result = $this->_sendRequest($url, false, $auth, 'delete');
	    }
	    
	    return $result;
	}
	
	protected static function getDataCenter($apiKey)
	{
	    // find the data center from the api key
	    // otherwise leave it empty
	    $dataCenter = "";
	    $dataCenter = explode("-", $apiKey);
	    if (isset($dataCenter[1])) {
	        $dataCenter = $dataCenter[1].".";
	    } else {
	        $dataCenter = "";
	    }
	    
	    return $dataCenter;
	}
	
	protected static function _sendRequest($url, $args, $auth, $action)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    
	    if ($auth) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: '.$auth));
	    }
	    
	    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    
	    switch($action){
	        case 'post':
	            curl_setopt($ch, CURLOPT_POST, true);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
	            break;
	            
	        case 'get':
	            curl_setopt($ch, CURLOPT_URL, $url);
	            break;
	            
	        case 'delete':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	            break;
	    }
	    
	    $result = curl_exec($ch);
	    
	    $err = curl_error($ch);
	    curl_close($ch);
	    
	    if (!empty($err)) {
	        return array('error' => true, 'data' => $err);
	    }
	    
	    return array('error' => false, 'data' => $result);
	}

    public function formatConfigForm($form, $data)
    {
        if (!empty($data->mailchimp_api_key)) {
            $form->setFieldAttribute('mailchimp_list', 'apiKey', $data->mailchimp_api_key, 'params');
        } else {
            $form->setFieldAttribute('mailchimp_list', 'apiKey', '', 'params');
        }

        return $form;
    }

    public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
    {
        $params     = $fielddata->params;
        $fielddata->options = $this->__getList($params['mailchimp_list'], $params['mailchimp_api_key']);

        $value = !is_array($value) ? array($value) : $value;
        return parent::getSearchHtml($fielddata, $value, $onlyFieldHtml);
    }

    public function getAppliedSearchHtml($fielddata, $values)
    {
        return $this->getViewHtml($fielddata, $values, 0);
    }
}
