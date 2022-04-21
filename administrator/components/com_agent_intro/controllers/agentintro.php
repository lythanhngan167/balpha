<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_intro
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Agentintro controller class.
 *
 * @since  1.6
 */
class Agent_introControllerAgentintro extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'agentintros';
		parent::__construct();
	}
}
