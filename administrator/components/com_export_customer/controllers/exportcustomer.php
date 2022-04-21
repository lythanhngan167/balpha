<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Export_customer
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Exportcustomer controller class.
 *
 * @since  1.6
 */
class Export_customerControllerExportcustomer extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'exportcustomers';
		parent::__construct();
	}
}
