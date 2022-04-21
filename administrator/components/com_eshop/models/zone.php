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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelZone extends EShopModel
{

	function __construct($config)
	{
		$config['table_name'] = '#__eshop_zones';
		parent::__construct($config);
	}
	
	/**
	 * Method to remove zones
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->delete('#__eshop_zones')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(zone_id) FROM #__eshop_geozonezones)');
			$db->setQuery($query);
			if (!$db->execute())
				//Removed error
				return 0;
			$numItemsDeleted = $db->getAffectedRows();
			if ($numItemsDeleted < count($cid))
			{
				//Removed warning
				return 2;
			}
		}
		//Removed success
		return 1;
	}
}