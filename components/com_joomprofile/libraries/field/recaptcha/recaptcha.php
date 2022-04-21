<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldRecaptcha extends JoomprofileLibField
{
	public $name = 'recaptcha';
	public $location = __DIR__;
	
	protected function _validate($field, $value, $userid)
	{
		$input      = JFactory::getApplication()->input;
		$privatekey = isset($field->params['private_key']) ? $field->params['private_key'] : '';		
		$remoteip   = $input->server->get('REMOTE_ADDR', '', 'string');

		// Challenge Not needed in 2.0 but needed for getResponse call
		$challenge = null;
		$response  = $value;
		$spam      = ($response == null || strlen($response) == 0);
		
		// Check for Private Key
		if (empty($privatekey)){
			return array(JText::_('COM_JOOMPROFILE_RECAPTCHA_ERROR_NO_PRIVATE_KEY'));
		}

		// Check for IP
		if (empty($remoteip)){
			return array(JText::_('COM_JOOMPROFILE_RECAPTCHA_ERROR_NO_IP'));
		}

		// Discard spam submissions
		if ($spam){
			return array(JText::_('COM_JOOMPROFILE_RECAPTCHA_ERROR_EMPTY_SOLUTION'));
		}

		if($this->getResponse($privatekey, $remoteip, $response)){
			return array();
		}

		return array(JText::_('COM_JOOMPROFILE_VALIDATION_INVALID_VALUE'));
	}

	private function getResponse($privatekey, $remoteip, $response)
	{
		if(!class_exists('JReCaptcha')){
			require_once __DIR__.'/recaptchalib.php';	
		}		

		$reCaptcha = new JReCaptcha($privatekey);
		$response  = $reCaptcha->verifyResponse($remoteip, $response);

		if ( !isset($response->success) || !$response->success){
			// @todo use exceptions here
			foreach ($response->errorCodes as $error){
				$this->_subject->setError($error);
			}

			return false;
		}
		
		return true;
	}

	public function getUserEditHtml($fielddata, $value, $user_id)
	{		
		if($user_id){
			return JText::_('COM_JOOMPROFILE_NOT_APPLICABLE');
		}

		// TODO : override template
		$path 		= $this->location.'/templates';
		$template 	= new JoomprofileTemplate(array('path' => $path));
		
		$template->set('fielddata', $fielddata)
				->set('value', $value)
				->set('user_id', $user_id);
				
		
			
		$pubkey     = isset($fielddata->params['public_key']) ? $fielddata->params['public_key'] : '';
		if ($pubkey == null || $pubkey == ''){
			throw new Exception(JText::_('COM_JOOMPROFILE_RECAPTCHA_ERROR_NO_PUBLIC_KEY'));
		}
		
		$theme = isset($fielddata->params['theme']) ? $fielddata->params['theme'] : 'clean';
		$file = JFactory::getApplication()->isSSLConnection() ? 'https' : 'http';
		$file .= '://www.google.com/recaptcha/api.js?hl=' . JFactory::getLanguage()
					->getTag() . '&render=explicit';

		$template->set('pubkey', $pubkey)
					->set('script_url', $file)
					->set('theme', $theme);

		return $template->render('field.'.$this->name.'.user.edit');
	}

	public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
	{	
		return '';
	}
	
	public function getViewHtml($fielddata, $value, $userid)
	{
		return JText::_('COM_JOOMPROFILE_VERIFIED');
	}
}