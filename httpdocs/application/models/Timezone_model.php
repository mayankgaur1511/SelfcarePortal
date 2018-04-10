<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timezone_model extends CI_Model {

    function get_all()
    {
        $this->db->order_by('id','asc');
        return  assoc_by('id', $this->db->get('timeZone')->result());
    }

    function get($id)
    {
        $this->db->where('id',$id);
        return  $this->db->get('timeZone')->row_object();
    }

    function find($where)
    {
        $this->db->where($where);
        return  $this->db->get('timeZone')->row_object();
    }
}