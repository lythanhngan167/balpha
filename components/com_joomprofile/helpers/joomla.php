<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

abstract class JoomprofileHelperJoomla
{	
	public static function getUserObject($user_id = 0)
	{
		if(!$user_id ){
			return JFactory::getUser();
		}
		return JFactory::getUser($user_id);
	}
	
	public static function getUser($filter, $what = '*')
	{
		//@TODO : apply caching
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select($what)
				->from('#__users');
				
		foreach($filter as $filter){
			$query->where($filter);
		}
				
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	public static function getUsergroups()
	{
		static $groups = array();
		
		if(empty($groups)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__usergroups'));
			$db->setQuery($query);
			$groups = $db->loadObjectList('id');
		}
		
		return $groups;
	}
	
	public static function isUserLoggedIn($userid, $client_id = 0)
	{
		static $loggedinUsers = array();
		
		if(empty($loggedinUsers)){
			$db    = JFactory::getDbo();
			
			$query = $db->getQuery(true)
				->select('s.time, s.client_id, u.id, u.name, u.username')
				->from('#__session AS s')
				->join('LEFT', '#__users AS u ON s.userid = u.id')
				->where('s.guest = 0')
				->where('s.client_id = '.$client_id);
			$db->setQuery($query);
			$loggedinUsers = $db->loadObjectList('id');
		}
		
		if(isset($loggedinUsers[$userid])){
			return true;
		}
		
		return false;
	}		
	
	public static function getMenu(Array $filters)
	{
		if(empty($filters)){
			return false;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		//export tags
		$query->select("*")
			  ->from("`#__menu`");
			  
		foreach ($filters as $filter){
			$query->where($filter);
		}			  

		$db->setQuery($query);		
		return $db->loadObject();
	}
	
	public static function getApplication()
	{
		return JFactory::getApplication();
	}
	
	public static function getDBO()
	{
		return JFactory::getDbo();
	}
	
	public static function getSession()
	{
		return JFactory::getSession();
	}

	public static function trigger($eventName, $args = array(), $types = array())
	{
		// Trigger event before saving user
		$dispatcher = JEventDispatcher::getInstance();
		
		// Include the plugins of $type
		foreach($types as $type){			
			JPluginHelper::importPlugin($type);
		}
			
		try
		{
			// Trigger the event.
			$results = $dispatcher->trigger($eventName, $args);

			// Check the returned results. This is for plugins that don't throw
			// exceptions when they encounter serious errors.
			if (in_array(false, $results))
			{
				throw new Exception($dispatcher->getError(), 500);
			}
		}
		catch (Exception $e)
		{
			// Handle a caught exception.
			throw $e;
		}

		return true;
	}

	static public function nl2br( $text )
	{
		$text	= str_ireplace(array("\r\n", "\r", "\n"), "<br />", $text );
		return preg_replace("/(<br\s*\/?>\s*){3,}/", "<br /><br />", $text);
	}

	static public function escape($var, $function='htmlspecialchars')
	{
		$disabledFunctions = array ('eval');
		if ( !in_array ($function, $disabledFunctions) ) {
			if (in_array($function, array('htmlspecialchars', 'htmlentities')))
   			{
				return call_user_func($function, $var, ENT_COMPAT, 'UTF-8');
   			}
			return call_user_func($function, $var);
		}
	}
}