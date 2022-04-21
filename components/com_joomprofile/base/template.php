<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileTemplate{
	
	protected $data = null;
	protected $paths = array();
	protected $app  = null;
	
	public function __construct($config = array()){
		$this->data = new stdClass();
		
		if (isset($config['app'])){
			$this->app = $config['app'];
		}
		
		$this->getTemplatePath();
		
		if(isset($config['path'])){
			$path = $config['path'];
			
			if(!is_array($path)){
				$path = array($path); 
			}
			
			$this->paths = array_merge($this->paths, $path);
		}
	}
	
	public function get($name, $default = ''){
		if(isset($this->data->$name)){
			return $this->data->$name;
		}
		
		return $default;
	}
	
	public function set($key, $value){
		$this->data->$key = $value;
		return $this;
	}
	
	/**
	 * Template name to be rendered
	 * Name should be as app_name.extension_name.controller.task : like admin.cpanel.index.index
	 * 
	 * @param unknown_type $tpl
	 * @throws Exception
	 */
	public function render($tpl){
		$filename 	= $tpl.'.php';		
		$found 		= false;

		foreach($this->paths as $path){
			$path 		= rtrim($path, '/');
			$filepath 	= $path.'/'.$filename;
			
			if(file_exists($filepath)){
				$data = $this->data;
				$doc  = JFactory::getDocument();
				ob_start();
				include $filepath;
				$content = ob_get_contents();
				ob_end_clean();
				
				return $content;
			}
		}
		
		throw new Exception(JText::sprintf('JOOMPROFILE_EXTENSION_TEMPLATE_NOT_FOUND', $tpl));
	}
	
	function getTemplatePath()
	{
		$japp = JoomprofileHelperJoomla::getApplication();
		$jTemplate = $japp->getTemplate();
		
		$paths = array();
		$paths[] = JPATH_BASE.'/templates/'.$jTemplate.'/html/com_joomprofile';

//        $componentTemplatePath 		= JPATH_COMPONENT.'/com_joomprofile/templates';
		
		// Add the path only if the tempaltes is asked from any extension of Joom Profile
		// otherwise do nothing
		// $this->aap will have the instance of extension of Joom Profile
		if($this->app){
        	$extension = $this->app->getName();
        	$paths[] = JPATH_COMPONENT_SITE.'/extensions/'.$extension.'/templates';
		}		
        
        $this->paths  = $paths;
        
        return $this->paths;
	}
	
}