<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

abstract class JoomprofileLib
{
	public static $objects = array();
	
	/**
	 * @var JoomprofileExtension
	 */
	public $_app;
	
	protected $_prefix 	= '';
	protected $_name 	= '';
	
	// object can not be created
	protected function __construct($config = array())
	{
		if(isset($config['app'])){
			$this->_app = $config['app'];
		}
	}
	
	public function getModel($name = '')
	{
		if(empty($name)){
			$name = $this->_name;
		}
		
		return $this->_app->getModel($name);
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	public static function getObject($name, $prefix, $id = 0, $config = array(), $bind = array())
	{		
		$prefix = strtolower($prefix);
		$name 	= strtolower($name);
		
		$classname = $prefix.'lib'.$name;
		
		// if object already exists
		if($id && isset(self::$objects[$classname][$id])){
			return self::$objects[$classname][$id];
		}
		
		$object = new $classname($config);
		$object->_name 		= $name;
		$object->_prefix 	= $prefix;
		
		if(!$id){
			return $object;
		}
		
		// load the object if id is not set on object
		if(!empty($bind)){
			$object->bind($bind);
		}
		else{
			$object->load($id);
		}
		
		self::$objects[$classname][$id] = $object;
		
		return self::$objects[$classname][$id];
	}
	
	public function load($id)
	{
		$model = $this->getModel();
		$data = $model->getItem($id);
		
		return $this->bind($data);
	}
	
	public function bind($binddata)
	{
		$binddata = is_array($binddata) ? (object) $binddata : $binddata;
		
		$members = get_object_vars($this);
		
		foreach($members as $key => $value){
			if(preg_match('/^_/',$key) || !isset($binddata->$key)){
				continue;
			}
			
			if(!isset($binddata->$key)){
				continue;
			}
			
			if(is_string($binddata->$key) && is_object($this->$key) && method_exists($this->$key, 'loadString')){
				$this->$key->loadString($binddata->$key);
				continue;
			}
			
			if(is_array($binddata->$key) && is_object($this->$key) && method_exists($this->$key, 'loadArray')){
				$this->$key->loadArray($binddata->$key);
				continue;
			}
			
			$this->$key = $binddata->$key;
		}
		
		return $this;
	}
	
	public function toDatabase()
	{
		$data = array(); 		
		$members = get_object_vars($this);
		
		foreach($members as $key => $value){
			if(preg_match('/^_/',$key)){
				continue;
			}
			
			if(is_object($this->$key) && method_exists($this->$key, 'toDatabase')){
				$data[$key] = $this->$key->toDatabase();
				continue;
			}
			
			if(is_object($this->$key) && method_exists($this->$key, 'toString')){
				$data[$key] = $this->$key->toString();
				continue;
			}
			
			$data[$key] = $value;
		}
		
		return $data;
	}
	
	public function toArray()
	{
		$data = array(); 		
		$members = get_object_vars($this);
		
		foreach($members as $key => $value){
			if(preg_match('/^_/',$key)){
				continue;
			}
			
			if(is_object($this->$key) && method_exists($this->$key, 'toArray')){
				$data[$key] = $this->$key->toArray();
				continue;
			}
			
			$data[$key] = $value;
		}
		
		return $data;
	}
	
	public function toObject()
	{
		return (object) $this->toArray();
	}
	
	public function save()
	{
		$data = $this->toDatabase();
		$id = $this->getModel()->save($this->getId(), $data);
		
		if(!$id){
			return false;
		}
		
		$this->setId($id);
		
		$classname = strtolower(get_class($this));
		if(!isset(self::$objects[$classname][$id])){
			self::$objects[$classname][$id] = $this;
		}
		
		return $this;
	}
	
	public function getParams()
	{
		return $this->params->toObject();
	}
}