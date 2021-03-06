<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class CommunityModelPollCategories extends JModelLegacy
{
	/**
	 * Configuration data
	 *
	 * @var object	JPagination object
	 **/
	var $_pagination;

	/**
	 * Configuration data
	 *
	 * @var int	Total number of rows
	 **/
	var $_total;

	/**
	 * Configuration data
	 *
	 * @var int	Total number of rows
	 **/
	var $_data;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$mainframe	= JFactory::getApplication();

		// Call the parents constructor
		parent::__construct();

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$limitstart	= $mainframe->getUserStateFromRequest('com_community.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get a pagination object for the events
	 *
	 * @access public
	 * @return integer
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->_pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to return the total number of rows
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Load total number of rows
		if( empty($this->_total) )
		{
			$this->_total	= $this->_getListCount($this->_buildQuery());
		}

		return $this->_total;
	}

	/**
	 * Build the SQL query string
	 *
	 * @access	private
	 * @return	string	SQL Query string
	 */
	public function _buildQuery()
	{
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$ordering = $mainframe->getUserStateFromRequest('com_community.filter_order', 'filter_order', 'name', 'cmd');
		$orderDirection	= $mainframe->getUserStateFromRequest('com_community.filter_order_Dir',	'filter_order_Dir',	'',	'word');

		switch ($ordering) {
			case 'id':
				$orderby = ' ORDER BY a.id '. $orderDirection;
				break;
			default:
				$orderby = ' ORDER BY name '. $orderDirection;
				break;
		}

		$query	= 'SELECT a.*'
				. ' FROM '
				. $db->quoteName( '#__community_polls_category' ) . ' AS a '
				. 'GROUP BY a.id';
		$query	.= $orderby;
		return $query;
	}

	/**
	 * Returns the Groups Categories list
	 *
	 * @return Array An array of group category objects
	 **/
	public function &getCategories($limit = true)
	{
		$mainframe	= JFactory::getApplication();
        $jinput = $mainframe->input;

		if (empty($this->_data)) {
			$view = $jinput->get('view');

			if (is_null($view)) {
				$limit = 0;
			} else {
				$limit = $this->getState('limit');
			}

			$query = $this->_buildQuery();

			if ($limit) $this->_data = $this->_getList($this->_buildQuery(), $this->getState('limitstart'), $limit);
			else $this->_data = $this->_getList($this->_buildQuery());
		}
		return $this->_data;
	}

    /**
     * Given an id, check all the descendant of the category based on the given list
     * @param $id
     * @param $categories
     * @param array $list
     * @return array
     */
    public function getCategoryChilds($id, $categories, $list = array()){
        foreach ($categories as $category) {
            if ($category->parent == $id) {
                $list[] = $category->id;
                $list = $this->getCategoryChilds($category->id, $categories, $list);
            }
        }

        return $list;
    }
}