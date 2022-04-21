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
class EShopModelGeozones extends EShopModelList {
	function __construct($config) {		
		$config['search_fields'] =  array('a.geozone_name');
		
		parent::__construct($config);
	}
	
	function _buildQuery() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')
			  ->from('#__eshop_geozones AS a')
		;
		return $query;	
	}
}