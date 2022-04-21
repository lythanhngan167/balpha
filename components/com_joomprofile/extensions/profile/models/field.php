<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelField extends JoomprofileModel
{	
	protected $_name = 'field';
	
	protected function _buildFilterQuery($query)
	{
		$search = $this->getState('filter.search');
		if(!empty($search)){
			$query->where('`title` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR')
					->where('`tooltip` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR');
		}
		
		return true;
	}

    /**
     * Get searchable field list
     * @param null $query
     * @param null $indexed_by
     * @return mixed
     */
	public function getSearchableList($query = null, $indexed_by = null){
	    if($query === null){
	        $query = $this->getQuery();
	    }

	    $this->_db->setQuery($query->__toString());
	    //TODO : Error handling
	    if($indexed_by === null){
	        $indexed_by = $this->_key;
	    }

	    $fields = $this->_db->loadObjectList($indexed_by);

	    foreach ($fields as $field){
	        $fieldInstance = JoomprofileLibField::get($field->type);

	        if($fieldInstance->isSearchable() === false){
	            unset($fields[$field->id]);
	        }
	    }

	    return $fields;
	}
}

class JoomprofileProfileModelformField extends JoomprofileModelform
{
	protected $_name 		= 'field';	
	protected $_location 	= __DIR__;
}