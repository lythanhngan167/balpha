<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Customer.
 *
 * @since  1.6
 */
class CustomerViewImportCustomers extends JViewLegacy
{
	protected $items;

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
		$this->items = $this->get('ImportCustomers');
		JToolBarHelper::title('Import Khách hàng thủ công từ File Excel', 'customers.png');
		parent::display($tpl);
	}






}
