<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldLink extends JoomprofileLibField
{
	public $name = 'link';
	public $location = __DIR__;
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		$query->clear();		
		return true;
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
		if(!empty($value)){
			$title = !empty($fielddata->params['display_title']) ? $fielddata->params['display_title'] : JText::_($fielddata->title);
			$target = !empty($fielddata->params['target']) ? $fielddata->params['target'] : "_blank";
			return '<a href="'.$value.'" target="'.$target.'">'.$title.'</a>';
		}		
	}
}