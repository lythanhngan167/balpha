<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileViewJson extends JoomprofileViewHtml{
	
	public function getPrefix()
	{
		if (empty($this->_prefix))
		{
			$r = null;
			if (!preg_match('/(.*)Viewjson/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_VIEWJSON_GET_PREFIX'), 500);
			}
			$this->_prefix = strtolower($r[1]);
		}

		return $this->_prefix;
	}
	
	public function getName()
	{
		if (empty($this->_name))
		{
			$r = null;
			if (!preg_match('/Viewjson(.*)/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_VIEWJSON_GET_NAME'), 500);
			}
			$this->_name = strtolower($r[1]);
		}

		return $this->_name;
	}

	public function setupScript()
	{
		return '';
	}
}