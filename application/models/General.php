<?php

defined('BASEPATH') or exit('No direct script access allowed');

class General extends CI_Model
{
    private $table = "center_list";

    public function __construct()
    {
        parent::__construct();
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



    public function add_data($tableName, $data)
    {
        $this->db->insert($tableName, $data);
        return $this->db->insert_id();
    }

    public function modify($tableName, $colName, $id, $data)
    {
        $this->db->where($colName, $id);
        $result = $this->db->update($tableName, $data);
        return $result;
    }

	function delete($tab_name, $tab_where)
	{
        $result = $this->db->delete($tab_name, $tab_where);
        return $result;
		// select * from registration limit 0,10
	}
}
