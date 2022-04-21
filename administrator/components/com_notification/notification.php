<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2018 tung hoang
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_notification'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Notification', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('NotificationHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'notification.php');

$controller = JControllerLegacy::getInstance('Notification');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
