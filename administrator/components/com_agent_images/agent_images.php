<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_images
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_agent_images'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Agent_images', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Agent_imagesHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'agent_images.php');

$controller = BaseController::getInstance('Agent_images');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
