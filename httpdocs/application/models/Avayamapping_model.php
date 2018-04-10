<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Avayamapping_model extends CI_Model {

    function get($bridge)
    {
        $this->db->where('bridge',$bridge);
        return $this->db->get('AvayaWapiMapping')->row_object();
    }
    
}