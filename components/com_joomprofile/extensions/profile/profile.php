<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfile extends JoomprofileExtension
{
	public $name = 'profile'; 

	public function getConfig()
	{
		$config_app = JoomprofileExtension::get('config');
		return $config_app->getConfig('profile');
	}
	
	public function getField($fieldname)
	{
		return JoomprofileLibField::get($fieldname);
	}
	
	public function onJoomprofileAdminViewBeforeRender($ext_name, JoomprofileViewHtml $view)
	{
		if($ext_name != 'config'){
			return '';
		}
		
		$app = JoomprofileExtension::get('config');
		$config = $app->getConfig('profile');
		
		$form = JForm::getInstance('joomprofile.profile.config', 
						dirname(__FILE__).'/models/forms/config.xml', 
						array('control' => 'joomprofile_form'));
		
		$form->bind(array('profile' => $config));
		$template = $this->getTemplate();
		$template->set('form', $form);
		return $template->render('admin.profile.config');
	}

	public function onJoomprofileAdminMenuRender(&$menus)
	{
		$menus[21] = array(
						'view'	=> 'profile',
						'text'	=> JText::_('COM_JOOMPROFILE_PROFILE'),
						'class' => 'fa fa-user',
						'menus' => array(
									array('view' => 'field', 'link' => 'index.php?option=com_joomprofile&view=profile&task=field.grid', 'text' => JText::_('COM_JOOMPROFILE_FIELD')),
									array('view' => 'fieldgroup', 'link' => 'index.php?option=com_joomprofile&view=profile&task=fieldgroup.grid', 'text' => JText::_('COM_JOOMPROFILE_FIELDGROUP')),
									array('view' => 'user', 'link' => 'index.php?option=com_joomprofile&view=profile&task=user.grid', 'text' => JText::_('COM_JOOMPROFILE_USER')),
									array('view' => 'usergroup', 'link' => 'index.php?option=com_joomprofile&view=profile&task=usergroup.grid', 'text' => JText::_('COM_JOOMPROFILE_USERGROUP'))
								)
						);
	}
}
