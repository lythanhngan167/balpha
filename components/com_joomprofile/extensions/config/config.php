<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileConfig extends JoomprofileExtension
{
	public $name = 'config'; 

	public function getConfig($ext_name)
	{
		$model = $this->getModel('config');
		$records = $model->getList();
		static $config = null;
		
		if($config === null){
			foreach ($records as $record){
				list($ext, $key) = explode('::', $record->id);			
				$config[$ext][$key] = json_decode($record->value, true);
				if(json_last_error()){
					$config[$ext][$key] = $record->value;
				}
			}
		}

		if(isset($config[$ext_name])){
			return $config[$ext_name];
		}
		
		return array();
	}	
	
	public function onJoomprofileAdminMenuRender(&$menus)
	{
		$menus[11] = array(
						'view'	=> 'config',
						'link'	=> 'index.php?option=com_joomprofile&view=config&task=config.grid',
						'text'	=> JText::_('COM_JOOMPROFILE_CONFIG'),
						'class' => 'fa fa-cog'
						);
	}
}