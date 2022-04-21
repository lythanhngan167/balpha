<?php
/**
 * @version		1.3.5
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
class EShopModelNotify extends EShopModelList
{

	function __construct($config)
	{
	    $config['translatable'] = false;
	    $config['search_fields'] = array('a.notify_email','b.product_name');
		parent::__construct($config);
	}
	
	/**
	 * Basic build Query function.
	 * The child class must override it if it is necessary
	 *
	 * @return string
	 */
	public function _buildQuery()
	{
	    $query = parent::_buildQuery();
	    $query->select('b.product_name')
              ->innerJoin('#__eshop_productdetails AS b ON a.product_id = b.product_id')
		      ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
	    return $query;
	}
	
	/**
	 * Get total entities
	 *
	 * @return int
	 */
	public function getTotal()
	{
	    // Lets load the content if it doesn't already exist
	    if (empty($this->_total))
	    {
	        $db = $this->getDbo();
	        $query = $this->_buildQuery();
	        $query->clear('select')->select('COUNT(a.id)');
	        $db->setQuery($query);
	        $this->_total = $db->loadResult();
	    }
	    return $this->_total;
	}
	
	/**
	 * Build an where clause array
	 *
	 * @return array
	 */
	public function _buildContentWhereArray()
	{
	    $db = $this->getDbo();
	    $state = $this->getState();
	    $where = array();
	    if ($state->search)
	    {
	        $search = $db->quote('%' . $db->escape($state->search, true) . '%', false);
	        if (is_array($this->searchFields))
	        {
	            $whereOr = array();
	            foreach ($this->searchFields as $titleField)
	            {
	                $whereOr[] = " LOWER($titleField) LIKE " . $search;
	            }
	            $where[] = ' (' . implode(' OR ', $whereOr) . ') ';
	        }
	        else
	        {
	            $where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->quote('%' . $db->escape($state->search, true) . '%', false);
	        }
	    }
	
	
	    return $where;
	}
}