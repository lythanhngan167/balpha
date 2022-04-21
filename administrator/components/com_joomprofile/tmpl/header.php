<?php
/**
 * @package     Joomla.Admin
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
Jhtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select.chosen');

JHtml::script("media/com_joomprofile/js/joomprofile.js");
JHtml::script("media/com_joomprofile/js/validation.js");
JHtml::script("media/com_joomprofile/js/uploader.js");

JHtml::stylesheet('media/com_joomprofile/css/font-awesome.css');
JHtml::stylesheet('media/com_joomprofile/css/joomprofile.css');
JHtml::stylesheet('media/com_joomprofile/css/admin.css');