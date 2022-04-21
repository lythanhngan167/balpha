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
class EShopModelQuotes extends EShopModelList
{
	function __construct($config)
	{
		$config['search_fields'] = array('name', 'email', 'company', 'telephone', 'message');
		$config['state_vars'] = array( 
			'filter_order_Dir' => array('DESC', 'cmd', 1));
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
		$db = $this->getDbo();
		$state = $this->getState();
		$query = $db->getQuery(true);
		$query->select('a.*')
			->from($this->mainTable . ' AS a ');
		$where = $this->_buildContentWhereArray();
		if (count($where))
			$query->where($where);
		$orderby = $this->_buildContentOrderBy();
		if ($orderby != '')
			$query->order($orderby);
		return $query;
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