<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileLibUsergroup extends JoomprofileLib
{
	protected $usergroup_id		= 0;
	protected $title			= '';
	protected $params			= null;
	
	protected $_searchfields 	= array();
	protected $_searchmapping 	= array();
	
	protected function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->usergroup_id = 0;
		$this->title = '';
		$this->params = new JRegistry();
	}
	
	public function getId()
	{
		return $this->usergroup_id;
	}
	
	public function setId($id)
	{
		$this->usergroup_id = $id;
		return $this;
	}

	function getSearchFieldsAndMappings()
	{
		if($this->_searchfields == false){
			// TODO : use helper function instead of executing query
			$usergroup_fields = JoomprofileProfileHelper::getUsergroupSearchFields($this->usergroup_id);
				
			$model = $this->getModel('field');
			$fields = $model->getList();
			
			$this->_searchfields = array();
			foreach($usergroup_fields as $map_data){
				$this->_searchfields[$map_data->field_id] = JoomprofileProfileLibField::getObject('field', 'Joomprofileprofile', $map_data->field_id, array('app' => $this->_app, 'binddata' => $fields[$map_data->field_id]));
				//TODO : 				
				$map_data->params 		= new JRegistry();
				$this->_searchmapping[$map_data->field_id] = $map_data;
			}
		}
		
		return array($this->_searchfields, $this->_searchmapping);
	}

	public function bind($binddata)
	{
		$binddata = !is_array($binddata) ? (array) $binddata : $binddata;
		
		if(!isset($binddata['params']) || !isset($binddata['params']['not_searchable'])
			|| empty($binddata['params']) || empty($binddata['params']['not_searchable'])){
			$this->params->set('not_searchable', array());
		}

		parent::bind($binddata);		

		return $this;
	}
}