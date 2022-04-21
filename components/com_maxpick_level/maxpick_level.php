<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Maxpick_level
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Maxpick_level', JPATH_COMPONENT);
JLoader::register('Maxpick_levelController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Maxpick_level');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
