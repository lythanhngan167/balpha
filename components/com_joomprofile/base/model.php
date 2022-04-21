<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileModel
{
	protected 	$_name 		= '';
	protected 	$_prefix	= '';
	protected 	$_db 		= null;
	protected 	$_key 		= 'id';
	protected 	$_table 	= null;
	protected 	$_tablename	= null;
	protected 	$_modelform	= null;
	protected 	$_namepsace = "Joomprofile";
	
	/**
	 * Indicates if the internal state has been set
	 *
	 * @var    boolean
	 * @since  12.2
	 */
	protected $__state_set = null;
	
	
	/**
	 * A state object
	 *
	 * @var    string
	 */
	protected $state;
	
	/**
	 * 
	 * @var JInput
	 */
	protected $input;

	protected $_default_order_col = 'id';
	protected $_default_order_in = 'ASC';
	
	protected $_filters = array('search');
	
	public function __construct($config = array()){
		$this->_db 			= JFactory::getDbo();
		$this->_tablename 	= $this->getTableName();
		$this->_table 		= $this->getTable();
		
		// Guess the context as Option.ModelName.
		if (empty($this->context))
		{
			$this->context = strtolower('joomprofile.' . $this->_name);
		}
		
		if(isset($config['input'])){
			$this->input = $config['input'];
		}
		
		$this->state = new JObject;
	}
	
	public function getPrefix()
	{
		if (empty($this->_prefix))
		{
			$r = null;
			if (!preg_match('/(.*)Model/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_MODEL_GET_PREFIX'), 500);
			}
			$this->_prefix = strtolower($r[1]);
		}

		return $this->_prefix;
	}
	
	public function getName()
	{
		if (empty($this->_name))
		{
			$r = null;
			if (!preg_match('/Model(.*)/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_MODEL_GET_NAME'), 500);
			}
			$this->_name = strtolower($r[1]);
		}

		return $this->_name;
	}
	
	public function getTableName()
	{
		if(empty($this->_tablename)){
			$this->_tablename = '#__joomprofile_'.$this->getName();
		}
			
		return $this->_tablename; 
	}
	
	public function getTable(){
		if($this->_table === null){
			$key_name		= $this->_key;
			
			$classname = $this->getPrefix().'Table'.$this->getName();
			$this->_table = new $classname($this->_tablename, $key_name, $this->_db);
		}
		
		return $this->_table;
	}
	
	public function getModelform(){
		if($this->_modelform === null){			
			$classname = $this->getPrefix().'Modelform'.$this->getName();
			$this->_modelform = new $classname();
		}
		
		return $this->_modelform;
	}
	
	public function save($itemid, $data){
		
		if($itemid){
			$data[$this->_key] = $itemid;
		}
		
		$binddata = array();
		foreach ($data as $key => $value){
			if(is_array($value)){
				$value = json_encode($value);
			}
			
			$binddata[$key] = $value; 
		}
		
		if($this->_table->save($binddata)){
			return $this->_table->{$this->_key};
		}
		
		return false;
	}
	
	public function getItem($itemid){
		if ($itemid)
		{
			// Attempt to load the row.
			$return = $this->_table->load($itemid);

			// Check for a table object error.
			if ($return === false && $this->_table->getError())
			{
				throw new Exception($this->_table->getError());
				return false;
			}
		}
		
		// Convert to the JObject before adding other data.
		$item = (object)$this->_table->getProperties(1);
	//	if (property_exists($item, 'params'))
	//	{
	//		$registry = new JRegistry;
	//		$registry->loadString($item->params);
	//		$item->params = $registry->toArray();
	//	}

		return $item;		
	}
	
	public function getQuery(){
		$db = $this->_db;
		$query = $db->getQuery(true);
		$query->select('*')
				->from($db->quoteName($this->_tablename).' AS `tbl`');
		if($this->_key){
			$query->order($this->_key);
		}
		return $query;
	}
	
	public function getList($query = null, $indexed_by = null){
		if($query === null){
			$query = $this->getQuery();
		}
		
		$this->_db->setQuery($query->__toString());
		//TODO : Error handling
		if($indexed_by === null){
			$indexed_by = $this->_key;
		}
		
		return $this->_db->loadObjectList($indexed_by);
	}
	
	protected function _buildFilterQuery($query)
	{
		return true;
	}
	
	public function getGridItemList($query = null){
		if($query === null){
			$query = $this->getQuery();
		}
		
		$this->_buildFilterQuery($query);
		
		$query->clear('order');
		$ordering = ' ORDER BY `'.$this->getState('list.ordering').'` '.$this->getState('list.direction');
		
		$this->_db->setQuery($query->__toString().$ordering, $this->getStart(), $this->getState('list.limit'));
		
		$indexed_by = $this->_key;

		$result = $this->_db->loadObjectList($indexed_by);
		return $result;
	}
	
	public function getForm($data)
    {
    	if(empty($data)){
    		$data = array();
    	}
    	// setup modelform
        $modelform = $this->getModelform();
        $modelform->setData($data);
                        
        return $modelform->getForm();
     }    

     public function remove($filters, $glue = 'AND')
     {
     	if(!is_array($filters)){
     		$filters = array($filters);
     	}
     	
     	$query = $this->_db->getQuery(true);
		$query->delete()
			->from($this->_tablename);
			
		foreach($filters as $filter){
			$query->where($filter, $glue);
		}
		
		$this->_db->setQuery($query->__toString());
		return $this->_db->query();
     	
     }
     
     public function getTotal()
     {
     	$query = $this->getQuery();
     	$query->clear('select')->select('count(*)');
     	$this->_buildFilterQuery($query);     	
     	$this->_db->setQuery($query->__toString());
		return $this->_db->loadResult();
     }
     
     public function getPagination()
     {
     	$page = new JPagination($this->getTotal(), $this->getStart(), $this->getState('list.limit'));
     	return $page;
     }
     
	public function setState($property, $value = null)
	{
		return $this->state->set($property, $value);
	}
	
	public function getState($property = null, $default = null)
	{
		if (!$this->__state_set)
		{
			// Protected method to auto-populate the model state.
			$this->populateState();

			// Set the model state set flag to true.
			$this->__state_set = true;
		}

		return $property === null ? $this->state : $this->state->get($property, $default);
	}
	
	public function getStart()
	{
		$start = $this->getState('list.start');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}
		
		return  $start;
	}

	protected function populateState()
	{
		$app = JFactory::getApplication();

		// set limit
		$limitstart = $this->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limit 		= $this->getUserStateFromRequest($this->context . '.limit', 'limit', 20);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$ordering = $this->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $this->_default_order_col);
		$direction = $this->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $this->_default_order_in);
		
		foreach($this->_filters as $filter){
			$state = $this->getUserStateFromRequest($this->context . '.filter.'.$filter, 'filter_'.$filter, '', 'string');
			$this->setState('filter.'.$filter, $state);
		}
		
		$this->setState('list.direction', $direction);
		$this->setState('list.ordering', $ordering);
		$this->setState('list.start', $limitstart);
		$this->setState('list.limit', $limit);
	}
	
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app = JFactory::getApplication();
		$input     = $app->input;
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = $input->get($request, null, $type);

		if (($cur_state != $new_state) && ($resetPage))
		{
			$input->set('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null)
		{
			$app->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}
}