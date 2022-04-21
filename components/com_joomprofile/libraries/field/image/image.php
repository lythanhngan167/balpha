<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldImage extends JoomprofileLibField
{
	public $name = 'image';
	public $location = __DIR__;
	
	public function _validate($field, $value, $user_id)
	{	
		$allowed_extension = $field->params['allowed_ext'];
	 
		// remove all space
		if(empty($allowed_extension)){
			return array();
		}

		$mimeTypes 			= $this->getAllowedMimeTypes();
		$upload_extensions 	= array_keys($mimeTypes);
		$upload_mime		= $mimeTypes;

		$format 	= strtolower(JFile::getExt($value));
		if ($format == '' || $format == false || (!in_array($format, $allowed_extension))) {
			return array(JText::_('COM_JOOMPROFILE_ERROR_FILE_NOT_SUPPORTED'));
		}

		if(!in_array($format, $allowed_extension)) 
		{ 
			// if its not an image...and we're not ignoring it	
			$allowed_mime = $mimeTypes;
			if(function_exists('finfo_open')) 
			{// We have fileinfo
				$finfo	= finfo_open(FILEINFO_MIME);
				$type	= finfo_file($finfo, $_FILES['joomprofile-field']['tmp_name'][$field->id]);
				$type 	= explode(';', $type);
				$type 	= array_shift($type);
				
				if(!isset($allowed_mime[$format]) || ($allowed_mime[$format] != $type && 
						(is_array($allowed_mime[$format]) && !in_array($type, $allowed_mime[$format])))) 
				{
					return array(JText::_('COM_JOOMPROFILE_ERROR_FILE_INVALID_MIME'));
				}
				finfo_close($finfo);
			} 
			else if(function_exists('mime_content_type')) 
			{ // we have mime magic
				$type = mime_content_type($_FILES['joomprofile-field']['tmp_name'][$field->id]);
				if(strlen($type) && !in_array($type, $allowed_mime)) 
				{
					return  array(JText::_('COM_JOOMPROFILE_ERROR_FILE_INVALID_MIME'));
				}
			}
		}
				
		return array();
	}
	
	public function getViewHtml($fielddata, $value, $userid)
	{
		// XITODO : override template
		$path 		= $this->location.'/templates';
		$template 	= new JoomprofileTemplate(array('path' => $path));
		
		$template->set('fielddata', $fielddata);
		$template->set('value', $value);
				
		return $template->render('field.'.$this->name.'.user.view');	
	}
	
	public function format($field, $value, $userid, $on)
	{  
		if($on == JOOMPROFILE_PROFILE_ON_SAVE){
			$ext = JFile::getExt($value);  
			$dest = JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid.'/'.$field->id.'/';
			
			// if destination is in source
			if(strpos($value, $dest) === false){
				if(!JFolder::exists(JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid)){
					if(!JFolder::create(JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid)){
						return $value;
						//@TODO : error
					}
				}
				
				if(JFolder::exists(JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid.'/'.$field->id)){
					if(!JFolder::delete(JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid.'/'.$field->id)){
						return $value;
						//@TODO : error
					}
				}
				
				if(!JFolder::create(JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_USER.'/'.$userid.'/'.$field->id)){
					return $value;
					//@TODO : error
				}
				
				$dest .=  JFile::getName($value);
				if(!JFile::move(JPATH_SITE.'/'.$value, JPATH_SITE.'/'.$dest)){
					return $value;
					//@TODO : error
				}
				
				return $dest;			
			}
		}
		else{
			return $value;
		}		
	}
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE "%" ';
		$query->where('('.$sql.')');
		return true;
	}
	
	public function getAppliedSearchHtml($fielddata, $values)
	{
		return JText::_('JYES');
	}

	public function getExportValue($fielddata, $value, $user_id)
	{
		return $value;
	}

	public function getAllowedMimeTypes()
	{
		return array(
					
					'bmp' => 'image/bmp' , 
					'gif' => 'image/gif' , 
					'jpeg' => 'image/jpeg' , 
					'jpg' => 'image/jpeg' , 
					'jpe' => 'image/jpeg' , 
					'png' => 'image/png'
		);
	}
}
