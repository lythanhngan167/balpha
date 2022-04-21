<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Order
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Order records.
 *
 * @since  1.6
 */
class OrderModelReports extends JModelList
{
    /**
        * Constructor.
        *
        * @param   array  $config  An optional associative array of configuration settings.
        *
        * @see        JController
        * @since      1.6
        */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.`id`',
                'category_id', 'a.`category_id`',
                'quantity', 'a.`quantity`',
                'price', 'a.`price`',
                'total', 'a.`total`',
                'project_id', 'a.`project_id`',
                'ordering', 'a.`ordering`',
                'state', 'a.`state`',
                'created_by', 'a.`created_by`',
                'modified_by', 'a.`modified_by`',
                'create_date', 'a.`create_date`',
                'modified_date', 'a.`modified_date`',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   Elements order
     * @param   string  $direction  Order direction
     *
     * @return void
     *
     * @throws Exception
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $sale_id = $app->getUserStateFromRequest($this->context . '.filter.sale_id', 'filter_sale_id', '', 'string');
        $this->setState('filter.sale_id', $sale_id);

        $category_id = $app->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '', 'string');
        $this->setState('filter.category_id', $category_id);

        // Filtering create_date
        
        $this->setState('filter.buy_date.from', $app->getUserStateFromRequest($this->context.'.filter.buy_date.from', 'filter_buy_date_from', '', 'string'));
        $this->setState('filter.buy_date.to', $app->getUserStateFromRequest($this->context.'.filter.buy_date.to', 'filter_buy_date_to', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_order');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return   string A store id.
     *
     * @since    1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return   JDatabaseQuery
     *
     * @since    1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db    = $this->getDbo();
        $query = $db->getQuery(true);
        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'DISTINCT a.*'
            )
        );
        $query->from('`#__customers` AS a');

        // Filter by sale id
        $sale_id = $this->state->get('filter.sale_id');

        if (is_numeric($sale_id)) {
            $query->where('a.sale_id = ' . (int) $sale_id);
        }

        // Filter by category id
        $status = $this->state->get('filter.status');

        if (is_numeric($status)) {
            switch ($status) {
                case 2:
                $query->where('a.category_id = 150');
                break;
                case 3:
                $query->where('a.category_id = 150');
                $query->where('a.payback = 0');
                break;
                case 4:
                $query->where('a.category_id = 150');
                $query->where('a.payback = 1');
                break;
            }
        }


        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(a.name like '.$search.' OR a.phone like '.$search.')');
            }
        }

        

        // Filtering create_date
        $filter_buy_date_from = $this->state->get("filter.buy_date.from");

        if ($filter_buy_date_from !== null && !empty($filter_buy_date_from)) {
            $query->where("a.`buy_date` >= '".$db->escape($filter_buy_date_from)."'");
        }
        $filter_buy_date_to = $this->state->get("filter.buy_date.to");

        if ($filter_buy_date_to !== null  && !empty($filter_buy_date_to)) {
            $query->where("a.`buy_date` <= '".$db->escape($filter_buy_date_to)."'");
        }

        // Filtering modified_date
        //$filter_modified_date_from = $this->state->get("filter.modified_date.from");

        // if ($filter_modified_date_from !== null && !empty($filter_modified_date_from))
        // {
        // 	$query->where("a.`modified_date` >= '".$db->escape($filter_modified_date_from)."'");
        // }
        // $filter_modified_date_to = $this->state->get("filter.modified_date.to");

        // if ($filter_modified_date_to !== null  && !empty($filter_modified_date_to))
        // {
        // 	$query->where("a.`modified_date` <= '".$db->escape($filter_modified_date_to)."'");
        // }
        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        $fullordering = $this->state->get('list.fullordering');
        echo $fullordering;
        if ($fullordering) {
            $query->order($db->escape($fullordering));
        } elseif ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }
        //echo $query;
        return $query;
    }

    /**
     * Get an array of data items
     *
     * @return mixed Array of data items on success, false on failure.
     */
    public function getItems()
    {
        $items = parent::getItems();


        return $items;
    }
}
