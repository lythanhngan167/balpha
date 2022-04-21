<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewJsonFieldgroup extends JoomprofileViewJson
{
	public $_name = 'fieldgroup';
	public $_path = __DIR__;
	
	public function loadfields()
	{
		$itemid = $this->getId();
		
		$all_fields = JoomprofileProfileHelper::getFields();
		
		$fields = false;
		if($itemid){
			$item = $this->getObject($itemid);			
			list($fields, $mappings) = $item->getFieldsAndMappings();
		}	
		
		if($fields){
			foreach($fields as $field){
				unset($all_fields[$field->getId()]);
			}
		}
		
		$template	= $this->getTemplate();
		$template->set('fields', $fields)
				 ->set('itemid', $itemid)
				 ->set('mappings', $mappings)
				 ->set('all_fields', $all_fields);
		
		$html = $template->render('admin.profile.'.$this->_name.'.fields');
		$response = new stdClass();
		$response->error = false;
		$response->html  = $html;
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();		
	}
}