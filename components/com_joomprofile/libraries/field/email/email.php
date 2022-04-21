<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldEmail extends JoomprofileLibField
{
	public $name = 'email';
	public $location = __DIR__;
	
	public function buildSearchQuery($fielddata, $query, $value)
	{		
		$db = JoomprofileHelperJoomla::getDBO();
	
		$sql ='`value` LIKE '.$db->quote('%'.$value.'%');
		$query->where('('.$sql.')');
		return true;
	}

	 public function getViewHtml($fielddata, $value, $user_id)
	 {
		if(isset($fielddata->params['email_linkable']) && !empty($fielddata->params['email_linkable']))
		{
		    $path       = $this->location.'/templates';
		    $template   = new JoomprofileTemplate(array('path' => $path));              
		    $template->set('fielddata', $fielddata)->set('email', $value);
		    return $template->render('field.'.$this->name.'.view');         
		}
		
		return $value;
	}
}
