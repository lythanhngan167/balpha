<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldJoomlalanguage extends JoomprofileLibField
{
    public $name            = 'joomlalanguage';
    public $location        = __DIR__;
    protected $searchable   = false;

    public function format($field, $value, $userid, $on)
    {
        if ($on == JOOMPROFILE_PROFILE_ON_SAVE) {
            $user = JFactory::getUser($userid);
            $user->setParam('language', $value);
            $user->save();
            return 0;
        }

        return $value;
    }

    public function loadValue($field, $value, $userid)
    {
        $user = JFactory::getUser($userid);
        return $user->getParam('language', $value);
    }

    public function getViewHtml($fielddata, $value, $user_id)
    {
        $languages = JLanguageHelper::getInstalledLanguages(0, true);
        if ($value === 0 || !isset($languages[$value])) {
            return JText::_('COM_JOOMPROFILE_DEFAULT');
        }

        return $languages[$value]->metadata['nativeName'];
    }

    public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
    {
        return false;
    }

    public function buildSearchQuery($fielddata, $query, $value)
    {
        return true;
    }

    public function isValueSearchable($fielddata, $value)
    {
        return false;
    }
}