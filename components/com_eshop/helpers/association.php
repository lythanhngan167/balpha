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
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * EShop Component Association Helper
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          3.0
 */
abstract class EShopHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer $id   Id of the item
	 * @param   string  $view Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since  3.0
	 */

	public static function getAssociations($id = 0, $view = null)
	{
		jimport('helper.route', JPATH_COMPONENT_SITE);
		
		$input  = JFactory::getApplication()->input;
		$view   = is_null($view) ? $input->get('view') : $view;
		$id     = empty($id) ? $input->getInt('id') : $id;
		$return = array();

		if ($view == 'product' || $view == 'category' || $view == 'manufacturer')
		{
			if ($id)
			{
				$associations = EshopHelper::getAssociations($id, $view);

				foreach ($associations as $tag => $item)
				{
					if ($view == 'product')
					{
						$return[$tag] = EshopRoute::getProductRoute($item->product_id, EshopHelper::getProductCategory($item->product_id), $item->language);
					}
					if ($view == 'category')
					{
						$return[$tag] = EshopRoute::getCategoryRoute($item->category_id, $item->language);
					}
				}
			}
		}
		elseif ($view == 'cart' || $view == 'checkout' || $view == 'wishlist' || $view == 'compare' || $view == 'customer')
		{
			$languageTag = JFactory::getLanguage()->getTag();
			$languages   = EshopHelper::getLanguages();

			foreach ($languages as $language)
			{
				if ($language->lang_code != $languageTag)
				{
					$return[$language->lang_code] = EshopRoute::getViewRoute($view, $language->lang_code);
				}
			}
		}

		return $return;
	}
}