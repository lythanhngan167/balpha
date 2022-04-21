<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileConfigControllerConfig extends JoomprofileController
{	
	public $_name = 'config';
	
	protected function _save($itemid, $data)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$final_data = array();
		foreach($data as $ext_name => $values){
			foreach ($values as $key => $value){
				if(is_array($value)){
					$value = json_encode($value);
				}
				$final_data[$ext_name.'::'.$key] = $value; 
			}
		}
		
		$model 	= $this->getModel($this->_name);		
		return $model->save($itemid, $final_data);
	}
}