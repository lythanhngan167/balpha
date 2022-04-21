<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldVideo extends JoomprofileLibField
{
	public $name = 'video';
	public $location = __DIR__;
	
	public function _validate($field, $value, $user_id)
	{
        //only accept youtube or vimeo URL
	    if(!preg_match("/^(http:\/\/|https:\/\/)(vimeo\.com|youtu\.be|www\.youtube\.com)\/([\w\/]+)([\?].*)?$/i", $value)){
	        return array(JText::_('COM_JOOMPROFILE_ERROR_VIDEO_INVALID_TYPE'));
	    }
	    else{
	        return array();
	    }
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
	    $path 		= $this->location.'/templates';
	    $template 	= new JoomprofileTemplate(array('path' => $path));
	    
	    if($value != null){
    	    $videoType  = $this->getVideoTypeFromUrl($value);
    	    $videoId    = $this->getVideoCodeFromUrl($value);
    	    
    	    $template->set('videoType', $videoType);
    	    $template->set('videoId', $videoId);
	    }
	    
	    $template->set('fielddata', $fielddata);
	    $template->set('value', $value);
	    
	    return $template->render('field.'.$this->name.'.user.view');
	}

	public function buildSearchQuery($fielddata, $query, $value)
	{
	    $sql ='`value` LIKE "%" ';
	    $query->where('('.$sql.')');
	    return true;
	}

	public function getAppliedSearchHtml($fieldObj, $values)
	{
	    return JText::_('JYES');
	}

	public function getVideoTypeFromUrl($url)
	{
	    // Youtube
	    if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) {
	        return "Youtube";
	        // Vimeo
	    } elseif (strpos($url, 'vimeo') !== false) {
	        return "Vimeo";
	        // Unknown
	    } else {
	        return '';
	    }
	}
	
	public function getVideoCodeFromUrl($url)
	{
	    $provider = self::getVideoTypeFromUrl($url);
	    // Youtube
	    if ($provider == "Youtube") {
	        parse_str(parse_url($url, PHP_URL_QUERY), $urlArray);
	        if (isset($urlArray['v'])) {
	            return $urlArray['v'];
	        }
	        if (strpos($url, 'youtu.be') !== false) {
	            $urlArrayShort = explode('/', $url);
	            $codeWithParams = $urlArrayShort[count($urlArrayShort) - 1];
	            $codeArray = explode('?', $codeWithParams);
	            
	            return $codeArray[0];
	        }
	        
	        return false;
	        // Vimeo
	    } elseif ($provider == "Vimeo") {
	        $urlArray = explode("/", $url);
	        
	        return isset($urlArray[count($urlArray) - 1]) ? $urlArray[count($urlArray) - 1] : false;
	        // Unknown
	    } else {
	        return '';
	    }
	    
	}
}
