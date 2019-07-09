<?php

class Userlog extends CI_Model
{
    private $table = "userlog";
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
