<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop Component Tools Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelTools extends JModelLegacy
{
	
	/**
	 * 
	 * Migrate subscribers from Membership Pro into Eshop Customers
	 */
	public function migrateFromMembershipPro()
	{
		require_once JPATH_ROOT.'/components/com_osmembership/helper/helper.php';
		require_once JPATH_ROOT.'/components/com_eshop/helpers/helper.php';
		require_once JPATH_ROOT.'/components/com_eshop/helpers/api.php';
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_subscribers')
			->where('is_profile=1')
			->where('user_id > 0');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$fieldsMapping = array(
			'first_name'	=> 'firstname',
			'last_name' 	=> 'lastname',
			'organization' 	=> 'company',
			'address' 		=> 'address_1',
			'address2' 		=> 'address_2',
			'phone' 		=> 'telephone',
			'zip' 			=> 'postcode',
			'fax'			=> 'fax',
			'city'			=> 'city',
			'email'			=> 'email'
		);		
		$defaultCountry = OSMembershipHelper::getConfigValue('default_country');
		$defaultCustomerGroupId = (int)EshopHelper::getConfigValue('customergroup_id');
		$countryCodes = array();
		if (count($rows))
		{
			foreach ($rows as $row)
			{
				if (EshopAPI::customerExist($row->user_id))
				{
					continue;
				}
				$data = array();
				$country = $row->country ? $row->country : $defaultCountry;
				if (!isset($countryCodes[$country]))
				{
					$query->clear();
					$query->select('iso_code_3')
						->from('#__eshop_countries')
						->where('country_name='.$db->quote($country));
					$db->setQuery($query);
					$countryCodes[$country] = $db->loadResult();
				}				
				$data['country_code'] = $countryCodes[$country];
				foreach ($fieldsMapping as $membershipProField => $eshopField)
				{
					if ($row->{$membershipProField})
					{
						$data[$eshopField] = $row->{$membershipProField};
					}
				}
				if ($row->state)
				{
					$query->clear();
					$query->select('state_3_code')
						->from('#__osmembership_states AS a')
						->innerJoin('#__osmembership_countries AS b ON a.country_id=b.country_id')
						->where('a.state_name='.$db->quote($row->state))
						->where('b.name='.$db->quote($country));
					$db->setQuery($query);
					$data['zone_code'] = $db->loadResult();
				}
				$customerGroupId = $defaultCustomerGroupId;
				//Customer groups based on active plans
				$activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id);
				if (count($activePlans) > 1)
				{
					$query->clear();
					$query->select('params')
						->from('#__osmembership_plans')
						->where('id IN  (' . implode(',', $activePlans) . ')')
						->order('price DESC');
					$db->setQuery($query);
					$rowPlans = $db->loadObjectList();
					if (count($rowPlans))
					{
						foreach ($rowPlans as $rowPlan)
						{
							$planParams = new JRegistry($rowPlan->params);
							$planCustomerGroupId = (int)$planParams->get('eshop_customer_group_id');
							if ($planCustomerGroupId)
							{
								$customerGroupId = $planCustomerGroupId;
								break;
							}
						}
					}	
				}				
				$data['customergroup_id'] = $customerGroupId;
				EshopAPI::addCustomer($row->user_id, $data);
			}
		}
	}
	
	/**
	 * 
	 * Migrate users from Joomla into Eshop Customers
	 */
	public function migrateFromJoomla()
	{
		require_once JPATH_ROOT.'/components/com_eshop/helpers/helper.php';
		require_once JPATH_ROOT.'/components/com_eshop/helpers/api.php';
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);	
		$query->select('*')
			->from('#__users');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$profileEnabled = JPluginHelper::isEnabled('user', 'profile');
		foreach ($rows as $row)
		{
			if (EshopAPI::customerExist($row->id))
			{
				continue;
			}
			$data = array();
			$name = $row->name;
			$pos = strpos($name, ' ');
			if ($pos !== false)
			{
				$data['firstname'] = substr($name, 0, $pos);
				$data['lastname'] =  substr($name, $pos + 1);
			}
			else
			{
				$data['firstname'] = $name;
				$data['lastname'] = '';
			}
			$data['email'] = $row->email;
			if ($profileEnabled)
			{								
				$profile = JUserHelper::getProfile($row->id);				
				$data['address_1'] = $profile->profile['address1'];
				$data['address_2'] = $profile->profile['address2'];
				$data['city'] = $profile->profile['city'];
				$country = $profile->profile['country'];												
				if ($country)
				{
					$query = $db->getQuery(true);
					$query->select('iso_code_3')
						->from('#__eshop_countries')
						->where('country_name='.$db->quote($country));
					$db->setQuery($query);
					$data['country_code'] = $db->loadResult();
					
					if ($data['country_code'] != '')
					{
						$region = $profile->profile['region'];
						if ($region)
						{
							$query->clear();
							$query->select('z.zone_code')
							->from('#__eshop_zones AS z')
							->innerJoin('#__eshop_countries AS c ON (z.country_id = c.id)')
							->where('c.iso_code_3 = ' . $db->quote($data['country_code']))
							->where('z.zone_name = ' . $db->quote($region));
							$db->setQuery($query);
							$data['zone_code'] = $db->loadResult();
						}
					}
				}
				
				$data['postcode'] = $profile->profile['postal_code'];
				$data['telephone'] = $profile->profile['phone'];
			}
			EshopAPI::addCustomer($row->id, $data);
		}
	}
	
	/**
	 * 
	 * Function to clean data
	 */
	public function cleanData()
	{
		$db = JFactory::getDbo();
		$cleanSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/clean.eshop.sql';
		$query = JFile::read($cleanSql);
		$queries = $db->splitSql($query);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	
	/**
	 *
	 * Function to add sample data
	 */
	public function addSampleData()
	{
		$db = JFactory::getDbo();
		// Clean data first
		$cleanSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/clean.eshop.sql';
		$query = JFile::read($cleanSql);
		$queries = $db->splitSql($query);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		// Then add sample data
		$cleanSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/sample.eshop.sql';
		$query = JFile::read($cleanSql);
		$queries = $db->splitSql($query);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	
	/**
	 * 
	 * Function to synchronize data
	 */
	public function synchronizeData()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('element')
			->from('#__extensions')
			->where('type = "language"')
			->where('client_id = 0');
		$db->setQuery($query);
		$langCodes = $db->loadColumn();
		if (count($langCodes))
		{
			foreach ($langCodes as $currentLangCode)
			{
				foreach ($langCodes as $langCode)
				{
					if ($langCode != $currentLangCode)
					{
						$sql = 'INSERT INTO #__eshop_attributedetails (attribute_id, attribute_name, language)' .
								' SELECT attribute_id, attribute_name, "' . $langCode . '"' .
								' FROM #__eshop_attributedetails WHERE (language = "' . $currentLangCode . '") AND attribute_id NOT IN (select attribute_id FROM #__eshop_attributedetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_attributegroupdetails (attributegroup_id, attributegroup_name, language)' .
								' SELECT attributegroup_id, attributegroup_name, "' . $langCode . '"' .
								' FROM #__eshop_attributegroupdetails WHERE (language = "' . $currentLangCode . '") AND attributegroup_id NOT IN (select attributegroup_id FROM #__eshop_attributegroupdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_categorydetails (category_id, category_name, category_alias, category_desc, meta_key, meta_desc, language)' .
								' SELECT category_id, category_name, category_alias, category_desc, meta_key, meta_desc, "' . $langCode . '"' .
								' FROM #__eshop_categorydetails WHERE (language = "' . $currentLangCode . '") AND category_id NOT IN (select category_id FROM #__eshop_categorydetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_customergroupdetails (customergroup_id, customergroup_name, language)' .
								' SELECT customergroup_id, customergroup_name, "' . $langCode . '"' .
								' FROM #__eshop_customergroupdetails WHERE (language = "' . $currentLangCode . '") AND customergroup_id NOT IN (select customergroup_id FROM #__eshop_customergroupdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_downloaddetails (download_id, download_name, language)' .
								' SELECT download_id, download_name, "' . $langCode . '"' .
								' FROM #__eshop_downloaddetails WHERE (language = "' . $currentLangCode . '") AND download_id NOT IN  (select download_id FROM #__eshop_downloaddetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_fielddetails (field_id, title, description, place_holder, language, default_values, `values`, validation_error_message)' .
								' SELECT field_id, title, description, place_holder, "' . $langCode . '", default_values, `values`, validation_error_message' .
								' FROM #__eshop_fielddetails WHERE (language = "' . $currentLangCode . '") AND field_id NOT IN (select field_id FROM #__eshop_fielddetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_labeldetails (label_id, label_name, language)' .
								' SELECT label_id, label_name, "' . $langCode . '"' .
								' FROM #__eshop_labeldetails WHERE (language = "' . $currentLangCode . '") AND label_id NOT IN  (select label_id FROM #__eshop_labeldetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_lengthdetails (length_id, length_name, length_unit, language)' .
								' SELECT length_id, length_name, length_unit, "' . $langCode . '"' .
								' FROM #__eshop_lengthdetails WHERE (language = "' . $currentLangCode . '") AND length_id NOT IN (select length_id FROM #__eshop_lengthdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_manufacturerdetails (manufacturer_id, manufacturer_name, manufacturer_alias, manufacturer_desc, language)' .
								' SELECT manufacturer_id, manufacturer_name, manufacturer_alias, manufacturer_desc, "' . $langCode . '"' .
								' FROM #__eshop_manufacturerdetails WHERE (language = "' . $currentLangCode . '") AND manufacturer_id NOT IN (select manufacturer_id FROM #__eshop_manufacturerdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_messagedetails (message_id, message_value, language)' .
								' SELECT message_id, message_value, "' . $langCode . '"' .
								' FROM #__eshop_messagedetails WHERE (language = "' . $currentLangCode . '") AND message_id NOT IN (select message_id FROM #__eshop_messagedetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_optiondetails (option_id, option_name, option_desc, language)' .
								' SELECT option_id, option_name, option_desc, "' . $langCode . '"' .
								' FROM #__eshop_optiondetails WHERE (language = "' . $currentLangCode . '") AND option_id NOT IN (select option_id FROM #__eshop_optiondetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_optionvaluedetails (optionvalue_id, option_id, value, language)' .
								' SELECT optionvalue_id, option_id, value, "' . $langCode . '"' .
								' FROM #__eshop_optionvaluedetails WHERE (language = "' . $currentLangCode . '") AND optionvalue_id NOT IN (select optionvalue_id FROM #__eshop_optionvaluedetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_orderstatusdetails (orderstatus_id, orderstatus_name, language)' .
								' SELECT orderstatus_id, orderstatus_name, "' . $langCode . '"' .
								' FROM #__eshop_orderstatusdetails WHERE (language = "' . $currentLangCode . '") AND orderstatus_id NOT IN (select orderstatus_id FROM #__eshop_orderstatusdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_productattributedetails (productattribute_id, product_id, value, language)' .
								' SELECT productattribute_id, product_id, value, "' . $langCode . '"' .
								' FROM #__eshop_productattributedetails WHERE (language = "' . $currentLangCode . '") AND productattribute_id NOT IN (select productattribute_id FROM #__eshop_productattributedetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_productdetails (product_id, product_name, product_alias, product_desc, product_short_desc, meta_key, meta_desc, language)' .
								' SELECT product_id, product_name, product_alias, product_desc, product_short_desc, meta_key, meta_desc, "' . $langCode . '"' .
								' FROM #__eshop_productdetails WHERE (language = "' . $currentLangCode . '") AND product_id NOT IN (select product_id FROM #__eshop_productdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_stockstatusdetails (stockstatus_id, stockstatus_name, language)' .
								' SELECT stockstatus_id, stockstatus_name, "' . $langCode . '"' .
								' FROM #__eshop_stockstatusdetails WHERE (language = "' . $currentLangCode . '") AND stockstatus_id NOT IN (select stockstatus_id FROM #__eshop_stockstatusdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
				
						$sql = 'INSERT INTO #__eshop_weightdetails (weight_id, weight_name, weight_unit, language)' .
								' SELECT weight_id, weight_name, weight_unit, "' . $langCode . '"' .
								' FROM #__eshop_weightdetails WHERE (language = "' . $currentLangCode . '") AND weight_id NOT IN (select weight_id FROM #__eshop_weightdetails WHERE language = "' . $langCode . '")';
						$db->setQuery($sql);
						$db->execute();
					}
				}	
			}
		}
	}
	
	/**
	 * 
	 * Function to migrate data from Virtuemart to EShop
	 */
	public function migrateVirtuemart()
	{
		jimport('joomla.filesystem.folder');
		if (!JFolder::exists(JPATH_ROOT.'/components/com_virtuemart'))
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_MIGRATE_VIRTUEMART_NOT_EXISTED'), 'error');
			$mainframe->redirect('index.php?option=com_eshop&view=dashboard');
		}
		else 
		{
			jimport('joomla.filesystem.file');
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__languages')
				->where('published = 1');
			$db->setQuery($query);
			$languages = $db->loadObjectList();
	
			// VM categories
			$query->clear()
				->select('*')
				->from('#__virtuemart_categories');
			$db->setQuery($query);
			$categories = $db->loadObjectList('virtuemart_category_id');
	
			// VM parent categories
			$query->clear()
				->select('id, category_parent_id')
				->from('#__virtuemart_category_categories');
			$db->setQuery($query);
			$parentCategories = $db->loadAssocList('id', 'category_parent_id');
			
			// VM image categories
			$query->clear()
				->select('a.virtuemart_category_id')
				->from('#__virtuemart_category_medias AS a')
				->select('b.file_url')
				->leftJoin('#__virtuemart_medias AS b ON a.virtuemart_media_id = b.virtuemart_media_id');
			$db->setQuery($query);
			$categoryImages = $db->loadAssocList('virtuemart_category_id', 'file_url');
			
			// Migrate categories
			$mappingCategories = array();
			$categoryImagesPath = JPATH_ROOT . '/media/com_eshop/categories/';
			foreach ($categories AS $category)
			{
				$row = new EShopTable('#__eshop_categories', 'id', $db);
				// Upload image category
				if (isset($categoryImages[$category->virtuemart_category_id]) && $categoryImages[$category->virtuemart_category_id] != '')
				{
					$categoryImage = pathinfo($categoryImages[$category->virtuemart_category_id]);
					$imageFileName = JFile::makeSafe($categoryImage['basename']);
					if (JFile::exists($categoryImagesPath . $categoryImage['basename']))
						$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($categoryImage['basename']);
					if (JFile::exists(JPATH_ROOT.'/'.$categoryImages[$category->virtuemart_category_id]))
					{
						$rel = JFile::copy(JPATH_ROOT.'/'.$categoryImages[$category->virtuemart_category_id], $categoryImagesPath . $imageFileName);
						if($rel)
							$row->category_image = $imageFileName;
					}
				}
				// Assign data
				$row->category_parent_id= 0;
				$row->products_per_page = 15;
				$row->products_per_row  = $category->products_per_row > 0 ? $category->products_per_row : 3;
				$row->published         = $category->published;
				$row->ordering          = $category->ordering;
				$row->hits              = $category->hits;
				$row->created_date      = $category->created_on;
				$row->created_by        = $category->created_by;
				$row->modified_date     = $category->modified_on;
				$row->modified_by       = $category->modified_by;
				$row->checked_out       = $category->locked_by;
				$row->checked_out_time  = $category->locked_on;
				if($row->store())
					$mappingCategories[$category->virtuemart_category_id] = $row->id;
			}
			
			// Update parent catogory
			foreach ($mappingCategories AS $virtuemart_category_id => $eshopCatId)
			{
				if (!$parentCategories[$virtuemart_category_id]) continue;
				$row = new EShopTable('#__eshop_categories', 'id', $db);
				$row->load($eshopCatId);
				$row->category_parent_id = $mappingCategories[$parentCategories[$virtuemart_category_id]];
				$row->store();
			}
			
			// Eshop category details
			foreach ($languages AS $language)
			{
				$search = 'virtuemart_categories_'.strtolower(str_replace('-','_', $language->lang_code));
				$search = $db->quote('%' . trim($search) . '%');
				$db->setQuery("SHOW TABLES LIKE $search");
				$categoryDetailsTables = $db->loadResult();
				if ($categoryDetailsTables != '')
				{
					$query->clear()
						->select('*')
						->from($categoryDetailsTables);
					$db->setQuery($query);
					$categoriesData = $db->loadObjectList('virtuemart_category_id');
					foreach ($categoriesData AS $categoryData)
					{
						if ($mappingCategories[$categoryData->virtuemart_category_id])
						{
							$row = new EShopTable('#__eshop_categorydetails', 'id', $db);
							$row->category_id           = $mappingCategories[$categoryData->virtuemart_category_id];
							$row->category_name         = $categoryData->category_name;
							if (empty($categoryData->slug))
								$row->category_alias    = JApplicationHelper::stringUrlSafe($row->category_name);
							else
								$row->category_alias    = $categoryData->slug;
							$row->category_desc         = $categoryData->category_description;
							$row->meta_key              = $categoryData->metakey;
							$row->meta_desc             = $categoryData->metadesc;
							$row->language              = trim($language->lang_code);
							$row->store();
						}
					}
				}
			}
			
			// VM manufacturers
			$query->clear()
				->select('*')
				->from('#__virtuemart_manufacturers');
			$db->setQuery($query);
			$manufactures = $db->loadObjectList('virtuemart_manufacturer_id');
			
			// VM image manufacturers
			$query->clear()
				->select('a.virtuemart_manufacturer_id')
				->from('#__virtuemart_manufacturer_medias AS a')
				->select('b.file_url')
				->leftJoin('#__virtuemart_medias AS b ON a.virtuemart_media_id=b.virtuemart_media_id');
			$db->setQuery($query);
			$manufacturerImages = $db->loadAssocList('virtuemart_manufacturer_id','file_url');
			
			// Migrate manufacturers
			$mappingManufactures = array();
			$manufacturerImagesPath = JPATH_ROOT . '/media/com_eshop/manufacturers/';
			foreach ($manufactures AS $manufacture)
			{
				$row = new EShopTable('#__eshop_manufacturers', 'id', $db);
				if (isset($manufacturerImages[$manufacture->virtuemart_manufacturer_id]) && $manufacturerImages[$manufacture->virtuemart_manufacturer_id] != '')
				{
					$manufactureImage = pathinfo($manufacturerImages[$manufacture->virtuemart_manufacturer_id]);
					$imageFileName = JFile::makeSafe($manufactureImage['basename']);
					if (JFile::exists($manufacturerImagesPath . $manufactureImage['basename']))
						$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($manufactureImage['basename']);
					if (JFile::exists(JPATH_ROOT.'/'.$manufacturerImages[$manufacture->virtuemart_manufacturer_id]))
					{
						$rel = JFile::copy(JPATH_ROOT.'/'.$manufacturerImages[$manufacture->virtuemart_manufacturer_id], $manufacturerImagesPath . $imageFileName);
						if($rel)
							$row->manufacturer_image = $imageFileName;
					}
				}
			
				// Assign data
				$row->published                     = $manufacture->published;
				$row->hits                          = $manufacture->hits;
				$row->created_date                  = $manufacture->created_on;
				$row->created_by                    = $manufacture->created_by;
				$row->modified_date                 = $manufacture->modified_on;
				$row->modified_by                   = $manufacture->modified_by;
				$row->checked_out                   = $manufacture->locked_by;
				$row->checked_out_time              = $manufacture->locked_on;
				if($row->store())
					$mappingManufactures[$manufacture->virtuemart_manufacturer_id] = $row->id;
			}
			
			// Manufactuer details
			foreach ($languages AS $language)
			{
				$search = 'virtuemart_manufacturers_'.strtolower(str_replace('-','_', $language->lang_code));
				$search = $db->quote('%' . trim($search) . '%');
				$db->setQuery("SHOW TABLES LIKE $search");
				$manufacturerDetailsTables = $db->loadResult();
				if ($manufacturerDetailsTables != '')
				{
					$query->clear()
						->select('*')->from($manufacturerDetailsTables);
					$db->setQuery($query);
					$manufacturersData = $db->loadObjectList('virtuemart_manufacturer_id');
					foreach ($manufacturersData AS $manufacturerData)
					{
						if ($mappingManufactures[$manufacturerData->virtuemart_manufacturer_id])
						{
							// Update email and url
							$row = new EShopTable('#__eshop_manufacturers', 'id', $db);
							$row->load($mappingManufactures[$manufacturerData->virtuemart_manufacturer_id]);
							$row->manufacturer_email = $manufacturerData->mf_email;
							$row->manufacturer_url   = $manufacturerData->mf_url;
							$row->store();
			
							// Manufacturer details
							$row = new EShopTable('#__eshop_manufacturerdetails', 'id', $db);
							$row->manufacturer_id           = $mappingManufactures[$manufacturerData->virtuemart_manufacturer_id];
							$row->manufacturer_name         = $manufacturerData->mf_name;
							$row->language                  = trim($language->lang_code);
							if (empty($manufacturerData->slug))
								$row->manufacturer_alias    = JApplicationHelper::stringUrlSafe($row->manufacturer_name);
							else
								$row->manufacturer_alias    = $manufacturerData->slug;
							$row->manufacturer_desc         = $manufacturerData->mf_desc;
							$row->store();
						}
					}
				}
			}
			
			// VM products
			$query->clear()
				->select('*')
				->from('#__virtuemart_products');
			$db->setQuery($query);
			$products = $db->loadObjectList('virtuemart_product_id');
			
			// VM products category
			$query->clear()
				->select('*')
				->from('#__virtuemart_product_categories');
			$db->setQuery($query);
			$productsCategories = $db->loadObjectList();
			
			// VM product manufacturer
			$query->clear()
				->select('DISTINCT virtuemart_product_id, virtuemart_manufacturer_id')
				->from('#__virtuemart_product_manufacturers');
			$db->setQuery($query);
			$productManufacturer = $db->loadAssocList('virtuemart_product_id', 'virtuemart_manufacturer_id');
			
			// VM product images
			$query->clear()
				->select('a.virtuemart_product_id')
				->from('#__virtuemart_product_medias AS a')
				->select('b.*')
				->innerJoin('#__virtuemart_medias AS b ON a.virtuemart_media_id=b.virtuemart_media_id');
			$db->setQuery($query);
			$productImages = $db->loadObjectList();
			
			// upload image
			$mappingProductImages = array();
			$imagesProductPath = JPATH_ROOT . '/media/com_eshop/products/';
			foreach ($productImages AS $image)
			{
				if (!isset($mappingProductImages[$image->virtuemart_product_id]))
					$mappingProductImages[$image->virtuemart_product_id] = array();
				$productImage = pathinfo($image->file_url);
				$imageFileName = JFile::makeSafe($productImage['basename']);
				if (JFile::exists($imagesProductPath . $imageFileName))
					$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($productImage['basename']);
				if (JFile::exists(JPATH_ROOT.'/'.$image->file_url))
				{
					$rel = JFile::copy(JPATH_ROOT.'/'.$image->file_url, $imagesProductPath . $imageFileName);
					if($rel)
						$image->image = $imageFileName;
				}
				$mappingProductImages[$image->virtuemart_product_id][] = $image;
			}
			
			// VM products price
			$query->clear()
				->select('virtuemart_product_id, product_price')
				->from('#__virtuemart_product_prices');
			$db->setQuery($query);
			$productsPrices = $db->loadAssocList('virtuemart_product_id','product_price');
			
			// eshop product, image
			$imagePath = JPATH_ROOT . '/media/com_eshop/products/';
			$mappingProducts = array();
			foreach ($products AS $product)
			{
				// save product and main image
				$row = new EShopTable('#__eshop_products', 'id', $db);
				if (isset($productsPrices[$product->virtuemart_product_id]))
				{
					$product_price = $productsPrices[$product->virtuemart_product_id];
					$product_call_for_price = 0;
				}
				else
				{
					$product_call_for_price = 1;
					$product_price = 0;
				}
				$product_minimum_quantity = 0;
				$product_maximum_quantity = 0;
				$product_params = array();
				if ($product->product_params != '')
				{
					$params = explode('|', $product->product_params);
					foreach ($params AS $param)
					{
						if ($param != '')
						{
							list($index,$value) = explode('=', $param);
							$product_params[$index] = substr($value, 1,strlen($value)-2);
						}
					}
				}
				if (isset($product_params['min_order_level'])) $product_minimum_quantity = $product_params['min_order_level'];
				if (isset($product_params['max_order_level'])) $product_maximum_quantity = $product_params['max_order_level'];
				if(isset($mappingManufactures[$productManufacturer[$product->virtuemart_product_id]]))
					$row->manufacturer_id           = $mappingManufactures[$productManufacturer[$product->virtuemart_product_id]];
				$row->product_sku               = $product->product_sku;
				$row->product_weight            = $product->product_weight;
				$row->product_weight_id         = 1;
				$row->product_length            = $product->product_length;
				$row->product_width             = $product->product_width;
				$row->product_height            = $product->product_height;
				$row->product_length_id         = 1;
				$row->product_price             = $product_price;
				$row->product_call_for_price    = $product_call_for_price;
				$row->product_taxclass_id       = 0;
				$row->product_quantity          = $product->product_in_stock;
				$row->product_minimum_quantity  = $product_minimum_quantity;
				$row->product_maximum_quantity  = $product_maximum_quantity;
				
				if (count($mappingProductImages[$product->virtuemart_product_id]))
					$row->product_image         = $mappingProductImages[$product->virtuemart_product_id][0]->image;
				$row->product_available_date    = $product->product_available_date;
				$row->product_featured          = $product->product_special;
				$row->published                 = $product->published;
				$row->ordering                  = $product->pordering;
				$row->hits                      = $product->hits;
				$row->created_date              = $product->created_on;
				$row->created_by                = $product->created_by;
				$row->modified_date             = $product->modified_on;
				$row->modified_by               = $product->modified_by;
				$row->checked_out               = $product->locked_by;
				$row->checked_out_time          = $product->locked_on;
				if($row->store())
					$mappingProducts[$product->virtuemart_product_id] = $row->id;
			
				if ($row->id)
				{
					unset($mappingProductImages[$product->virtuemart_product_id][0]);
					// save extra image
					foreach ($mappingProductImages[$product->virtuemart_product_id] AS $image)
					{
						$row = new EShopTable('#__eshop_productimages', 'id', $db);
						$row->id = 0;
						$row->product_id = $mappingProducts[$product->virtuemart_product_id];
						$row->image = $image->image;
						$row->published = 1;
						$row->ordering = 1;
						$row->created_date = $image->created_on;
						$row->created_by = $image->created_by;
						$row->modified_date = $image->modified_on;
						$row->modified_by = $image->modified_by;
						$row->checked_out = $image->locked_by;
						$row->checked_out_time = $image->locked_on;
						$row->store();
					}
				}
			}
			
			// Product categories relation
			$main_category = 1;
			foreach ($productsCategories AS $products_category)
			{
				$product_id     = $mappingProducts[$products_category->virtuemart_product_id];
				$category_id    = $mappingCategories[$products_category->virtuemart_category_id];
				if ($product_id && $category_id)
				{
					$query->clear()
						->insert('#__eshop_productcategories')
						->values("'', $product_id, $category_id, $main_category");
					$db->setQuery($query);
					$db->execute();
					$main_category = 0;
				}
			}
			
			// Product details
			foreach ($languages AS $language)
			{
				$search = 'virtuemart_products_'.strtolower(str_replace('-','_', $language->lang_code));
				$search = $db->quote('%' . trim($search) . '%');
				$db->setQuery("SHOW TABLES LIKE $search");
				$productTables = $db->loadResult();
				if ($productTables != '')
				{
					$query->clear()
						->select('*')
						->from($productTables);
					$db->setQuery($query);
					$productsData = $db->loadObjectList('virtuemart_product_id');
					foreach ($productsData AS $products_data)
					{
						if ($mappingProducts[$products_data->virtuemart_product_id])
						{
							// Save database
							$row = new EShopTable('#__eshop_productdetails', 'id', $db);
							$row->product_id            = $mappingProducts[$products_data->virtuemart_product_id];
							$row->product_name          = $products_data->product_name;
							if (empty($products_data->slug))
								$row->product_alias     = JApplicationHelper::stringUrlSafe($row->product_name);
							else
								$row->product_alias     = $products_data->slug;
							$row->product_desc          = $products_data->product_desc;
							$row->product_short_desc    = $products_data->product_s_desc;
							$row->meta_key              = $products_data->metakey;
							$row->meta_desc             = $products_data->metadesc;
							$row->language              = trim($language->lang_code);
							$row->store();
						}
					}
				}
			}
		}
	}
	
	/**
	 * 
	 * Function to reset hits data
	 */
	public function resetHits()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__eshop_products')
			->set('hits = 0');
		$db->setQuery($query);
		$db->execute();
		$query->update('#__eshop_categories')
			->set('hits = 0');
		$db->setQuery($query);
		$db->execute();
		$query->update('#__eshop_manufacturers')
			->set('hits = 0');
		$db->setQuery($query);
		$db->execute();
	}
	
	/**
	 * 
	 * Function to purge urls
	 */
	public function purgeUrls()
	{
		$db = JFactory::getDbo();
		$db->truncateTable('#__eshop_urls');
	}
}