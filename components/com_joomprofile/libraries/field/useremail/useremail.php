<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldUseremail extends JoomprofileLibField
{
	public $name = 'useremail';
	public $location = __DIR__;
	
	protected function _validateEmail($field, $value, $userid)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            array(JText::_('COM_JOOMPROFILE_ERROR_EMAIL_INVALID'));
        }

        $user = JoomprofileHelperJoomla::getUser(array('`email` = ' . JFactory::getDbo()->quote($value)));

        if (!empty($userid)) {
            $loggedinuser = JFactory::getUser($userid);
        } else {
            $loggedinuser = JFactory::getUser();
        }

        if (is_object($user) && isset($user->id) && $user->id && $loggedinuser->id != $user->id) {
            return array(JText::_('COM_JOOMPROFILE_ERROR_EMAIL_ALREADY_EXIST'));
        }

        return array();
    }

    protected function _validate($field, $value, $userid)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            array(JText::_('COM_JOOMPROFILE_ERROR_EMAIL_INVALID'));
        }

        $user = JoomprofileHelperJoomla::getUser(array('`email` = ' . JFactory::getDbo()->quote($value)));

        if (!empty($userid)) {
            $loggedinuser = JFactory::getUser($userid);
        } else {
            $loggedinuser = JFactory::getUser();
        }

        if (is_object($user) && isset($user->id) && $user->id && $loggedinuser->id != $user->id) {
            return array(JText::_('COM_JOOMPROFILE_ERROR_EMAIL_ALREADY_EXIST'));
        } else if (is_object($user) && isset($user->id) && $user->id && $loggedinuser->id == $user->id) {
            return array();
        }

        if(isset($field->params['enable_verification']) && $field->params['enable_verification']) {
            $session = JFactory::getSession();
            $sessionData = $session->get('JPPEV_VALIDATION_CODE', array());
            $fieldid = $field->id;

            if (empty($sessionData) || !isset($sessionData[$fieldid])) {
                return array(JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_NOT_VERFIFIED'));
            }

            $sessionData = $sessionData[$fieldid];

            if (isset($sessionData['email']) && $sessionData['email'] == $value) {
                if (isset($sessionData['verified']) && !$sessionData['verified']) {
                    return array(JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_NOT_VERFIFIED'));
                } else {
                    return array();
                }
            }

            return array(JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_NOT_VERFIFIED'));
        }

		return array();
	}
	
	public function buildSearchQuery($fielddata, $query, $value)
	{		
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE '.$db->quote('%'.$value.'%');
		$query->where('('.$sql.')');
		return true;
	}

	public function onJoomProfileSendVerificationEmail($field, $userid, $data)
	{

		$app = JFactory::getApplication();
        if($app->isAdmin()){
        	return true;
        }
        
        $input 	= $app->input;
        $email 	= $data['value'];
        $code 	= rand(10000000, 99999999);

        $valid = $this->_validateEmail($field, $email, $userid);
        if (!empty($valid)) {
            echo json_encode(array('error' => true));
            exit();
        }

        $result = $this->_sendEmail($email, $code);
        if($result == false ||  ($result instanceof Exception)){
        	echo json_encode(array('error' => true, 'html' => JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_ERROR_IN_SENDING_CODE')));
       }
        else{

        	$session 					= JFactory::getSession();
			$sessionData 				= $session->get('JPPEV_VALIDATION_CODE', array());
			$sessionData[$field->id]	= array('email' => $email, 'code' => $code, 'verified' => false);
			$session->set('JPPEV_VALIDATION_CODE', $sessionData);

        	echo json_encode(array('error' => false, 'html' => JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_EMAIL_SENT')));
       }
	        
 	   exit();
	}


	public function _sendEmail($email, $code)
    {
    	$config = JFactory::getConfig();
    	$data['fromname'] = $config->get('fromname');
    	$data['mailfrom'] = $config->get('mailfrom');
                
    	$user = JFactory::getUser();
    	$emailSubject = JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_EMAIL_SUBJECT');
    	$emailBodyAdmin = JText::sprintf('COM_JOOMPROFILE_PREEMAILVALIDATION_EMAIL_BODY', $email, $code);
    		
		return JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $email, $emailSubject, $emailBodyAdmin);
	}

	public function onJoomProfileValidateCode($field, $userid, $data)
	{

		$app = JFactory::getApplication();
        if($app->isAdmin()){
        	return true;
        }
        
        $email 			= $data['value'];
        $code  			= $data['code'];

        $session 		= JFactory::getSession();
		$sessionData 	= $session->get('JPPEV_VALIDATION_CODE', array());

		$fieldid 		= $field->id;

		if (empty($sessionData) || !isset($sessionData[$fieldid])) {
			$sessionData[$fieldid] = array();
		}


		$verified = false;
		if (isset($sessionData[$fieldid]['email']) && $sessionData[$fieldid]['email'] == $email) {
			if (isset($sessionData[$fieldid]['code']) && $sessionData[$fieldid]['code'] == $code){
				$verified = true;
			}
		}

		$sessionData[$fieldid]['verified'] = $verified;
		$session->set('JPPEV_VALIDATION_CODE', $sessionData);

		if($verified){
			echo json_encode(array('error' => false, 'html' => JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_EMAIL_VARIFIED')));
		} else {
			echo json_encode(array('error' => true, 'html' => JText::_('COM_JOOMPROFILE_PREEMAILVALIDATION_INVALID_CODE')));
		}
		
		exit();
	}

	
	    public function getViewHtml($fielddata, $value, $user_id)
	    {
		if(isset($fielddata->params['email_linkable']) && !empty($fielddata->params['email_linkable']))
		{
		    $path       = $this->location.'/templates';
		    $template   = new JoomprofileTemplate(array('path' => $path));              
		    $template->set('fielddata', $fielddata)->set('email', $value);
		    return $template->render('field.'.$this->name.'.view');         
		}
		
		return $value;
	    }
}

