<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
class JoomprofileFormFieldMailchimplists extends JFormFieldList
{	
	protected $type = 'mailchimplists';

	protected function getInput()
    {
        //have to remove this apikey
        $apiKey = (string) $this->element['apiKey'];
        if (empty($apiKey)) {
            return JText::_('COM_JOOMPROFILE_FIELD_MAILCHIMP_ENTER_API_KEY');
        }
        
        $return = parent::getInput();

        if (!empty($this->error)) {
            return $this->errorMessage;
        }

        return $return;
    }

    protected function getOptions()
	{
        //have to remove this apikey
        $apiKey = (string) $this->element['apiKey'];
        if (empty($apiKey)) {
            return [];
        }

		$options = array();
		$options = parent::getOptions();


		$auth = 'Basic '.base64_encode('user:'.$apiKey);
		
		// find the data center from the api key
		// otherwise leave it empty
		$dataCenter = "";
		$dataCenter = explode("-", $apiKey);
		if (isset($dataCenter[1])) {
		    $dataCenter = $dataCenter[1].".";
		} else {
		    $dataCenter = "";
		}
		
		$url = 'https://'.$dataCenter.'api.mailchimp.com/3.0/lists/';
		$result = self::_sendRequest($url, false, $auth);

		if ($result['error']) {
		    return $result;
		}
		
		$data = json_decode($result['data'], true);

		if (!isset($data['lists'])) {
		    $this->error = true;
            $this->errorMessage = $data['title'].' : '.$data['detail'];
            return [];
        }
		if (isset($data['lists'])) {
		    $lists = $data['lists'];
		}
		
		foreach($lists as $list)
		{
			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', $list['id'],
				$list['name'], 'value', 'text');
		}

		reset($options);

		return $options;
	}
	
	protected static function _sendRequest($url, $args, $auth)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    
	    if ($auth) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: '.$auth));
	    }
	    
	    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    
	    
	    $result = curl_exec($ch);
	    
	    $err = curl_error($ch);
	    curl_close($ch);
	    
	    if (!empty($err)) {
	        return array('error' => true, 'data' => $err);
	    }

	    return array('error' => false, 'data' => $result);
	}
} 