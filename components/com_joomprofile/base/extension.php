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
 * This class will hadle all kind of communitcation from extensions
 * @author function90.com
 */

class JoomprofileExtension
{
	protected $db;
	protected $input;
	
	protected $default_controller 	= 'index';
	protected $default_task 		= 'index';
	
	static $bucket = array();
	static $paths = array();
	
	public $name = '';
	
	private static function loadBucket(){		
		foreach (self::$paths as $path){
			foreach (JFolder::folders($path) as $folder){
				self::$bucket[$folder] = $path.'/'.$folder.'/autoload.php';
			}
		}
	}
	
	final public static function get($extension_name, $config = array()){
		if(self::$bucket == array()){
			self::$paths[] = JPATH_SITE.'/components/com_joomprofile/extensions';
			self::loadBucket();
		}
		
		static $instances = array();
		
		$classname = strtolower('Joomprofile'.$extension_name);
		
		if(!isset($instances[$classname])){
			if(!class_exists('Joomprofile'.$extension_name, true)){
				require_once self::$bucket[$extension_name];
			}
			
			$instances[$classname] = new $classname;
		}
		
		return $instances[$classname];
	}
	
	public function __construct()
	{
		$this->japp  = JFactory::getApplication();
		$this->input = $this->japp->input;		
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function execute($task){
		// explode tast by DOT (.)
		// first part will be controller 
		// second part will be its task to be executed
		// if it does not have DOT(.) then get the default controller and execute the taks
		// if task is empty then get default controller and default task to execute
		
		$task_parts = explode('.', $task);
		if(count($task_parts) > 1){
			$controller = $task_parts[0];
			$task 		= $task_parts[1];
		}
		elseif(count($task_parts) > 0){
			$controller = $this->default_controller;
			$task 		= $task_parts[0];
		}
		else{
			$controller = $this->default_controller;
			$task 		= $this->default_task;
		}
		
		$config = array();
		$config['input'] = $this->input;
		$controller_instance = $this->getController($controller, $config);
		$result = $controller_instance->$task();
		
		// if result is true then need to load its view, otherwise redirect to set $redirect_url
		if($result === false){
			$this->japp->redirect(JRoute::_($controller_instance->redirect_url, false), $controller_instance->msg, $controller_instance->msg_type);
			return false;
		}
		
		// @TODO : ask from controller to get view
		$format = $this->input->get('format', 'html');
		$view = $controller_instance->get_view($controller, $format, $config);
		
		// get joomla app
		$app = 'Site';
		if($this->japp->isAdmin()){
			$app = 'Admin';
		}
		
		$view->triggered = self::trigger('onJoomprofile'.$app.'ViewBeforeRender', array($this->name, $view));
		$setup = $view->setupScript();
		return $setup.$view->$task();
	}
	
	public function getPrefix(){
		return "Joomprofile".$this->name;
	} 
	
	public function getController($c_name, $config = array()){
		$class_name = strtolower($this->getPrefix().'Controller'.$c_name);
		
		static $controllers = array();
		if(!isset($controllers[$class_name])){
			if(!class_exists($class_name, true)){
				throw new Exception(JText::sprintf('JOOMPROFILE_EXTENSION_CONTROLLER_NOT_FOUND', $c_name, $this->name));
			}

			$controllers[$class_name] = new $class_name($config);
			$controllers[$class_name]->app = $this;
		}
		
		return $controllers[$class_name];
	}
	
	public function getModel($m_name, $config = array()){
		$class_name = strtolower($this->getPrefix().'Model'.$m_name);
		
		static $models = array();
		if(!isset($models[$class_name])){
			if(!class_exists($class_name, true)){
				throw new Exception(JText::sprintf('JOOMPROFILE_EXTENSION_MODEL_NOT_FOUND', $c_name, $this->name));
			}

			$models[$class_name] = new $class_name($config);
		}
		
		return $models[$class_name];
	}
	
	public function getTemplate($config = array())
	{
		$config = array_merge(array('app'=>$this), $config);
		$template = new JoomprofileTemplate($config);
		return $template;
	}
	
	final public static function trigger($eventname, $args, $extensions = array())
	{
		if(self::$bucket == array()){
			self::load_bucket();
		}

		$apps = array_keys(self::$bucket);
		if(!empty($extensions)){
			$apps = array_intersect($extensions, $apps);
		}
		
		$return = array();
		foreach($apps as $app){
			$app_instance = self::get($app);
			if(method_exists($app_instance, $eventname)){
				$return[] = call_user_func_array(array($app_instance, $eventname), $args);
			}
		}
		
		return $return;		
	}
	
	public function getObject($name, $prefix, $itemid, $config = array(), $bind = array())
	{
		$config['app'] = $this;
		return JoomprofileLib::getObject($name, $prefix, $itemid, $config, $bind);
	}
}