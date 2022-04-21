<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
class plgOSMembershipEshop extends JPlugin
{

	private $canRun = false;
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		if (file_exists(JPATH_ADMINISTRATOR . '/components/com_eshop/eshop.php'))
		{
			$this->canRun = true;
			require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';
			require_once JPATH_ROOT . '/components/com_eshop/helpers/api.php';
			JFactory::getLanguage()->load('plg_osmembership_eshop', JPATH_ADMINISTRATOR);
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_osmembership/tables');
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_eshop/tables');
		}
	}

	/**
	 * Render settings from
	 * @param PlanOSMembership $row
	 */
	function onEditSubscriptionPlan($row)
	{
		if (!$this->canRun)
		{
			return;
		}
		ob_start();
		$this->_drawSettingForm($row);
		$form = ob_get_clean();
		return array('title' => JText::_('PLG_OSMEMBERSHIP_ESHOP_CUSTOMER_GROUPS_SETTINGS'), 'form' => $form);
	}

	/**
	 * Store setting into database
	 * @param PlanOsMembership $row
	 * @param Boolean $isNew true if create new plan, false if edit
	 */
	function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		if (!$this->canRun)
		{
			return;
		}
		$params = new JRegistry($row->params);
		$params->set('eshop_customer_group_id', (int) $data['eshop_customer_group_id']);
		$row->params = $params->toString();
		$row->store();
	}

	/**
	 * Create customer record if needed
	 * @param SubscriberOsmembership $row
	 */
	function onAfterStoreSubscription($row)
	{
		if ($this->canRun && $row->user_id)
		{
			$db = JFactory::getDbo();
			$rowFields = OSMembershipHelper::getProfileFields($row->plan_id, true, $row->language);
			$data = OSMembershipHelper::getProfileData($row, $row->plan_id, $rowFields);
			//Map fields in Membership Pro with Fields in Eshop
			if (!isset($data['country']) || !$data['country'])
			{
				$country = OSMembershipHelper::getConfigValue('default_country');
			}
			else
			{
				$country = $data['country'];
			}
			$query = $db->getQuery(true);
			$query->select('iso_code_3')
				->from('#__eshop_countries')
				->where('country_name=' . $db->quote($country));
			$db->setQuery($query);
			$data['country_code'] = $db->loadResult();
			$fieldsMapping = array(
				'first_name' => 'firstname', 
				'last_name' => 'lastname', 
				'organization' => 'company', 
				'address' => 'address_1', 
				'address2' => 'address_2', 
				'phone' => 'telephone', 
				'zip' => 'postcode', 
				'fax' => 'fax', 
				'city' => 'city', 
				'email' => 'email');
			foreach ($fieldsMapping as $membershipProField => $eshopField)
			{
				if (isset($data[$membershipProField]))
				{
					$data[$eshopField] = $data[$membershipProField];
				}
			}
			if (isset($data['state']))
			{
				$query->clear();
				$query->select('state_3_code')
					->from('#__osmembership_states AS a')
					->innerJoin('#__osmembership_countries AS b ON a.country_id=b.country_id')
					->where('a.state_name=' . $db->quote($data['state']))
					->where('b.name=' . $db->quote($country));
				$db->setQuery($query);
				$data['zone_code'] = $db->loadResult();
			}
			EshopAPI::addCustomer($row->user_id, $data);
		}
	}

	/**
	 * Run when a membership activated
	 * @param PlanOsMembership $row
	 */
	function onMembershipActive($row)
	{
		if ($this->canRun && $row->user_id)
		{
			$config = OSMembershipHelper::getConfig();
			if($config->create_account_when_membership_active) $this->onAfterStoreSubscription($row);
			$plan = JTable::getInstance('Osmembership', 'Plan');
			$plan->load($row->plan_id);
			$params = new JRegistry($plan->params);
			$customerGroupId = (int) $params->get('eshop_customer_group_id');
			if ($customerGroupId)
			{
				EshopAPI::setCustomerGroup($row->user_id, $customerGroupId);
			}
		}
	}

	/**
	 * Run when a membership expired
	 * @param PlanOsMembership $row
	 */
	function onMembershipExpire($row)
	{
		if ($this->canRun && $row->user_id)
		{
			$plan = JTable::getInstance('Osmembership', 'Plan');
			$plan->load($row->plan_id);
			$params = new JRegistry($plan->params);
			$activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('params')
				->from('#__osmembership_plans')
				->where('id IN  (' . implode(',', $activePlans) . ')')
				->order('price DESC');
			$db->setQuery($query);
			$rowPlans = $db->loadObjectList();
			$customerGroupId = (int) EshopHelper::getConfigValue('customergroup_id');
			if (count($rowPlans))
			{
				foreach ($rowPlans as $rowPlan)
				{
					$planParams = new JRegistry($rowPlan->params);
					$planCustomerGroupId = (int) $planParams->get('eshop_customer_group_id');
					if ($planCustomerGroupId)
					{
						$customerGroupId = $planCustomerGroupId;
						break;
					}
				}
			}
			EshopAPI::setCustomerGroup($row->user_id, $customerGroupId);
		}
	}

	/**
	 * Display form allows users to change setting for this subscription plan 
	 * @param object $row
	 * 
	 */
	function _drawSettingForm($row)
	{
		$params = new JRegistry($row->params);
		$customerGroupId = (int) $params->get('eshop_customer_group_id', 0);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('PLG_OSMEMBERSHIP_ESHOP_SELECT_GROUP'), 'id', 'customergroup_name');
		$options = array_merge($options, EshopAPI::getCustomerGroups());
		?>
<table class="admintable adminform" style="width: 90%;">
	<tr>
		<td width="220" class="key">
					<?php echo  JText::_('PLG_OSMEMBERSHIP_ESHOP_ASSIGN_TO_CUSTOMER_GROUP'); ?>
				</td>
		<td>
				<?php
		echo JHtml::_('select.genericlist', $options, 'eshop_customer_group_id', '', 'id', 'customergroup_name', $customerGroupId);
		?>
				</td>
		<td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_ESHOP_ASSIGN_TO_CUSTOMER_GROUP_EXPLAIN'); ?>
				</td>
	</tr>
</table>
<?php
	}
}