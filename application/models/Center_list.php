<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Center_list extends CI_Model
{
    private $table = "center_list";

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id = "")
    {
        $this->db->select('*');
        $this->db->from($this->table);
        //$this->db->where('status',0);
        if ($id != '') {
            $arr = array('id' => $id);
            $this->db->where($arr);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getcActive($id = "")
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('status', 0);
        if ($id != '') {
            $arr = array('id' => $id);
            $this->db->where($arr);
        }

        $query = $this->db->get();
        return $query->result_array();
    }


    public function add_login($tableName, $data)
    {
        return $this->db->insert($tableName, $data);
    }

    public function modify($tableName, $colName, $id, $data)
    {
        $this->db->where($colName, $id);
        $result = $this->db->update($tableName, $data);
        return $result;
    }


    public function getSingleRowById($tableName, $colName, $id, $returnType = '')
    {
        $this->db->where($colName, $id);
        $result = $this->db->get($tableName);
        if ($result->num_rows() > 0) {
            if ($returnType == 'array')

                return $result->row_array();
            else

                return $result->row();
        } else
            return FALSE;
    }


    function check_unique_center_name($center_name, $id)
    {
        $this->db->where('center_name', $center_name);
        $this->db->where_not_in('id', $id);
        return $this->db->get('center_list')->num_rows();
    }

    public function getlocation()
    {
        $sql = "SELECT DISTINCT(state_name)  FROM location where country_name='India' ORDER BY state_name ASC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getRowByWhere($tableName, $filters = '', $select = '', $noRowReturn = '', $returnType = '', $orderby = '', $orderformat = 'asc', $perPage = '', $start = '', $or_filters = array())
    {

        if ($select != '')
            $this->db->select($select);

        if (count($filters) > 0) {
            foreach ($filters as $field => $value)
                $this->db->where($field, $value);
        }

        if (count($or_filters) > 0) {
            $this->db->or_where($or_filters);
        }

        if ($perPage != '' && $start != '') {
            $this->db->limit($perPage, $start);
        }

        if ($orderby != '')
            $this->db->order_by($orderby, $orderformat);
        $result = $this->db->get($tableName);

        if ($result->num_rows() > 0) {
            if ($noRowReturn == 'single') {
                if ($returnType == 'array')
                    return $result->row_array();
                else
                    return $result->row();
            } else {
                if ($returnType == 'array')
                    return $result->result_array();
                else
                    return $result->result();
            }
        } else
            return FALSE;
    }
}
