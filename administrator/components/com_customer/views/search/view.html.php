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
class CustomerViewSearch extends JViewLegacy
{


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
		JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_customer/models', 'CustomerModel');
		$model = JModelLegacy::getInstance('Customers', 'CustomerModel', array('ignore_request' => true));

		$user = JFactory::getUser();
		$groups = \JAccess::getGroupsByUser($user->id, false);
		$phone_biznet_id = trim($_REQUEST['keyword']);
		if($phone_biznet_id != ''){
			if($groups[0] == 15){
				$this->groupBDM = 15;
				$this->customerActive = $model->getCustomerLandingpageByPhone($phone_biznet_id);
			}else{
				$this->customerActive = $model->getCustomerLandingpageByPhone($phone_biznet_id);
			}
		}
		$this->db            = JFactory::getDbo();

		JToolBarHelper::title('Tìm Khách hàng', 'customers.png');
		parent::display($tpl);
	}


}
