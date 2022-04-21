<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Session\session;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Registrations list controller class.
 *
 * @since  1.6
 */
class RegistrationControllerReport extends \Joomla\CMS\MVC\Controller\AdminController
{


	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'reports', $prefix = 'RegistrationModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

}
