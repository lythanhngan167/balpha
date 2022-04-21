<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class EshopCustomer
{

	/**
	 *
	 * Function to get customer group id
	 */
	public function getCustomerGroupId()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('customergroup_id')
			->from('#__eshop_customers')
			->where('customer_id = ' . intval(JFactory::getUser()->get('id')));
		$db->setQuery($query);

		if ($db->loadResult())
		{
			$customerGroupId = $db->loadResult();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}

		return $customerGroupId;
	}
	
	/**
	 * 
	 * Function to get Customer Group Name of a specific customer group
	 * @param int $customerGroupId
	 * @param string $langCode
	 * @return string
	 */
	public function getCustomerGroupName($customerGroupId, $langCode)
	{
	    $db    = JFactory::getDbo();
	    $query = $db->getQuery(true);
	    
	    if (!$langCode)
	    {
	        $langCode = JFactory::getLanguage()->getTag();
	    }
	    
	    $query->select('customergroup_name')
	       ->from('#__eshop_customergroupdetails')
	       ->where('customergroup_id = ' . intval($customerGroupId))
	       ->where('language = ' . $db->quote($langCode));
	    $db->setQuery($query);
	    $customerGroupName = $db->loadResult();
	    
	    return $customerGroupName;
    }
}