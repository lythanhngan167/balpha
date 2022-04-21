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

use \Joomla\CMS\Categories\Categories;
/**
 * Content Component Category Tree
 *
 * @since  1.6
 */

class Notifications_userNotificationusersCategories extends Categories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   11.1
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__notifications_user';
		$options['extension'] = 'com_notifications_user.notificationusers';
		parent::__construct($options);
	}
}
