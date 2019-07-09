<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//==========Any Table data=============
if (!function_exists('get_Details')) {
    function get_Details($table = "", $id_arr = "", $noRowReturn = '', $orderby = '', $orderformat = '')
    {
        $CI =& get_instance();
        $CI->db->where($id_arr);
        if ($orderby != '' && $orderformat != '') {
            $CI->db->order_by($orderby, $orderformat);
        }
        $query = $CI->db->get($table);
        if ($noRowReturn == 'single') {
            return $query->row();
        } else {
            return $query->result();
        }
    }
}

/*------------------Get Trash News Count ---------------------*/
if (!function_exists('get_count')) {
    function get_count($table = '', $r_id = '')
    {
        $CI =& get_instance();
        $CI->db->where($r_id);
        $query = $CI->db->get($table);
        return $query->num_rows();
    }
}

/*------------------Get Sum data ---------------------*/
if (!function_exists('get_sum_data')) {
    function get_sum_data($table = '', $columname = "", $r_id = '')
    {
        $CI =& get_instance();
        $CI->db->select(' SUM(' . $columname . ') as total_sell');
        $CI->db->where($r_id);
        $query = $CI->db->get($table);
        return $query->row();
    }
}


if (!function_exists('get_data_group')) {
    function get_data_group($table = "", $r_id = "", $collum_name = "")
    {
        $CI =& get_instance();
        $CI->db->where($r_id);
        $CI->db->group_by($collum_name);
        $query = $CI->db->get($table);
        return $query->row();
    }
}



