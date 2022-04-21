<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Return_store
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Return_store', JPATH_COMPONENT);
JLoader::register('Return_storeController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Return_store');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
