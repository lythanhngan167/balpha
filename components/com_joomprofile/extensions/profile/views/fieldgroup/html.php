<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewHtmlFieldgroup extends JoomprofileViewHtml
{
	public $_name = 'fieldgroup';
	public $_path = __DIR__;
	
	protected function _grid($template)
	{
		$fieldgroup_mapping = JoomprofileProfileHelper::getFieldgroupUsegroupMapping();
		$usergroups = JoomprofileHelperJoomla::getUsergroups();
		$template->set('usergroups', $usergroups)
					->set('fieldgroup_mapping', $fieldgroup_mapping);
					
		$model = $this->getModel();
		$query = $model->getQuery();
		$query->clear('order')->order('ordering');
		return $model->getGridItemList($query);
	}
}
