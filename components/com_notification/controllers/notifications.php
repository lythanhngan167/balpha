<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Notifications list controller class.
 *
 * @since  1.6
 */
class NotificationControllerNotifications extends NotificationController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Notifications', $prefix = 'NotificationModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
