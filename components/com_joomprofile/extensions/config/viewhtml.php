<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileConfigViewHtmlConfig extends JoomprofileViewHtml
{
	public $_name = 'config';
	
	public function grid()
	{
		$form = JForm::getInstance('joomprofile.config.config', 
						dirname(__FILE__).'/config.xml', 
						array('control' => 'joomprofile_form'));

		$config = $this->app->getConfig('config');
		$form->bind(array('config' => $config));
		
		$template = $this->getTemplate();
		$template->set('triggered', $this->triggered)
				->set('form', $form);
		return $template->render('admin.config.'.$this->_name.'.grid');
	}
}