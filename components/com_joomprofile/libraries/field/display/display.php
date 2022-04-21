<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldDisplay extends JoomprofileLibField
{
	public $name = 'display';
	public $location = __DIR__;
	protected $searchable = false;

	public function getUserEditHtml($fielddata, $value, $user_id)
	{
		return $this->getViewHtml($fielddata, $value, $user_id);
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
		$value = $fielddata->params['default_value'];
		return str_replace( "\n", "<br />", $value );
	}

	public function buildSearchQuery($fielddata, $query, $value)
	{	
		$query->clear();		
		return true;
	}				

	public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
	{
		return '';
	}

	public function getAppliedSearchHtml($fieldObj, $values)
	{
		return '';
	}

	public function isValueSearchable($fielddata, $value)
	{
		return false;
	}
}
