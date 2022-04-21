<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Import_data
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Import_data', JPATH_COMPONENT);
JLoader::register('Import_dataController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Import_data');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
