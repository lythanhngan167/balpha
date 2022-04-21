<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Export_customer
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_export_customer'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Export_customer', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Export_customerHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'export_customer.php');

$controller = JControllerLegacy::getInstance('Export_customer');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
