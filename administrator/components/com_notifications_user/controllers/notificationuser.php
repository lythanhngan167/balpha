<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notifications_user
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Notificationuser controller class.
 *
 * @since  1.6
 */
class Notifications_userControllerNotificationuser extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'notificationusers';
		parent::__construct();
	}
}
