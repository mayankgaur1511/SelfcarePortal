<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends CI_Model {

    function add($object,$objectId,$action,$data)
    {

        $userId = ($this->session->userdata('userId') ? $this->session->userdata('userId'):1);

        return $this->db->insert('history',array(
            'datetime' => date("Y-m-d H:i:s"),
            'userId' => $userId,
            'object' => $object,
            'objectId' => $objectId,
            'action' => $action,
            'data' => json_encode($data),
        ));
    }

}