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

class EShopModelCompare extends EShopModel
{

	/**
	 * 
	 * Constructor
	 * @since 1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct();
	}
	
	function add($productId)
	{
		$json = array();
		$user = JFactory::getUser();
		$session = JFactory::getSession();
		$compare = $session->get('compare');
		if (!$compare)
		{
			$compare = array();
		}
		$productInfo = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());
		if (is_object($productInfo))
		{
			if (!in_array($productId, $compare))
			{
				if (count($compare) >= 4) {
					array_shift($compare);
				}
				$compare[] = $productId;
				$session->set('compare', $compare);
			}
			$viewProductLink = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
			$viewCompareLink = JRoute::_(EshopRoute::getViewRoute('compare'));
			$message = '<div class="compare-message">' . sprintf(JText::_('ESHOP_ADD_TO_COMPARE_SUCCESS_MESSAGE'), $viewProductLink, $productInfo->product_name, $viewCompareLink) . '</div>';
			$json['success']['message'] =  '<h1>' . JText::_('ESHOP_PRODUCT_COMPARE') . '</h1>' . $message;
		}
		return $json;
	}
}