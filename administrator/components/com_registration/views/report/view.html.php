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
class RegistrationViewReport extends \Joomla\CMS\MVC\View\HtmlView
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
		$this->state = $this->get('State');
		//$this->items = $this->get('Items');

		$model = $this->getModel();
		switch($_REQUEST['date']){
			case 'today':
			$from_date = date("Y-m-d 00:00:00");
			$to_date = date("Y-m-d 23:59:59");
			break;
			case 'week':
			$from_date = date("Y-m-d 00:00:00", strtotime('monday this week'));
			$to_date = date("Y-m-d 23:59:59");
			break;
			case 'last_week':
			$from_date = date("Y-m-d 00:00:00", strtotime('monday last week'));
			$to_date = date("Y-m-d 23:59:59", strtotime('sunday last week'));
			break;
			case 'last_month':
			$from_date = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
			$to_date = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
			break;
			default:
			$from_date = date("Y-m-d 00:00:00");
			$to_date = date("Y-m-d 23:59:59");
			break;
		}

		// if($_REQUEST['date'] == 'today'){
		// 	echo 'today';
		// 	echo $from_date;
		// 	echo $to_date;
		// }
		// if($_REQUEST['date'] == 'last_week'){
		// 	echo 'last_week';
		// 	echo $from_date;
		// 	echo $to_date;
		// }
		// if($_REQUEST['date'] == 'last_month'){
		// 	echo 'month';
		// 	echo $from_date;
		// 	echo $to_date;
		// }

		$this->landingpages = $model->getGroupLandingpage($from_date,$to_date);
		$this->sources = $model->getGroupSource($from_date,$to_date);
		$this->mediums = $model->getGroupMedium($from_date,$to_date);
		$this->compains = $model->getGroupCompain($from_date,$to_date);
		// echo "<pre>";
		// print_r($landingpage);
		// echo "</pre>";
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		RegistrationHelper::addSubmenu('registrations');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = RegistrationHelper::getActions();
		switch($_REQUEST['date']){
			case 'today':
			$from_date = 'hôm nay';
			break;
			case 'week':
			$from_date = 'tuần này';
			break;
			case 'last_week':
			$from_date = 'tuần trước';
			break;
			case 'month':
			$from_date = 'tháng này';
			break;
			case 'last_month':
			$from_date = 'tháng trước';
			break;
			default:
			$from_date = 'hôm nay';
			break;
		}
		JToolBarHelper::title(Text::_('Thống kê ').$from_date, 'registrations.png');

	}



}
