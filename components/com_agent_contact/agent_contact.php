<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_contact
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Agent_contact', JPATH_COMPONENT);
JLoader::register('Agent_contactController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Agent_contact');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
