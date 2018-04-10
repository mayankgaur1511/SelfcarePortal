<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Countrymaster_model extends CI_Model {

    function all()
    {
        $this->db->order_by('countryName','asc');
        return  assoc_by('id', $this->db->get('countryMaster')->result());
    }

    function get($id)
    {
        $this->db->where('id',$id);
        $this->db->order_by('countryCode','asc');
        return $this->db->get('countryMaster')->row_object();
       
    }

    function find($where)
    {
        $this->db->where($where);
        return $this->db->get('countryMaster')->row_object();
       
    }

    function allByCode()
    {
        $this->db->order_by('countryCode','asc');
        return  assoc_by('id', $this->db->get('countryMaster')->result());
       
    }

   
}