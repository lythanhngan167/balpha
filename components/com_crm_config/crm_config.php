<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Crm_config
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Crm_config', JPATH_COMPONENT);
JLoader::register('Crm_configController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Crm_config');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
