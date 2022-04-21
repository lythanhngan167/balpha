<?php
/**
 * @version        1.0
 * @package        Joomla
 * @subpackage     OSFramework
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopModelList extends JModelLegacy
{

    /**
     * Context, using for store permanent information
     *
     * @var string
     */
    protected $context = null;

    /**
     * search fields using for searching
     *
     * @var string
     */
    protected $searchFields = null;

    /**
     *
     * @var main database table which we will query data from
     */
    protected $mainTable = null;

    /**
     * This object can be translated into different language or not
     *
     * @var Boolean
     */
    protected $translatable = false;

    /**
     * List of fields which can be translated
     * @var array
     */
    protected $translatableFields = array();

    /**
     * Total records
     *
     * @var int
     */
    protected $_total = 0;

    /**
     * Entitires data array
     *
     * @var array
     */
    protected $_data = null;

    /**
     * Pagination object
     *
     * @var object
     */
    protected $_pagination = null;

    /**
     * Constructor.
     *
     * @param
     *            array An optional associative array of configuration settings.
     *
     * @see   JController
     * @since 1.6
     */
    protected $_join = '';

    public function __construct($config = array())
    {
        parent::__construct();

        $input = JFactory::getApplication()->input;
        $mainframe = JFactory::getApplication();
        $baseStateVars = array(
            'search' => array('', 'string', 1),
            'filter_order' => array('a.id', 'cmd', 1),
            'filter_order_Dir' => array('', 'cmd', 1),
            'filter_state' => array('', 'cmd', 1));

        if (isset($config['state_vars']))
            $config['state_vars'] = array_merge($baseStateVars, $config['state_vars']);
        else
            $config['state_vars'] = $baseStateVars;

        if (isset($config['search_fields']))
            $this->searchFields = $config['search_fields'];
        else
            $this->searchFields = 'a.' . EShopInflector::singularize($this->name) . '_name';

        if (isset($config['main_table'])) {
            $this->mainTable = $config['main_table'];
        } else {
            $this->getMainTable();
        }
        if (isset($config['translatable']))
            $this->translatable = $config['translatable'];
        else
            $this->translatable = false;
        if (isset($config['translatable_fields']))
            $this->translatableFields = $config['translatable_fields'];
        else
            $this->translatableFields = array();
        if (isset($config['context'])) {
            $this->context = $config['context'];
        } else {
            $this->getContext();
        }

        if (isset($config['state_vars'])) {
            foreach ($config['state_vars'] as $name => $values) {
                $storeInSession = isset($values[2]) ? $values[2] : 0;
                $type = isset($values[1]) ? $values[1] : null;
                $default = isset($values[0]) ? $values[0] : null;
                if ($storeInSession) {
                    $value = $mainframe->getUserStateFromRequest($this->context . '.' . $name, $name, $default, $type);
                } else {
                    $value = $input->get($name, $default, 'default', $type);
                }

                $this->setState($name, $value);
            }
        }

        $fullOrdering = $mainframe->getUserStateFromRequest($this->context . '.' . 'filter_full_ordering', 'filter_full_ordering', '');

        if ($fullOrdering) {
            $parts = explode(' ', $fullOrdering);
            $sort = $parts[0];
            $direction = isset($parts[1]) ? $parts[1] : '';

            $this->setState('filter_order', $sort);
            $this->setState('filter_order_Dir', $direction);
        }

        // Get the pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust limitstart accordingly
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        //nganly
        if($_GET['template'] == 'updateprice'){
          $limit = 5000;
        }

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    /**
     * Method to get categories data
     *
     * @access public
     * @return array
     */
    public function getData()
    {
        // Lets load the content if it doesn't already exist

        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    /**
     * Get total entities
     *
     * @return int
     */
    public function getTotal()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total)) {
            $db = $this->getDbo();
            $where = $this->_buildContentWhereArray();
            $query = $db->getQuery(true);
            $query->select('COUNT(*)');
            if ($this->translatable) {
                $query->from($this->mainTable . ' AS a ')
                    ->innerJoin(EShopInflector::singularize($this->mainTable) . 'details AS b ON (a.id = b.' . EShopInflector::singularize($this->name) . '_id)');
            } else {
                $query->from($this->mainTable . ' AS a ');
            }
            if (count($where))
                $query->where($where);

            $db->setQuery($query);
            $this->_total = $db->loadResult();
        }

        return $this->_total;
    }

    /**
     * Method to get a pagination object
     *
     * @access public
     * @return integer
     */
    public function getPagination()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    /**
     * Basic build Query function.
     * The child class must override it if it is necessary
     *
     * @return string
     */
    public function _buildQuery()
    {
        $db = $this->getDbo();
        $state = $this->getState();
        $query = $db->getQuery(true);
        if ($this->translatable) {
            $query->select('a.*, ' . implode(', ', $this->translatableFields))
                ->from($this->mainTable . ' AS a ')
                ->innerJoin(EShopInflector::singularize($this->mainTable) . 'details AS b ON (a.id = b.' . EShopInflector::singularize($this->name) . '_id)');
        } else {
            $query->select('a.*')
                ->from($this->mainTable . ' AS a ');
        }
        $where = $this->_buildContentWhereArray();
        if (count($where))
            $query->where($where);
        $orderby = $this->_buildContentOrderBy();
        if ($orderby != '')
            $query->order($orderby);

        //$query->setLimit('5000');
        return $query;
    }

    /**
     *
     * Build order by clause for the select command
     * @return string order by clause
     */
    function _buildContentOrderBy()
    {
        $state = $this->getState();
        $orderby = '';
        if ($state->filter_order != '') {
            if (strpos($state->filter_order, 'a.extra') !== false) {
                $group = end(explode('.', $state->filter_order));
                $this->_join = '#__eshop_home_products AS hp ON (hp.product_id = a.id AND hp.group_id = ' . (int)$group . ')';
                $orderby = 'hp.group_id ' . $state->filter_order_Dir;
            } else {
                $orderby = $state->filter_order . ' ' . $state->filter_order_Dir;
            }
        }


        return $orderby;
    }

    /**
     * Build an where clause array
     *
     * @return array
     */
    public function _buildContentWhereArray()
    {
        $db = $this->getDbo();
        $state = $this->getState();
        $where = array();
        if ($state->filter_state == 'P')
            $where[] = ' a.published=1 ';
        elseif ($state->filter_state == 'U')
            $where[] = ' a.published = 0';

        if ($state->search) {
            $search = $db->quote('%' . $db->escape(strtolower($state->search), true) . '%', false);
            if (is_array($this->searchFields)) {
                $whereOr = array();
                foreach ($this->searchFields as $titleField) {
                    $whereOr[] = " LOWER($titleField) LIKE " . $search;
                }
                $where[] = ' (' . implode(' OR ', $whereOr) . ') ';
            } else {
                $where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->quote('%' . $db->escape(strtolower($state->search), true) . '%', false);
            }
        }

        if ($this->translatable) {
            $where[] = 'b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"';
        }

        return $where;
    }

    public function getContext()
    {
        if (empty($this->context)) {
            $r = null;
            if (preg_match('/(.*)Model/i', get_class($this), $r)) {
                $component = $r[1];
                $this->context = $component . '.' . $this->getName();
            }
        }

        return $this->context;
    }

    /**
     * Get name of database table use for query
     *
     * @return string The main database table
     */
    public function getMainTable()
    {
        $db = $this->getDbo();
        if (empty($this->mainTable)) {
            $this->mainTable = $db->getPrefix() . strtolower(ESHOP_TABLE_PREFIX . '_' . $this->getName());
        }

        return $this->mainTable;
    }
}
