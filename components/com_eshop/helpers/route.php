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

class EshopRoute
{
	protected static $lookup;

	/**
	 *
	 * Function to get Product Route
	 *
	 * @param int $id
	 * @param int $catid
	 *
	 * @return string
	 */
	public static function getProductRoute($id, $catid)
	{
		$link = 'index.php?option=com_eshop&view=product&id=' . $id;

		if (!EshopHelper::getConfigValue('add_category_path'))
		{
			$item = self::getDefaultItemId();
			$link .= '&Itemid=' . $item;
		}
		else
		{
			$needles = array('product' => array((int) $id));

			if ($catid)
			{
				$needles['category']   = array_reverse(EshopHelper::getCategoryPath($catid, 'id'));
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid=' . $item;
			}
			else
			{
				$item = self::getDefaultItemId();
				$link .= '&Itemid=' . $item;
			}
		}

		return $link;
	}

	/**
	 *
	 * Function to get Category Route
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public static function getCategoryRoute($id)
	{
		if (!$id)
		{
			$link = '';
		}
		else
		{
			//Create the link
			$link    = 'index.php?option=com_eshop&view=category&id=' . $id;
			$catids  = array_reverse(EshopHelper::getCategoryPath($id, 'id'));
			$needles = array(
				'category'   => $catids,
				'categories' => $catids
			);

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid=' . $item;
			}

		}

		return $link;
	}

	/**
	 *
	 * Function to get Manufacturer Route
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public static function getManufacturerRoute($id)
	{
		if (!$id)
		{
			$link = '';
		}
		else
		{
			//Create the link
			$link    = 'index.php?option=com_eshop&view=manufacturer&id=' . $id;
			$needles = array(
				'manufacturer' => array((int) $id),
			);

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid=' . $item;
			}
		}

		return $link;
	}

	/**
	 *
	 * Function to get View Route
	 *
	 * @param string $view (cart, checkout, compare, wishlist)
	 *
	 * @return string
	 */
	public static function getViewRoute($view)
	{
		//Create the link
		$link = 'index.php?option=com_eshop&view=' . $view;

		if ($item = self::findView($view))
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 *
	 * Function to find a view
	 *
	 * @param string $view
	 *
	 * @return int
	 */
	public static function findView($view)
	{
		$needles = array(
			$view => array(0)
		);

		if ($item = self::_findItem($needles))
		{
			return $item;
		}
		elseif ($item = self::getDefaultItemId())
		{
			return $item;
		}
		else
		{
			return 0;
		}
	}

	/**
	 *
	 * Function to find Itemid
	 *
	 * @param string $needles
	 *
	 * @return int
	 */
	protected static function _findItem($needles = null)
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component = JComponentHelper::getComponent('com_eshop');
			$items     = $menus->getItems('component_id', $component->id);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$view]))
					{
						self::$lookup[$view] = array();
					}

					if (isset($item->query['id']))
					{
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
					else
					{
						self::$lookup[$view][0] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}

			if ($defaultItemid = self::getDefaultItemId())
			{
				return $defaultItemid;
			}

		}

		return 0;
	}

	/**
	 *
	 * Function to find default item id
	 */
	public static function getDefaultItemId()
	{
		if (EshopHelper::getConfigValue('default_menu_item') > 0)
		{
			return EshopHelper::getConfigValue('default_menu_item');
		}
		else
		{
			//Find in order: frontpage, categories, cart, checkout, wishlist, compare, customer
			$defaultViews = array('frontpage', 'categories', 'cart', 'checkout', 'wishlist', 'compare', 'customer');

			foreach ($defaultViews as $view)
			{
				if (isset(self::$lookup[$view]))
				{
					return self::$lookup[$view][0];
				}
			}
		}

		return 0;
	}
}