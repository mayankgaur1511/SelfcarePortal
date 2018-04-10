<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiooptionsmapping_model extends CI_Model {

    function get($spirit)
    {
        $this->db->where('spirit',$spirit);
        return $this->db->get('AudioOptionSet_mapping')->row_object();
    }

    function all()
    {
        $this->db->where("wapit is not null");
        return $this->db->get('AudioOptionSet_mapping')->result();
    }
   
}