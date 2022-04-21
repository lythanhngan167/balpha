<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldConfirm extends JoomprofileLibField
{
	public $name = 'confirm';
	public $location = __DIR__;

    protected $searchable = false;

    public function getViewHtml($fielddata, $value, $userid)
    {
        return '';
    }

    public function getUserEditHtml($fielddata, $value, $user_id)
    {
        $fieldid = $fielddata->params['field'];
        $app   = JoomprofileExtension::get('profile');
        $field = $app->getObject('field', 'Joomprofileprofile', $fieldid);

        if (!$field) {
            return '';
        }

        // TODO : override template
        $path 		= $this->location.'/templates';
        $template 	= new JoomprofileTemplate(array('path' => $path));

        $template->set('fielddata', $fielddata)
            ->set('value', $value)
            ->set('parentField', $field)
            ->set('user_id', $user_id);

        return $template->render('field.'.$this->name.'.user.edit');
    }
}