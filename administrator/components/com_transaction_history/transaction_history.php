<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Transaction_history
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_transaction_history'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Transaction_history', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Transaction_historyHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'transaction_history.php');

$controller = BaseController::getInstance('Transaction_history');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
