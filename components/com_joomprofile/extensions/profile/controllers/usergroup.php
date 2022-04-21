<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileControllerUsergroup extends JoomprofileController
{	
	public $_name = 'usergroup';
	
	public function loadSearchFields()
	{
		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}
		
		return true;
	}
	

	public function removeSearchField()
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
		
		$model = $this->getModel('usergroup_searchfields');
		if(!$model->remove(array('`usergroup_searchfield_id` = '.$mapping_id))){
			// @TODO : Error
			return false;
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();	
	}
	
	public function addSearchField()
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

		$usergroup = $this->getObject($id);
		list($fields, $mapping) = $usergroup->getSearchfieldsAndMappings();
		
		$ordering = 1;
		if(count($mapping)){
			$mapping = array_pop($mapping);
			$ordering = $mapping->ordering + 1;
		}
		
		$model = $this->getModel('usergroup_searchfields');
		if(!$model->save(0, array('field_id' => $field_id, 'usergroup_id' => $id, 'ordering'=>$ordering ))){
			// @TODO : ErrorremoveField
			return false;
		}
		
		$response = new stdClass();
		$response->error = false;
		$response->html  = '';
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();		
	}
	
	public function changeSearchFieldOrder()
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
		
		$model = $this->getModel('usergroup_searchfields');
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
		
		$parameter = $this->input->get('parameter', '');
		if(empty($parameter)){
			// @TODO : Error
			$response->error = true;
			$response->html  = JText::_('INVALID_PARAMETER');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}
		
		$model 		= $this->getModel('usergroup_searchfields');
		$item  		= $model->getItem($mapping_id);
		
		$item->$parameter = !$item->$parameter;

		if(!$model->save($mapping_id, (array)$item)){
			$response->error = true;
		}
		
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();

	}
}