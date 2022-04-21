<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notifications_user
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Notifications_user', JPATH_COMPONENT);
JLoader::register('Notifications_userController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Notifications_user');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
