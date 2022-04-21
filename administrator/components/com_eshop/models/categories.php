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
class EShopModelCategories extends EShopModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('b.category_name', 'b.category_desc');
		$config['translatable'] = true;
		$config['translatable_fields'] = array('category_name', 'category_alias', 'category_desc', 'meta_key', 'meta_desc');
		
		parent::__construct($config);
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$db = $this->getDbo();
			$query = $this->_buildQuery();
			$query->select(' a.category_parent_id AS parent_id ')->select(' b.category_name AS title ');
			
			// We will build the data here
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$children = array();
			// first pass - collect children
			if (count($rows))
			{
				foreach ($rows as $v)
				{
					$pt = $v->parent_id;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $v);
					$children[$pt] = $list;
				}
			}
			$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999);
			$total = count($list);
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($total, $this->getState('limitstart'), $this->getState('limit'));
			// slice out elements based on limits
			$list = array_slice($list, $this->_pagination->limitstart, $this->_pagination->limit);
			$this->_data = $list;
		}
		
		return $this->_data;
	}
}