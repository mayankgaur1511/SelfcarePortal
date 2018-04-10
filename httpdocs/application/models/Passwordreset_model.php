<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passwordreset_model extends CI_Model {

    function get($token, $login)
    {
        $this->db->where('token', $token);
        $this->db->where('login', $login);
        return $this->db->get('passwordReset')->row_object();
    }

    function add($params)
    {
        $this->db->insert('passwordReset',$params);
        return $this->db->insert_id();
    }

    function update($id, $params)
    {
        $this->db->where('id',$id);
        return $this->db->update('passwordReset',$params);
    }
    
}