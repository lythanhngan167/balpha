<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Routing class from com_joomprofile
 *
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 */
class JoomprofileRouter
{
    public function _getMenus()
    {
    	static $menus = null;
    	
        if($menus === null){
        	$app      	= JFactory::getApplication();
			$sitemenus 	= $app->getMenu('site');        
        	$component  = JComponentHelper::getComponent('com_joomprofile');
			$attributes = array('component_id');
			$values     = array($component->id);

			$menus = $sitemenus->getItems($attributes, $values);
		}
	
		return $menus;
    }

    public function getActiveMenuItemid(&$query)
    {        
    	$menus = $this->_getMenus();
    	$activeMenu = null; 
        $lang_tag = JFactory::getLanguage()->getTag();
            
        if($menus){
            $prevcount      = 0;
            foreach($menus as $menu){
            	$menuQuery = $menu->query;
            	$count = 0;
            	foreach($menuQuery as $key => $value)
	            {
            		if(empty($menuQuery[$key])){
            			continue;	
            		}

            		// if diff menu the set count to 0
	                if(!isset($query[$key]) || $value !== $query[$key]){
	                	$count = 0;
	                	break;
	                }
	
					$count++;
	            }
	            
             	// if language is set on menu
                if(isset($menu->language)){
                    $menu->language = trim($menu->language);

	                if ($count > 0 && $menu->language == $lang_tag) {
            	    	//count matching
            	   		$count++;
            	    }
            	}
                
                //current menu matches more
                if($count > $prevcount){
                    $prevcount		= $count;
                    $activeMenu 	= $menu;
                }
            }
        }
        
        //assig ItemID of selected menu if any
        if($activeMenu !== null){
            return $activeMenu->id;
        }
         
        return 0;
    }
   		
	public function build(&$query)
	{		
		$segments = array();	
	
		if(!isset($query['view']) || !isset($query['task'])){
			return $segments;
		}
		
		$view = $query['view'];
		$task = $query['task'];		
		
		$app      = JFactory::getApplication();
		$menus  = $app->getMenu('site');
		
		// if itemid is set and has all the query element then return 
		if(isset($query['Itemid'])){
			$item = $menus->getItem($query['Itemid']);
     		
			if ($item->component != 'com_joomprofile'){
				unset($query['Itemid']);
			}            
		}
		
		//if(!isset($query['Itemid'])){
			$query['Itemid'] = $this->getActiveMenuItemid($query);
		//}
		
		$item = $menus->getItem($query['Itemid']);
		
		if(!isset($item->query['view']) || $item->query['view'] != $view){
			$segments[] = $view;
		}
		unset($query['view']);
		
		if(!isset($item->query['task']) || $item->query['task'] != $task){
			$segments = array_merge($segments, explode('.', $task));
		}
		unset($query['task']);
			
		if((isset($query['id']))){
			if(!isset($item->query['id']) || $item->query['id'] != $query['id']){
				if ($view == 'profile' && ($task == 'user.display' || $task == 'user.edit'))
				{
					if (isset($query['id']))
					{
						// Make sure we have the id and the alias
						if (strpos($query['id'], '-') === false)
						{
							$db = JFactory::getDbo();
							$dbQuery = $db->getQuery(true)
								->select('name')
								->from('#__users')
								->where('id=' . (int) $query['id']);
							$db->setQuery($dbQuery);
							$username = $db->loadResult();
							
							if ($app->getCfg('unicodeslugs', 0) == 1)
							{
								$username = JFilterOutput::stringURLUnicodeSlug($username);
							}
							else
							{
								$username = JFilterOutput::stringURLSafe($username);
							}
						
							$query['id'] = $query['id'] . '-' . $username;
						}
					}
				}
				
				$segments[] = $query['id'];
			}
						
			unset($query['id']);		
		}			
		
		if(empty($query['Itemid'])){
			unset($query['Itemid']);
		}
		return $segments;
	}

	public function parse(&$segments)
	{
		$vars = array();
		
		// get active menu
		$item = JFactory::getApplication()->getMenu()->getActive();
		
		if(isset($item->query['view'])){
			$vars['view'] = $item->query['view']; 
		}
		else{
			$vars['view'] = array_shift($segments);
		}
		
		if(isset($item->query['task'])){
			$vars['task'] = $item->query['task']; 
		}
		else{
			$vars['task'] = array_shift($segments).'.'.array_shift($segments);
		}		
		
		if(isset($item->query['id'])){
			$vars['id'] = $item->query['id']; 
		}		
		
		if(is_array($segments)){			
			$id = array_shift($segments);
			// We check to see if an alias is given.  If not, we assume it is an user name
			if (strpos($id, ':') !== false)
			{
				list($id, $user_name) = explode(':', $id, 2);
				$vars['id'] = (int) $id;
			}	
			else{
				$vars['id'] = (int) $id;
			}
		}	
		
		return $vars;
	}
}

function JoomprofileBuildRoute(&$query)
{
	$router = new JoomprofileRouter;

	return $router->build($query);
}

function JoomprofileParseRoute($segments)
{
	$router = new JoomprofileRouter;

	return $router->parse($segments);
}
