<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewHtmlField extends JoomprofileViewHtml
{
	public $_name = 'field';
	public $_path = __DIR__;
	
	protected function _grid($template)
	{
		$records = parent::_grid($template);
		$fieldgroup_mapping = JoomprofileProfileHelper::getFieldFieldgroupMapping();
		$fieldgroups = JoomprofileProfileHelper::getFieldgroups();
		$template->set('fieldgroups', $fieldgroups)
					->set('fieldgroup_mapping', $fieldgroup_mapping);
		return $records;
	}
}