<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Referral
 * @author     Truyền Đặng Minh <minhtruyen.ut@gmail.com>
 * @copyright  2021 Truyền Đặng Minh
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Referral', JPATH_COMPONENT);
JLoader::register('ReferralController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Referral');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
