<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Import_data
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_import_data'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Import_data', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Import_dataHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'import_data.php');

$controller = JControllerLegacy::getInstance('Import_data');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
