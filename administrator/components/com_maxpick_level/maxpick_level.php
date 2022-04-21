<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Maxpick_level
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_maxpick_level'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Maxpick_level', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Maxpick_levelHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'maxpick_level.php');

$controller = JControllerLegacy::getInstance('Maxpick_level');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
