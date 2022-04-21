<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

/**
 * Routing class from com_eventbooking
 *
 * @since  2.8.1
 */
class EshopRouter extends JComponentRouterBase
{
    /**
     * 
     * Build the route for the com_eshop component
     * @param	array	An array of URL arguments
     * @return	array	The URL arguments to use to assemble the subsequent URL.
     * @since	1.5
     */
    public function build(&$query)
    {
    	$segments = array();
    	require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';
    	require_once JPATH_ROOT . '/components/com_eshop/helpers/' . ((version_compare(JVERSION, '3.0', 'ge') && JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1) ? 'routev3.php' : 'route.php');
    	$db = JFactory::getDbo();
    	$queryArr = $query;
    	if (isset($queryArr['option']))
    		unset($queryArr['option']);
    	if (isset($queryArr['Itemid']))
    		unset($queryArr['Itemid']);
    	//Store the query string to use in the parseRouter method
    	$queryString = http_build_query($queryArr);
    	
    	$app		= JFactory::getApplication();
    	$menu		= $app->getMenu();
    	
    	//We need a menu item.  Either the one specified in the query, or the current active one if none specified
    	if (empty($query['Itemid']))
    		$menuItem = $menu->getActive();
    	else
    		$menuItem = $menu->getItem($query['Itemid']);
    	
    	if (empty($menuItem->query['view']))
    	{
    		$menuItem->query['view'] = '';
    	}
    	//Are we dealing with an product or category that is attached to a menu item?
    	if (($menuItem instanceof stdClass) && isset($query['view']) && isset($query['id']) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == intval($query['id']))
    	{
    		unset($query['view']);
    		if (isset($query['catid']))
    			unset($query['catid']);
    		unset($query['id']);
    	}
    	
    	if (($menuItem instanceof stdClass) && $menuItem->query['view'] == 'category' && isset($query['catid']) && $menuItem->query['id'] == intval($query['catid']))
    	{
    		if (isset($query['catid']))
    			unset($query['catid']);
    	}
    	
    	$parentId = 0;
    	if (($menuItem instanceof stdClass))
    	{
    		if (isset($menuItem->query['view']) && $menuItem->query['view'] == 'category')
    		{
    			$parentId = (int)$menuItem->query['id'];
    		}
    	}
    			
    	$view = isset($query['view']) ? $query['view'] : '';
    	$id = 	isset($query['id']) ? (int) $query['id'] : 0;
    	$catid = isset($query['catid']) ? (int) $query['catid'] : 0;
    
    	if ($view == 'cart' || $view == 'quote' || $view == 'checkout' || $view == 'wishlist' || $view == 'compare' || $view == 'customer')
    	{
    		if (isset($query['Itemid']) && !EshopRoute::findView($view, isset($query['l']) ? $query['l'] : ''))
    		{
    			unset($query['Itemid']);
    		}
    	}
    	
    	switch ($view)
    	{
    		case 'search':
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_SEARCH_RESULT');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_SEARCH_RESULT');
    				}
    			}
    			break;
    		case 'filter':
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_FILTER');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_FILTER');
    				}
    			}
    			break;	
    		case 'category' :
    			if ($id)
    				$segments = array_merge( $segments, EshopHelper::getCategoryPath($id, 'alias', isset($query['l']) ? $query['l'] : '', $parentId));
    			break;
    		case 'product' :
    			if ($id)
    			{
    				$segments[] = EshopHelper::getElementAlias($id, 'product', isset($query['l']) ? $query['l'] : '');
    			}
    			if ($catid)
    			{
    				$segments = array_merge(EshopHelper::getCategoryPath($catid, 'alias', isset($query['l']) ? $query['l'] : '', $parentId), $segments);
    			}
    			break;
    		case 'manufacturer':
    			if ($id)
    			{
    				$segments[] = EshopHelper::getElementAlias($id, 'manufacturer', isset($query['l']) ? $query['l'] : '');
    			}
    			break;
    		case 'checkout':
    			$layout = isset($query['layout']) ? $query['layout'] : '';
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_CHECKOUT');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_CHECKOUT');
    				}
    			}
    			if (isset($query['l']))
    			{
    				$language = JLanguage::getInstance($query['l'], 0);
    				$language->load('com_eshop', JPATH_ROOT, $query['l']);
    				switch ($layout)
    				{
    					case 'cancel':
    						$segments[] = $language->_('ESHOP_CHECKOUT_CANCEL');
    						break;
    					case 'complete':
    						$segments[] = $language->_('ESHOP_CHECKOUT_COMPLETE');
    						break;
    					case 'failure':
    						$segments[] = $language->_('ESHOP_CHECKOUT_FAILURE');
    						break;
    					default:
    						break;
    				}
    			}
    			else
    			{
    				switch ($layout)
    				{
    					case 'cancel':
    						$segments[] = JText::_('ESHOP_CHECKOUT_CANCEL');
    						break;
    					case 'complete':
    						$segments[] = JText::_('ESHOP_CHECKOUT_COMPLETE');
    						break;
    					case 'failure':
    						$segments[] = JText::_('ESHOP_CHECKOUT_FAILURE');
    						break;
    					default:
    						break;
    				}
    			}
    			break;
    		case 'cart':
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_SHOPPING_CART');
    				}
    				else 
    				{
    					$segments[] = JText::_('ESHOP_SHOPPING_CART');
    				}
    			}
    			break;
    		case 'quote':
    			$layout = isset($query['layout']) ? $query['layout'] : '';
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_QUOTE_CART');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_QUOTE_CART');
    				}
    			}
    			if (isset($query['l']))
    			{
    				$language = JLanguage::getInstance($query['l'], 0);
    				$language->load('com_eshop', JPATH_ROOT, $query['l']);
    				switch ($layout)
    				{
    					case 'complete':
    						$segments[] = $language->_('ESHOP_QUOTE_COMPLETE');
    						break;
    					default:
    						break;
    				}
    			}
    			else
    			{
    				switch ($layout)
    				{
    					case 'complete':
    						$segments[] = JText::_('ESHOP_QUOTE_COMPLETE');
    						break;
    					default:
    						break;
    				}
    			}
    			break;
    		case 'wishlist':
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_WISHLIST');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_WISHLIST');
    				}
    			}
    			break;
    		case 'compare':
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_COMPARE');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_COMPARE');
    				}
    			}
    			break;
    		case 'customer':
    			$layout = isset($query['layout']) ? $query['layout'] : '';
    			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId(isset($query['l']) ? $query['l'] : '')))
    			{
    				if (isset($query['l']))
    				{
    					$language = JLanguage::getInstance($query['l'], 0);
    					$language->load('com_eshop', JPATH_ROOT, $query['l']);
    					$segments[] = $language->_('ESHOP_CUSTOMER');
    				}
    				else
    				{
    					$segments[] = JText::_('ESHOP_CUSTOMER');
    				}
    			}
    			if (isset($query['l']))
    			{
    				$language = JLanguage::getInstance($query['l'], 0);
    				$language->load('com_eshop', JPATH_ROOT, $query['l']);
    				switch ($layout)
    				{
    					case 'account':
    						$segments[] = $language->_('ESHOP_EDIT_ACCOUNT');
    						break;
    					case 'orders':
    						$segments[] = $language->_('ESHOP_ORDER_HISTORY');
    						break;
    					case 'downloads':
    						$segments[] = $language->_('ESHOP_DOWNLOADS');
    						break;
    					case 'addresses':
    						$segments[] = $language->_('ESHOP_ADDRESSES');
    						break;
    					case 'order':
    						$segments[] = $language->_('ESHOP_ORDER_DETAILS');
    						break;
    					case 'address':
    						$segments[] = $language->_('ESHOP_ADDRESS_EDIT');
    						break;
    					default:
    						break;
    				}
    			}
    			else
    			{
    				switch ($layout)
    				{
    					case 'account':
    						$segments[] = JText::_('ESHOP_EDIT_ACCOUNT');
    						break;
    					case 'orders':
    						$segments[] = JText::_('ESHOP_ORDER_HISTORY');
    						break;
    					case 'downloads':
    						$segments[] = JText::_('ESHOP_DOWNLOADS');
    						break;
    					case 'addresses':
    						$segments[] = JText::_('ESHOP_ADDRESSES');
    						break;
    					case 'order':
    						$segments[] = JText::_('ESHOP_ORDER_DETAILS');
    						break;
    					case 'address':
    						$segments[] = JText::_('ESHOP_ADDRESS_EDIT');
    						break;
    					default:
    						break;
    				}
    			}
    			break;

            case 'campaign':
                $segments[] = 'campaign';
                $segments[] = $query['id'];
                break;
    	}
    
    	if (isset($query['task']) && $query['task'] == 'product.downloadPDF')
    		$segments[] = JText::_('ESHOP_DOWNLOAD_PDF');
    	if (isset($query['task']) && $query['task'] == 'customer.downloadInvoice')
    		$segments[] = JText::_('ESHOP_DOWNLOAD_INVOICE');
    	if (isset($query['task']) && $query['task'] == 'cart.reOrder')
    		$segments[] = JText::_('ESHOP_RE_ORDER');
    	if (isset($query['task']) && $query['task'] == 'customer.downloadFile')
    		$segments[] = JText::_('ESHOP_DOWNLOAD');
    	if (isset($query['task']) && $query['task'] == 'search')
    		$segments[] = JText::_('ESHOP_SEARCH');
    
    	
    	if (isset($query['task']))
    		unset($query['task']);
    	
    	if (isset($query['view']))
    		unset($query['view']);
    	
    	if (isset($query['id']))
    		unset($query['id']);
    	
    	if (isset($query['catid']))
    		unset($query['catid']);
    	
    	if (isset($query['key']))
    		unset($query['key']);
    
    	if (isset($query['redirect']))
    		unset($query['redirect']);
    	
    	if (isset($query['l']))
    		unset($query['l']);
    	
    	if (isset($query['layout']))
    		unset($query['layout']);
    	
    	if (count($segments))
    	{
    		$segments    = array_map('JApplicationHelper::stringURLSafe', $segments);
    		$key = md5(implode('/', $segments));
    		$q = $db->getQuery(true);
    		$q->select('id')
    			->from('#__eshop_urls')
    			->where('md5_key="'.$key.'"');
    		$db->setQuery($q);
    		$urlId = $db->loadResult();
    		if (!$urlId)
    		{
    			$q->clear();
    			$q->insert('#__eshop_urls')
    				->columns('md5_key, `query`')
    				->values("'$key', '$queryString'");
    			$db->setQuery($q);
    			$db->execute();
    		}
    		else 
    		{
    			$q->clear();
    			$q->update('#__eshop_urls')
    				->set('query="'.$queryString.'"')
    				->where('md5_key="'.$key.'"');
    			$db->setQuery($q);
    			$db->execute();
    		}
    	}
    		
    	return $segments;
    }
    
    /**
     * 
     * Parse the segments of a URL.
     * @param	array	The segments of the URL to parse.
     * @return	array	The URL attributes to be used by the application.
     * @since	1.5
     */
    function parse(&$segments)
    {		
    	$vars = array();
    	
    	if (count($segments))
    	{
    		$db = JFactory::getDbo();
    		$key = md5(str_replace(':', '-', implode('/', $segments)));
    		$query = $db->getQuery(true);
    		$query->select('`query`')
    			->from('#__eshop_urls')
    			->where('md5_key = "' . $key . '"');
    		$db->setQuery($query);
    		$queryString = $db->loadResult();
    		
    		if ($queryString)
    		{
    			parse_str(html_entity_decode($queryString), $vars);
    		}
    		else
    		{
    			$method = strtoupper(JFactory::getApplication()->input->getMethod());
    
    			if ($method == 'GET')
    			{
    				throw new Exception('Page not found', 404);
    			}
    		}
    		
    		if (version_compare(JVERSION, '4.0.0-dev', 'ge'))
    		{
    		    $segments = [];
    		}
    	}
    	
    	$item = JFactory::getApplication()->getMenu()->getActive();
    	
    	if ($item)
    	{
    		foreach ($item->query as $key=>$value)
    		{
    			if ($key != 'option' && $key != 'Itemid' && !isset($vars[$key]))
    				$vars[$key] = $value;
    		}
    	}
    		
    	return $vars;
    }
}

/**
 * EShop router functions
 *
 * These functions are proxies for the new router interface
 * for old SEF extensions.
 *
 * @param   array &$query An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 */
function EshopBuildRoute(&$query)
{
    $router = new EshopRouter();

    return $router->build($query);
}

function EshopParseRoute($segments)
{
    $router = new EshopRouter();

    return $router->parse($segments);
}
