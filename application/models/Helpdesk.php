<?php


class Helpdesk extends CI_Model
{
    private $table = "helpdesk";
    public function __construct()
    {
        parent::__construct();
    }

    public function getInitial($id = NULL)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where(array('parentid' => $id));

        $query = $this->db->get();
        return $query->row_array()['id'];
    }

    public function getParents($parentid)
    {
        $this->db->select('id, lable');
        $this->db->from($this->table);
        $this->db->where(array('parentid' => $parentid));

        $query = $this->db->get();
        return $query->result_array();
    }


    public function getInfo($id)
    {
        $this->db->select('id, lable');
        $this->db->from($this->table);
        $this->db->where(array('id' => $id));

        $query = $this->db->get();
        return $query->row_array();
    }
}
