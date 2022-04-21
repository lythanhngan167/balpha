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

jimport('joomla.application.component.controllerform');

/**
 * Crmconfig controller class.
 *
 * @since  1.6
 */
class Crm_configControllerCrmconfig extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'crmconfigs';
		parent::__construct();
	}
}
