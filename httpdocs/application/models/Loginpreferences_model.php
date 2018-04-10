<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loginpreferences_model extends CI_Model {

    function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('loginPreferences')->row_object();
    }

    function find($where)
    {
        $this->db->where($where);
        return $this->db->get('loginPreferences')->row_object();
    }

    function add($params)
    {
        $this->db->insert('loginPreferences',$params);
        return $this->db->insert_id();
    }

    function update($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('loginPreferences',$params);
    }
    
}