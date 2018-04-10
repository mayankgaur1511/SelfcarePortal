<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertype_model extends CI_Model {

    function all()
    {
        $this->db->order_by('type','asc');
        return $this->db->get('userType')->result();
    }
}