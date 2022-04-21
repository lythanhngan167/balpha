<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Registration.
 *
 * @since  1.6
 */
class RegistrationViewExpire extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $items;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		JToolBarHelper::title('Xóa Data hết hạn Landingpage', 'customers.png');
		parent::display($tpl);
	}



}
