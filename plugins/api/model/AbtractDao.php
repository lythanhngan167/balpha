<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 11:01 AM
 */

namespace api\model;


abstract class AbtractDao
{
    public $db;

    abstract function getTable();

    public function __construct()
    {
        $this->db = \JFactory::getDbo();
    }

    private function _buildQuery($params = array())
    {
        $params['as'] = @$params['as'] ? $params['as'] : null;
        $query = $this->db->getQuery(true);
        if (@$params['table']) {
            $query->from($this->db->quoteName($params['table'], $params['as']));
        } else {
            $query->from($this->db->quoteName($this->getTable(), $params['as']));
        }
        if (@$params['no_quote'] == true) {
            $query->select($params['select']);
        } elseif ($params['select']) {
            $query->select($this->db->quoteName($params['select']));
        } elseif (@$params['is_count']) {
            $query->select('count(*) as num');
        } else {
            $query->select('*');
        }
        if (@$params['join']) {
            foreach ($params['join'] as $item) {
                $query->join($item['type'], $item['with_table']);
            }
        }

        if (@$params['where']) {
            $first = array_shift($params['where']);
            $query->where($first);
            if ($params['where']) {
                foreach ($params['where'] as $item) {
                    $query->andWhere($item);
                }

            }
        }
        if (@$params['order']) {
            $query->order($params['order']);
        }
        if (@$params['group']) {
            $query->group($params['group']);
        }

        return $query;
    }

    public function getList($params = array())
    {
        $query = $this->_buildQuery($params);
        //echo $query->__toString();
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        $limit = isset($params['limit']) ? $params['limit'] : 20;
        $this->db->setQuery($query, $offset, (int)$limit);
        return $this->db->loadAssocList();
    }

    public function get($params = array())
    {
        $query = $this->_buildQuery($params);
        //echo $query->__toString();
        $this->db->setQuery($query);
        return $this->db->loadAssoc();

    }

    public function getListBySql($sql)
    {
        $this->db->setQuery($sql);
        return $this->db->loadAssocList();
    }

    public function insert($object)
    {
        $this->db->insertObject($this->getTable(), $object, 'id');
        return $object;
    }

    public function update($params = array())
    {
        $table = isset($params['table']) ? $params['table'] : $this->getTable();
        $sql = 'UPDATE ' . $table
            . ' SET ' . implode(' , ', $params['set'])
            . ' WHERE ' . implode(' AND ', $params['where']);
        $this->db->setQuery($sql);
        return $this->db->execute();
    }

}
