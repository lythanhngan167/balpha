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
	protected static $lang_lookup = array();

	/**
	 *
	 * Function to get Product Route
	 *
	 * @param int $id
	 * @param int $catid
	 *
	 * @return string
	 */
	public static function getProductRoute($id, $catid, $language = '')
	{
		$link = 'index.php?option=com_eshop&view=product&id=' . $id;

		if (!EshopHelper::getConfigValue('add_category_path'))
		{
			$item = self::getDefaultItemId($language);
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

			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				self::buildLanguageLookup();

				if (isset(self::$lang_lookup[$language]))
				{
					$link .= '&lang=' . self::$lang_lookup[$language] . '&l=' . $language;
					$needles['language'] = $language;
				}
			}

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid=' . $item;
			}
			else
			{
				$item = self::getDefaultItemId($language);
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
	public static function getCategoryRoute($id, $language = '')
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

			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				self::buildLanguageLookup();

				if (isset(self::$lang_lookup[$language]))
				{
					$link .= '&lang=' . self::$lang_lookup[$language] . '&l=' . $language;
					$needles['language'] = $language;
				}
			}

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
	public static function getManufacturerRoute($id, $language = '')
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

			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				self::buildLanguageLookup();

				if (isset(self::$lang_lookup[$language]))
				{
					$link .= '&lang=' . self::$lang_lookup[$language] . '&l=' . $language;
					$needles['language'] = $language;
				}
			}

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
	public static function getViewRoute($view, $language = '')
	{
		//Create the link
		$link = 'index.php?option=com_eshop&view=' . $view;

		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			self::buildLanguageLookup();

			if (isset(self::$lang_lookup[$language]))
			{
				$link .= '&lang=' . self::$lang_lookup[$language] . '&l=' . $language;
			}
		}

		if ($item = self::findView($view, $language))
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
	public static function findView($view, $language = '')
	{
		$needles = array(
			$view => array(0)
		);

		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			self::buildLanguageLookup();

			if (isset(self::$lang_lookup[$language]))
			{
				$needles['language'] = $language;
			}
		}

		if ($item = self::_findItem($needles))
		{
			return $item;
		}
		elseif ($item = self::getDefaultItemId($language))
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
	 * Function to build lanugage lookup
	 */
	protected static function buildLanguageLookup()
	{
		if (count(self::$lang_lookup) == 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');
			$db->setQuery($query);
			$langs = $db->loadObjectList();

			foreach ($langs as $lang)
			{
				self::$lang_lookup[$lang->lang_code] = $lang->sef;
			}
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
		$app      = JFactory::getApplication();
		$menus    = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = array();

			$component  = JComponentHelper::getComponent('com_eshop');
			$attributes = array('component_id');
			$values     = array($component->id);

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[]     = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = array();
					}

					if (isset($item->query['id']))
					{
						self::$lookup[$language][$view][$item->query['id']] = $item->id;
					}
					else
					{
						self::$lookup[$language][$view][0] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}

			if ($defaultItemid = self::getDefaultItemId($language))
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
	public static function getDefaultItemId($language = '')
	{
		if (!$language || $language == '*')
		{
			$language = JFactory::getLanguage()->getTag();
		}

		if (JLanguageMultilang::isEnabled())
		{
			if (EshopHelper::getConfigValue('default_menu_item_' . $language) > 0)
			{
				return EshopHelper::getConfigValue('default_menu_item_' . $language);
			}
		}
		else if (EshopHelper::getConfigValue('default_menu_item') > 0)
		{
			return EshopHelper::getConfigValue('default_menu_item');
		}
		else
		{
			//Find in order: frontpage, categories, cart, checkout, wishlist, compare, customer
			$defaultViews = array('frontpage', 'categories', 'cart', 'checkout', 'wishlist', 'compare', 'customer');

			foreach ($defaultViews as $view)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					return self::$lookup[$language][$view][0];
				}
			}
		}

		return 0;
	}
}