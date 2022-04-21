<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileLibFieldGroup extends JoomprofileLib
{
	protected $id 			= 0;
	protected $ordering		= 0;
	protected $title 		= 0;
	protected $description	= '';
	protected $published 	= 0;
	protected $registration	= 0;
	protected $params 		= 0;	
	protected $jusergroups = array();
	
	protected $_fields = false; // IMP
	protected $_mapping = false; // IMP

	protected function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->id 			= 0;
		$this->ordering 	= 0;
		$this->title 		= '';
		$this->description 	= '';
		$this->published 	= true;
		$this->registration	= true;
		$this->css_classes 	= '';
		$this->params 		= new JRegistry();
	}
	
	public function load($id)
	{
		parent::load($id);
		
		$this->jusergroups = $this->getModel('fieldgroup_usergroups')->getUsergroups($id);			
		return $this;
	}
	
	public function save()
	{
		if(parent::save() === false){
			return false;
		}
		// @TODO : move to model
		$model = $this->getModel('fieldgroup_usergroups');
		return $model->save($this->id, $this->jusergroups);
	}
	
	function getFieldsAndMappings()
	{
		if($this->_fields == false){
			// TODO : use helper function instead of executing query
			$model = $this->getModel('field_fieldgroups');
			$query = $model->getQuery();
			$query->clear('order')
					->where('`fieldgroup_id` = '.$this->id)
					->order('`ordering`');
			$fieldgroup_fields = $model->getList($query);
					
			$fields = JoomprofileProfileHelper::getFields();
			
			$this->_fields = array();
			foreach($fieldgroup_fields as $map_data){
				$this->_fields[$map_data->field_fieldgroup_id] = JoomprofileProfileLibField::getObject('field', 'Joomprofileprofile', $map_data->field_id, array('app' => $this->_app), $fields[$map_data->field_id]);
				//TODO : 				
				$map_data->params 		= new JRegistry();
				$this->_mapping[$map_data->field_fieldgroup_id] = $map_data;
			}
		}
		
		return array($this->_fields, $this->_mapping);
	}
	
	public function getTitle()
	{
		return $this->title;
	}
}