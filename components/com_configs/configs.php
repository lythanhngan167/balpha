<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Configs
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Configs', JPATH_COMPONENT);
JLoader::register('ConfigsController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Configs');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
