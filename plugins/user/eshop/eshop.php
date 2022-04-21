<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
class PlgUserEshop extends JPlugin
{

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * This method creates a contact for the saved user
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		// If the user wasn't stored we don't resync
		if (!$success)
		{
			return false;
		}
		
		// If the user isn't new we don't sync
		if (!$isnew)
		{
			return false;
		}
		
		// If from com_eshop, then don't need to sync
		if (JRequest::getVar('option') == 'com_eshop')
		{
			return false;
		}
		
		// Ensure the user id is really an int
		$userId = (int) $user['id'];
		
		// If the user id appears invalid then bail out just in case
		if (empty($userId))
		{
			return false;
		}
		
		if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_eshop/eshop.php'))
		{
			return true;
		}
		require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';
		require_once JPATH_ROOT . '/components/com_eshop/helpers/api.php';
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_eshop/tables');
		$db = JFactory::getDbo();
		$data = array();
		$name = $user['name'];
		//Get first name, last name from username
		$pos = strpos($name, ' ');
		if ($pos !== false)
		{
			$data['firstname'] = substr($name, 0, $pos);
			$data['lastname'] = substr($name, $pos + 1);
		}
		else
		{
			$data['firstname'] = $name;
			$data['lastname'] = '';
		}
		$data['email'] = $user['email'];
		if (JPluginHelper::isEnabled('user', 'profile'))
		{
			$profile = JUserHelper::getProfile($userId);
			$data['address_1'] = $profile->profile['address1'];
			$data['address_2'] = $profile->profile['address2'];
			$data['city'] = $profile->profile['city'];
			$country = $profile->profile['country'];
			if ($country)
			{
				$query = $db->getQuery(true);
				$query->select('iso_code_3')
					->from('#__eshop_countries')
					->where('country_name = ' . $db->quote($country));
				$db->setQuery($query);
				$countryCode = $db->loadResult();
				$data['country_code'] = $countryCode;
				if ($countryCode != '')
				{
					$region = $profile->profile['region'];
					if ($region)
					{
						$query->clear();
						$query->select('z.zone_code')
							->from('#__eshop_zones AS z')
							->innerJoin('#__eshop_countries AS c ON (z.country_id = c.id)')
							->where('c.iso_code_3 = ' . $db->quote($countryCode))
							->where('z.zone_name = ' . $db->quote($region));
						$db->setQuery($query);
						$data['zone_code'] = $db->loadResult();
					}	
				}
			}
			$data['postcode'] = $profile->profile['postal_code'];
			$data['telephone'] = $profile->profile['phone'];
		}
		EshopAPI::addCustomer($userId, $data);
		return true;
	}
}
