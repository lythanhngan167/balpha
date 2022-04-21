<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2018 tung hoang
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Notification controller class.
 *
 * @since  1.6
 */
class NotificationControllerNotification extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'notifications';
		parent::__construct();
	}
}
