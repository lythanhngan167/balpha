<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileControllerField extends JoomprofileController
{	
	public $_name = 'field';
	
	public function config()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		return true;
	}
	
	public function validate()
	{
		return true;
	}
	
	protected function _save($itemid, $data)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $field = $this->app->getField($data['type']);
        // @TODO : move to proper location
        $app = JFactory::getApplication();
        if($app->isAdmin()){
            $userid = $this->input->get('user_id', 0);
            $user = JFactory::getUser($userid);
        }
        else{
            $user = JFactory::getUser();
        }

        $data = $field->onBeforeFieldSave($data, $user->id);
		$return = parent::_save($itemid, $data);
		$field->onAfterFieldSave($return, $data, $user->id);
		
		return $return;
	}
	
	public function remove()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$cids = $this->input->get('cid', array(), 'array');
		// call parent remove
		parent::remove();

		if($this->msg_type != 'error'){
			// delete mapping
			$model = $this->getModel('field_fieldgroups');
			$filter = array(' `field_id` IN ('.implode(", ", $cids).') ');
			if(!$model->remove($filter)){
				$this->msg = JText::_("COM_JOOMPROFILE_ERROR_DELETE_MAPPING");
				$this->msg_type = 'error';
			}
			
			// delete mapping
			$model = $this->getModel('usergroup_searchfields');
			$filter = array(' `field_id` IN ('.implode(", ", $cids).') ');
			if(!$model->remove($filter)){
				$this->msg = JText::_("COM_JOOMPROFILE_ERROR_DELETE_MAPPING");
				$this->msg_type = 'error';
			}
			
			// delete mapping
			$model = $this->getModel('field_values');
			$filter = array(' `field_id` IN ('.implode(", ", $cids).') ');
			if(!$model->remove($filter)){
				$this->msg = JText::_("COM_JOOMPROFILE_ERROR_DELETE_USER_VALUES");
				$this->msg_type = 'error';
			}
		}
		
		return false;
	}

	public function trigger() 
	{
		return true;
	}
}