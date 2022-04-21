<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Crm_config
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_crm_config'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Crm_config', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Crm_configHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'crm_config.php');

$controller = JControllerLegacy::getInstance('Crm_config');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
