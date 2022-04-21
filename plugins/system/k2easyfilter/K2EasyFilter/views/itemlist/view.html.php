<?php
/**
 * @version		$Id: view.html.php 1511 2012-03-01 21:41:16Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewItemlist extends K2View {

	function display($tpl = null) {

		$mainframe = JFactory::getApplication();
		$params = K2HelperUtilities::getParams('com_k2');
		$model = $this->getModel('itemlist');
		$limitstart = JRequest::getInt('limitstart');
		$view = JRequest::getWord('view');
		$task = JRequest::getWord('task');
		$db = JFactory::getDBO();

		// <!--- added easy filter ---!>
		$pluginPath = JPATH_BASE.DS.'plugins'.DS.'system'.DS.'k2easyfilter'.DS.'K2EasyFilter';
		require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'controllers'.DS.'itemlist.php');
		$controller = new K2ControllerItemList;							
		$controller->addModelPath($pluginPath.DS."models");
		$model = $controller->getModel('ItemListEasyFilter');
		// <!--- added easy filter ---!>
		
		// Add link
		if (K2HelperPermissions::canAddItem())
			$addLink = JRoute::_('index.php?option=com_k2&view=item&task=add&tmpl=component');
		$this->assignRef('addLink', $addLink);

		// Get data depending on task
		switch ($task) {
				
			// <!--- easy filter added ---!>
			case 'easyfilter':
				// Set layout
				$this->setLayout('generic');

				// Set limit
				$limit = $params->get('genericItemCount');

				// Set title
				$title = JText::_('K2_SEARCH_RESULTS_FOR').' '.JRequest::getVar('etag');
				if(JRequest::getVar("keyword")) {
					$title .= " " . JRequest::getVar("keyword");
				}
				
				// Set ordering
				$ordering = $params->get('tagOrdering');

				$addHeadFeedLink = $params->get('genericFeedLink', 1);

				break;
			// <!--- easy filter added ---!>
		}

		// Set limit for model
		if (!$limit)
			$limit = 10;
		JRequest::setVar('limit', $limit);

		// Get items
		if (!isset($ordering))
		{
			$items = $model->getData();
		}
		else
		{
			$items = $model->getData($ordering);
		}

		// Pagination
		jimport('joomla.html.pagination');
		$total = count($items) ? $model->getTotal() : 0;
		$pagination = new JPagination($total, $limitstart, $limit);

		//Prepare items
		$user = JFactory::getUser();
		$cache = JFactory::getCache('com_k2_extended');
		$model = $this->getModel('item');

		for ($i = 0; $i < sizeof($items); $i++)
		{

			// Ensure that all items have a group. If an item with no group is found then assign to it the leading group
			$items[$i]->itemGroup = 'leading';

			//Item group
			if ($task == "category" || $task == "")
			{
				if ($i < ($params->get('num_links') + $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items')))
					$items[$i]->itemGroup = 'links';
				if ($i < ($params->get('num_secondary_items') + $params->get('num_leading_items') + $params->get('num_primary_items')))
					$items[$i]->itemGroup = 'secondary';
				if ($i < ($params->get('num_primary_items') + $params->get('num_leading_items')))
					$items[$i]->itemGroup = 'primary';
				if ($i < $params->get('num_leading_items'))
					$items[$i]->itemGroup = 'leading';
			}

			// Check if the model should use the cache for preparing the item even if the user is logged in
			if ($user->guest || $task == 'tag' || $task == 'search' || $task == 'date')
			{
				$cacheFlag = true;
			}
			else
			{
				$cacheFlag = true;
				if (K2HelperPermissions::canEditItem($items[$i]->created_by, $items[$i]->catid))
				{
					$cacheFlag = false;
				}
			}

			// Prepare item
			if ($cacheFlag)
			{
				$hits = $items[$i]->hits;
				$items[$i]->hits = 0;
				JTable::getInstance('K2Category', 'Table');
				$items[$i] = $cache->call(array(
					$model,
					'prepareItem'
				), $items[$i], $view, 'search');
				$items[$i]->hits = $hits;
			}
			else
			{
				$items[$i] = $model->prepareItem($items[$i], $view, 'search');
			}

			// Plugins
			$items[$i] = $model->execPlugins($items[$i], $view, 'search');

			// Trigger comments counter event if needed
			if ($params->get('catItemK2Plugins') &&
			    ($params->get('catItemCommentsAnchor') ||
			     $params->get('itemCommentsAnchor') ||
			     $params->get('itemComments')))
			{
				// Trigger comments counter event
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('k2');
				$results = $dispatcher->trigger('onK2CommentsCounter', array(
					&$items[$i],
					&$params,
					$limitstart
				));
				$items[$i]->event->K2CommentsCounter = trim(implode("\n", $results));
			}
		}

		// Set title
		$document = JFactory::getDocument();
		$application = JFactory::getApplication();
		$menus = $application->getMenu();
		$menu = $menus->getActive();
		if (is_object($menu))
		{
			if (is_string($menu->params))
			{
				$menu_params = K2_JVERSION == '15' ? new JParameter($menu->params) : new JRegistry($menu->params);
			}
			else
			{
				$menu_params = $menu->params;
			}
			if (!$menu_params->get('page_title'))
			{
				$params->set('page_title', $title);
			}
		}
		else
		{
			$params->set('page_title', $title);
		}

		// We're adding a new variable here which won't get the appended/prepended site title,
		// when enabled via Joomla!'s SEO/SEF settings
		$params->set('page_title_clean', $title);

		if (K2_JVERSION != '15')
		{
			if ($mainframe->getCfg('sitename_pagetitles', 0) == 1)
			{
				$tmpTitle = JText::sprintf('JPAGETITLE', $mainframe->getCfg('sitename'), $params->get('page_title'));
				$params->set('page_title', $tmpTitle);
			}
			elseif ($mainframe->getCfg('sitename_pagetitles', 0) == 2)
			{
				$tmpTitle = JText::sprintf('JPAGETITLE', $params->get('page_title'), $mainframe->getCfg('sitename'));
				$params->set('page_title', $tmpTitle);
			}
		}
		$document->setTitle($params->get('page_title'));

		// Search - Update the Google Search results container (K2 v2.6.9+)
		if ($task == 'search')
		{
			$googleSearchContainerID = trim($params->get('googleSearchContainer', 'k2GoogleSearchContainer'));
			if ($googleSearchContainerID == 'k2Container')
			{
				$googleSearchContainerID = 'k2GoogleSearchContainer';
			}
			$params->set('googleSearchContainer', $googleSearchContainerID);
		}

		// Set metadata for category
		if ($task == 'category')
		{
			if ($category->metaDescription)
			{
				$document->setDescription($category->metaDescription);
			}
			else
			{
				$metaDescItem = preg_replace("#{(.*?)}(.*?){/(.*?)}#s", '', $this->category->description);
				$metaDescItem = strip_tags($metaDescItem);
				$metaDescItem = K2HelperUtilities::characterLimit($metaDescItem, $params->get('metaDescLimit', 150));
				if (K2_JVERSION != '15')
				{
					$metaDescItem = html_entity_decode($metaDescItem);
				}
				$document->setDescription($metaDescItem);
			}
			if ($category->metaKeywords)
			{
				$document->setMetadata('keywords', $category->metaKeywords);
			}
			if ($category->metaRobots)
			{
				$document->setMetadata('robots', $category->metaRobots);
			}
			if ($category->metaAuthor)
			{
				$document->setMetadata('author', $category->metaAuthor);
			}
		}

		if (K2_JVERSION != '15')
		{

			// Menu metadata options
			if ($params->get('menu-meta_description'))
			{
				$document->setDescription($params->get('menu-meta_description'));
			}

			if ($params->get('menu-meta_keywords'))
			{
				$document->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}

			if ($params->get('robots'))
			{
				$document->setMetadata('robots', $params->get('robots'));
			}

			// Menu page display options
			if ($params->get('page_heading'))
			{
				$params->set('page_title', $params->get('page_heading'));
			}
			$params->set('show_page_title', $params->get('show_page_heading'));

		}

		// Pathway
		$pathway = $mainframe->getPathWay();
		if (!isset($menu->query['task']))
			$menu->query['task'] = '';
		if ($menu)
		{
			switch ($task)
			{
				case 'category' :
					if ($menu->query['task'] != 'category' || $menu->query['id'] != JRequest::getInt('id'))
						$pathway->addItem($title, '');
					break;
				case 'user' :
					if ($menu->query['task'] != 'user' || $menu->query['id'] != JRequest::getInt('id'))
						$pathway->addItem($title, '');
					break;

				case 'tag' :
					if ($menu->query['task'] != 'tag' || $menu->query['tag'] != JRequest::getVar('tag'))
						$pathway->addItem($title, '');
					break;

				case 'search' :
				case 'date' :
					$pathway->addItem($title, '');
					break;
			}
		}

		// Feed link
		$config = JFactory::getConfig();
		$menu = $application->getMenu();
		$default = $menu->getDefault();
		$active = $menu->getActive();
		if ($task == 'tag')
		{
			$link = K2HelperRoute::getTagRoute(JRequest::getVar('tag'));
		}
		else
		{
			$link = '';
		}
		$sef = K2_JVERSION == '30' ? $config->get('sef') : $config->getValue('config.sef');
		if (!is_null($active) && $active->id == $default->id && $sef)
		{
			$link .= '&Itemid='.$active->id.'&format=feed&limitstart=';
		}
		else
		{
			$link .= '&format=feed&limitstart=';
		}

		$feed = JRoute::_($link);
		$this->assignRef('feed', $feed);

		// Add head feed link
		if ($addHeadFeedLink)
		{
			$attribs = array(
				'type' => 'application/rss+xml',
				'title' => 'RSS 2.0'
			);
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array(
				'type' => 'application/atom+xml',
				'title' => 'Atom 1.0'
			);
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}

		// Assign data
		if ($task == "category" || $task == "")
		{

			// Leading items
			$offset = 0;
			$length = $params->get('num_leading_items');
			$leading = array_slice($items, $offset, $length);

			// Primary
			$offset = (int)$params->get('num_leading_items');
			$length = (int)$params->get('num_primary_items');
			$primary = array_slice($items, $offset, $length);
			
			// Secondary
			$offset = (int)($params->get('num_leading_items') + $params->get('num_primary_items'));
			$length = (int)$params->get('num_secondary_items');
			$secondary = array_slice($items, $offset, $length);

			// Links
			$offset = (int)($params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items'));
			$length = (int)$params->get('num_links');
			$links = array_slice($items, $offset, $length);

			// Assign data
			$this->assignRef('leading', $leading);
			$this->assignRef('primary', $primary);
			$this->assignRef('secondary', $secondary);
			$this->assignRef('links', $links);
		}
		else
		{
			$this->assignRef('items', $items);
		}

		// Set default values to avoid division by zero
		if ($params->get('num_leading_columns') == 0)
			$params->set('num_leading_columns', 1);
		if ($params->get('num_primary_columns') == 0)
			$params->set('num_primary_columns', 1);
		if ($params->get('num_secondary_columns') == 0)
			$params->set('num_secondary_columns', 1);
		if ($params->get('num_links_columns') == 0)
			$params->set('num_links_columns', 1);

		$this->assignRef('params', $params);
		$this->assignRef('pagination', $pagination);

		// Set Facebook meta data
		$document = JFactory::getDocument();
		$uri = JURI::getInstance();
		$document->setMetaData('og:url', $uri->toString());
		$document->setMetaData('og:title', (K2_JVERSION == '15') ? htmlspecialchars($document->getTitle(), ENT_QUOTES, 'UTF-8') : $document->getTitle());
		$document->setMetaData('og:type', 'website');
		if ($task == 'category' && $this->category->image && strpos($this->category->image, 'placeholder/category.png') === false)
		{
			$image = substr(JURI::root(), 0, -1).str_replace(JURI::root(true), '', $this->category->image);
			$document->setMetaData('og:image', $image);
			$document->setMetaData('image', $image);
		}
		$document->setMetaData('og:description', strip_tags($document->getDescription()));

		// Look for template files in component folders
		$this->_addPath('template', JPATH_COMPONENT.DS.'templates');
		$this->_addPath('template', JPATH_COMPONENT.DS.'templates'.DS.'default');

		// Look for overrides in template folder (K2 template structure)
		$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates');
		$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates'.DS.'default');

		// Look for overrides in template folder (Joomla! template structure)
		$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'default');
		$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2');

		// Look for specific K2 theme files
		if ($params->get('theme'))
		{
			$this->_addPath('template', JPATH_COMPONENT.DS.'templates'.DS.$params->get('theme'));
			$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates'.DS.$params->get('theme'));
			$this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.$params->get('theme'));
		}

		$nullDate = $db->getNullDate();
		$this->assignRef('nullDate', $nullDate);
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('k2');
		$dispatcher->trigger('onK2BeforeViewDisplay');
		// Prevent spammers from using the tag view
		if ($task == 'tag' && !count($this->items))
		{
			$tag = JRequest::getString('tag');
			$db = JFactory::getDBO();
			$db->setQuery('SELECT id FROM #__k2_tags WHERE name = '.$db->quote($tag));
			$tagID = $db->loadResult();
			if (!$tagID)
			{
				JError::raiseError(404, JText::_('K2_NOT_FOUND'));
				return false;
			}
		}
		parent::display($tpl);
	}

}