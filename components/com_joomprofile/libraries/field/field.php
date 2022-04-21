<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JoomprofileLibField::$paths[] = __DIR__;
class JoomprofileLibField
{	
	const ON_SEARCH = 'search';
	const ON_VIEW 	= 'view';
	const ON_EDIT 	= 'edit';

	static $bucket = array();
	static $paths = array();
	
	public $name = '';

	protected $searchable = true;
	
	public static function load(){
		
		JoomprofileHelperJoomla::trigger('onJoomprofileFieldLoad', array(), array('joomprofile'));

		foreach (self::$paths as $path){
			foreach (JFolder::folders($path) as $folder){
				self::$bucket[$folder] = $path.'/'.$folder.'/'.$folder.'.php';
			}
		}
	}
	
	/**
	 * 
	 * Get intance of field
	 * @param $fieldname string field name
	 * @return Field Object
	 */
	public static function get($fieldname)
	{
		if(self::$bucket == array()){
			self::load();
		}
		
		static $instances = array();
		
		$classname = strtolower('JoomprofileLibField'.$fieldname);
		
		if(!isset($instances[$classname])){
			if(!class_exists($classname, true)){
				require_once self::$bucket[$fieldname];
			}
			
			$instances[$classname] = new $classname;
		}
		
		return $instances[$classname];
	}
	
	public static function getList(){
		if(self::$bucket == array()){
			self::load();
		}
		
		return self::$bucket;
	}
	
	public function getXmlPath()
	{
		return $this->location.'/'.$this->name.'.xml';
	}
	
	public function getUserEditHtml($fielddata, $value, $user_id)
	{		
		// TODO : override template
		$path 		= $this->location.'/templates';
		$template 	= new JoomprofileTemplate(array('path' => $path));
		
		$template->set('fielddata', $fielddata)
				->set('value', $value)
				->set('user_id', $user_id);
				
		return $template->render('field.'.$this->name.'.user.edit');
	}

    public function onBeforeFieldSave($data, $user_id)
    {
        return $data;
    }

	public function onAfterFieldSave($field_id, $data, $user_id)
	{
		return true;
	}
	
	public function getViewHtml($fielddata, $value, $user_id)
	{
		return $value;
	}

	public function getMiniProfileViewHtml($fielddata, $value, $user_id)
	{
		return $this->getViewHtml($fielddata, $value, $user_id);
	}

	public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
	{
		$path 		= $this->location.'/templates';
		$template 	= new JoomprofileTemplate(array('path' => $path));
		
		$template->set('fielddata', $fielddata)
				->set('value', $value)
				->set('onlyFieldHtml', $onlyFieldHtml);
				
		return $template->render('field.'.$this->name.'.search');
	}
	
	/**
	 * @return Array Arry of errors
	 */
	public function validate($field, $value, $user_id)
	{
		// some core check
		
		return $this->_validate($field, $value, $user_id);
	}
	
	protected function _validate($field, $value, $user_id)
	{
		return array();
	}

    public function loadValue($field, $value, $userid)
    {
        return $value;
    }

	public function format($field, $value, $userid, $on)
	{
		if(!is_array($value)){
			return strip_tags($value);
		}

		foreach ($value as &$val) {
			$val = strip_tags($val);
		}

		return $value;
	}
	
	public function saveOptions($field_id, $data)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$sql = "INSERT INTO `#__joomprofile_fieldoption` (`id`, `field_id`, `title`) VALUES ";
		foreach ($data as $d){
			$sql .= "(";
			$sql .= isset($d['id']) ? $db->quote($d['id']) : "''";
			$sql .= ",";
			$sql .= $field_id;
			$sql .= ",";
			$sql .= isset($d['title']) ? $db->quote($d['title']) : '""';
			$sql .= "),";
		}
		
		$sql = rtrim($sql, ',');
		$db->setQuery($sql);
		return $db->query();
	}	
	
	public function removeOptions($field_id)
	{
		$sql = 'DELETE FROM #__joomprofile_fieldoption WHERE `field_id` = '.intval($field_id);
		$db = JoomprofileHelperJoomla::getDBO();
		$db->setQuery($sql);
		return $db->query();
	}
	
	public static function getOptions($field_id = null)
	{
		static $options = null;
		
		if($options === null){
			$db = JoomprofileHelperJoomla::getDBO();
			$query = "SELECT * FROM `#__joomprofile_fieldoption` ORDER BY `title`";
			$db->setQuery($query);
			
			$results = $db->loadObjectList();
			foreach($results as $result){
				$options[$result->field_id][$result->id] = $result;
			}
		}
		
		if($field_id){
			if(isset($options[$field_id])){
				return $options[$field_id];
			}
			
			return array();
		}
		
		return $options;
	}
	
	public function buildSearchQuery($fielddata, $query, $value)
	{
		if(!is_array($value)){
			throw new Exception($fielddata->title.' : Invlaid value to search by checkbox field');
		}
		
		$db = JoomprofileHelperJoomla::getDBO();
		$sql = array();
		foreach ($value as $v){
			$sql[] = '`value` = '.$db->quote($v);
		}
		
		$sql = implode(' OR ', $sql);
		$query->where('('.$sql.')');
		return true;
	}
	
	public function getAppliedSearchHtml($fieldObj, $values)
	{
		if(is_array($values)){
			return implode(", ", $values);
		}
		
		return $values;
	}

	public function getAssets($on, $field)
	{
		// There will there type of request on
		// #1 : Edit
		// #2 : View
		// #3 : Search

		// It will return array of js & css code
		return '';
	}

	public function isValueSearchable($fielddata, $value)
	{
		return true;
	}

	public function getExportValue($fielddata, $value, $user_id)
	{
		$value = $this->getViewHtml($fielddata, $value, $user_id);
		return str_replace( array("<br />","<br>","<br/>"), "\n", $value);
	}

	public function getExportColumn($fielddata)
	{
		return array($fielddata->title);
	}

    public function showEditButton($fielddata, $user_id)
    {
        return true;
    }

    public function isSearchable()
    {
        return $this->searchable;
    }

    public function formatConfigForm($form, $data)
    {
        return $form;
    }
}
