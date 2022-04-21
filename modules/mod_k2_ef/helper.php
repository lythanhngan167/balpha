<?php 

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');


class modK2EasyFilterHelper {
	
	function getTags(&$params, $restcata = 0) {
		
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();

		$jnow = &JFactory::getDate();
		$now = $jnow->toSQL();
		$nullDate = $db->getNullDate();

		$query = "SELECT i.id FROM #__k2_items as i";
		$query .= " LEFT JOIN #__k2_categories c ON c.id = i.catid";
		$query .= " WHERE i.published=1 ";
		$query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." ) ";
		$query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
		$query .= " AND i.trash=0 ";

		$query .= " AND i.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		$query .= " AND c.published=1 ";
		$query .= " AND c.trash=0 ";

		$query .= " AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		$catids = $params->get('catids');
		if($catids != '') {
				$tagCategory = $catids;
				$tagCategory = str_replace(" ", "", $tagCategory);
				$tagCategory = explode(",", $tagCategory);
				if(is_array($tagCategory)) {
					$tagCategory = array_filter($tagCategory);
				}
				if ($tagCategory) {
					if(!is_array($tagCategory)){
						$tagCategory = (array)$tagCategory;
					}
					foreach($tagCategory as $tagCategoryID){
						$categories[] = $tagCategoryID;
						$children = modK2EasyFilterHelper::getCategoryChildren($tagCategoryID);
						$categories = @array_merge($categories, $children);
					}
					$categories = @array_unique($categories);
					JArrayHelper::toInteger($categories);
					if(count($categories)==1){
						$query .= " AND i.catid={$categories[0]}";
					}
					else {
						$query .= " AND i.catid IN(".implode(',', $categories).")";
					}
				}
			}
		
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND c.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") AND i.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}

		$db->setQuery($query);
		$IDs = K2_JVERSION == '30' ? $db->loadColumn() : $db->loadResultArray();

		$query = "SELECT tag.name, tag.id
        FROM #__k2_tags as tag
        LEFT JOIN #__k2_tags_xref AS xref ON xref.tagID = tag.id 
        WHERE xref.itemID IN (".implode(',', $IDs).") 
        AND tag.published = 1 ORDER BY tag.name";
		$db->setQuery($query);
		$rows = K2_JVERSION == '30' ? $db->loadColumn() : $db->loadResultArray();
		$cloud = array();

		if (count($rows)) {
			
			foreach ($rows as $tag) {
				if (@array_key_exists($tag, $cloud)) {
					$cloud[$tag]++;
				} else {
					$cloud[$tag] = 1;
				}
			}

			$counter = 0;
			foreach ($cloud as $key=>$value) {
				$tags[$counter]-> {'tag'} = $key;
				$counter++;
			}

			return $tags;
		}
	}
	
	function getCategoryChildren($catid) {

		static $array = array();
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$catid = (int) $catid;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 ";

			$query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}

		$query .= " ORDER BY ordering ";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		foreach ($rows as $row) {
			array_push($array, $row->id);
			if (modK2EasyFilterHelper::hasChildren($row->id)) {
				modK2EasyFilterHelper::getCategoryChildren($row->id);
			}
		}
		return $array;
	}
	
	function hasChildren($id) {

		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$id = (int) $id;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories  WHERE parent={$id} AND published=1 AND trash=0 ";

			$query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		if (count($rows)) {
			return true;
		} else {
			return false;
		}
	}
	
	function treeselectbox(&$params, $id = 0, $level = 0) {

		$mainframe = &JFactory::getApplication();
		
		$catids = $params->get('catids');
		if($catids != '') {
			$root_id = str_replace(" ", "", $catids);
			$root_id = explode(",", $root_id);
		}
		else $root_id = "";
		
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$category = JRequest::getInt('ecategory');
		$task = JRequest::getVar('task');
		
		if($option == "com_k2" && $view == "itemlist" && $task == "category") {
			$category = JRequest::getInt('id');
		}		
		
		if($option == "com_k2" && $view == "item") {
			$itemid = JRequest::getInt('id');
			$category = modK2EasyFilterHelper::getParent($itemid);
		}
		
		$id = (int) $id;
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();
		
		if (($root_id != 0) && ($level == 0)) {
			if(!is_array($root_id)) {
				$root_id = Array($root_id);
			}
			$query = "SELECT * FROM #__k2_categories WHERE id IN(".implode(",", $root_id).") AND published=1 AND trash=0 ";
		} else {
			$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 ";
		}

			$query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}

		$query .= " ORDER BY ordering";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		
		$onchange = $params->get('onchange', 0);
		if($onchange) {
			$onchange = " onchange=\"document.K2EasyFilter.submit()\"";
		}
		
		if($level == 0) {
			
			$output = "<select name=\"ecategory\"". $onchange ."><option value=\"\">". JText::_("MOD_K2_EF_SELECT_CATEGORY_DEFAULT") ."</option>";
		
		}

		$indent = "";
		for ($i = 0; $i < $level; $i++) {
			$indent .= '&ndash; ';
		}
		
		foreach ($rows as $k => $row) {
			if (($option == 'com_k2') && ($category == $row->id)) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			if (modK2EasyFilterHelper::hasChildren($row->id)) {
				@$output .= '<option value="'.$row->id.'"'.$selected.'>'.$indent.$row->name.'</option>';
				@$output .= modK2EasyFilterHelper::treeselectbox($params, $row->id, $level + 1);
			} else {
				if(modK2EasyFilterHelper::countItems($row->id)) {
					@$output .= '<option value="'.$row->id.'"'.$selected.'>'.$indent.$row->name.'</option>';
				}
			}
		}
		
		if ($level == 0) {
			$output .= "
				</select>
			";
		}
		
		return $output;
	}
	
	function countItems($catid) {
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items WHERE catid = {$catid} AND published = 1 AND trash = 0";
		$db->setQuery($query);
		
		return $db->loadResult();
	}
	
	function getAuthors(&$params) {
		$mainframe = &JFactory::getApplication();
		$componentParams = &JComponentHelper::getParams('com_k2');
		$where = '';
		$cid = $params->get('authors_module_category');
		if ($cid > 0) {
			$categories = modK2ToolsHelper::getCategoryChildren($cid);
			$categories[] = $cid;
			JArrayHelper::toInteger($categories);
			$where = " catid IN(".implode(',', $categories).") AND ";

		}

		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();

		$jnow = &JFactory::getDate();
		$now = $jnow->toSQL();
		$nullDate = $db->getNullDate();


			$languageCheck = '';
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$languageCheck = "AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').")";
			}
			$query = "SELECT DISTINCT created_by FROM #__k2_items
	        WHERE {$where} published=1 
	        AND ( publish_up = ".$db->Quote($nullDate)." OR publish_up <= ".$db->Quote($now)." ) 
	        AND ( publish_down = ".$db->Quote($nullDate)." OR publish_down >= ".$db->Quote($now)." ) 
	        AND trash=0 
	        AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") 
	        AND created_by_alias='' 
			{$languageCheck}
	        AND EXISTS (SELECT * FROM #__k2_categories WHERE id= #__k2_items.catid AND published=1 AND trash=0 AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") {$languageCheck})";        	

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$authors = array();
		if (count($rows)) {
			foreach ($rows as $row) {
				$author = JFactory::getUser($row->created_by);
				if($author->block == 1) continue;
				
				$author->link = JRoute::_(K2HelperRoute::getUserRoute($author->id));

				$query = "SELECT id, gender, description, image, url, `group`, plugins FROM #__k2_users WHERE userID=".(int)$author->id;
				$db->setQuery($query);
				$author->profile = $db->loadObject();

				$authors[] = $author;
			}
		}
		return $authors;
	}
	
	function getParent($id) {
		$db = &JFactory::getDBO();
		
		$query = "SELECT * FROM #__k2_items WHERE id = {$id}";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->catid;
	}
	
}
