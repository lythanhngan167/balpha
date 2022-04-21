<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Return_store
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_return_store'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Return_store', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Return_storeHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'return_store.php');

$controller = JControllerLegacy::getInstance('Return_store');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
