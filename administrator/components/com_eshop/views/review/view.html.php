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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopViewReview extends EShopViewForm
{

	function __construct($config)
	{
		$config['name'] = 'review';
		parent::__construct($config);
	}
	
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//Build products list
		$query->select('a.id AS value, b.product_name AS text')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('a.ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if (count($rows))
		{
			$lists['products'] = JHtml::_('select.genericlist', $rows, 'product_id',
				array(
					'option.text.toHtml' => false,
					'option.text' => 'text',
					'option.value' => 'value',
					'list.attr' => ' class="inputbox chosen" ',
					'list.select' => $item->product_id));
		}
		else
		{
			$lists['products'] = '';
		}
		$ratingHtml = '<b>' . JText::_('ESHOP_BAD') . '</b>';
		for ($i = 1; $i <= 5; $i++)
		{
			if ($i == $item->rating)
				$checked = ' checked';
			else 
				$checked = '';
			$ratingHtml .= '<input type="radio" name="rating" value="' . $i . '" style="width: 25px;"' . $checked .' />';
		}
		$ratingHtml .= '<b>' . JText::_('ESHOP_EXCELLENT') . '</b>';
		$lists['rating'] = $ratingHtml;
	}
}