<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileControllerFieldgroup extends JoomprofileController
{	
	public $_name = 'fieldgroup';
	
	public function _save($itemid, $data)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$itemid = parent::_save($itemid, $data);
		if(!$itemid){
			return $itemid;
		}
		
		$model = $this->getModel('fieldgroup_usergroups');
		return $model->save($itemid, $data['jusergroups']);
	}
	
	public function loadfields()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		return true;
	}
	
	public function removeField()
	{
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$id = $this->input->get('id', 0);
		if(!$id){
			// @TODO : Error
			return false;
		}
		
		$mapping_id = $this->input->get('mapping_id', 0);
		if(!$mapping_id){
			// @TODO : Error
			return false;
		}
		
		$model = $this->getModel('field_fieldgroups');
		if(!$model->remove(array('`field_fieldgroup_id` = '.$mapping_id))){
			// @TODO : Error
			return false;
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();	
	}
	
	public function addField()
	{
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$id = $this->input->get('id', 0);
		if(!$id){
			// @TODO : Error
			return false;
		}
		
		$field_id = $this->input->get('field_id', 0);
		if(!$field_id){
			// @TODO : Error
			return false;
		}

		$fieldgroup = $this->getObject($id);
		list($fields, $mapping) = $fieldgroup->getFieldsAndMappings();
		
		$ordering = 1;
		if(count($mapping)){
			$mapping = array_pop($mapping);
			$ordering = $mapping->ordering + 1;
		}
		
		$model = $this->getModel('field_fieldgroups');
		if(!$model->save(0, array('field_id' => $field_id, 'fieldgroup_id' => $id, 'ordering'=>$ordering ))){
			// @TODO : ErrorremoveField
			return false;
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();		
	}
	
	public function changeFieldOrder()
	{
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		
		$id = $this->input->get('id', 0);
		if(!$id){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('Invalid Id');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$mapping_id = $this->input->get('mapping_id', 0);
		if(!$mapping_id){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('Invalid Field Id');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$otherid = $this->input->get('otherid', 0);
		if(!$otherid){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('Invalid field id to reorder.');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$model = $this->getModel('field_fieldgroups');
		$item  		= $model->getItem($mapping_id);
		$otheritem  = $model->getItem($otherid);
		
		$tmp_ordering = $item->ordering;
		$item->ordering = $otheritem->ordering;
		$otheritem->ordering = $tmp_ordering;

		if(!$model->save($mapping_id, (array)$item) || !$model->save($otherid, (array)$otheritem)){
			$response->error = true;
		}
		
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function boolean()
	{
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		
		$id = $this->input->get('id', 0);
		if(!$id){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('INVALID_ID');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$mapping_id = $this->input->get('mapping_id', 0);
		if(!$mapping_id){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('INVALID_FIELD_ID');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$access_parameter = $this->input->get('access_parameter', '');
		if(empty($access_parameter)){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('INVALID_ACCESS_PARAMETER');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$model 		= $this->getModel('field_fieldgroups');
		$item  		= $model->getItem($mapping_id);
		
		$item->$access_parameter = !$item->$access_parameter;

		if(!$model->save($mapping_id, (array)$item)){
			$response->error = true;
		}
		
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();

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
			$filter = array(' `fieldgroup_id` IN ('.implode(", ", $cids).') ');
			if(!$model->remove($filter)){
				$this->msg = JText::_("COM_JOOMPROFILE_ERROR_DELETE_MAPPING");
				$this->msg_type = 'error';
			}
		}
		
		return false;
	}
}