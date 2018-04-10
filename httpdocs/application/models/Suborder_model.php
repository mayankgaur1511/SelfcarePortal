<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suborder_model extends CI_Model {

    function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('subOrder')->row_object();
    }

    function getSubOrders($orderId)
    {
        $this->db->where('orderId', $orderId);
        return $this->db->get('subOrder')->result();
    }

    function getOpenSubOrders($orderId = false)
    {
        if($orderId)
        {
            $this->db->where('orderId', $orderId);
            $this->db->where("(status = 'CREATED' or status = 'PENDING' or (status = 'ERROR' and attempts < 3))");
        }
        else{
            $this->db->where("(status = 'WAITING' or status = 'PENDING' or (status = 'ERROR' and attempts < 3))");
        }

        $this->db->order_by("id");

        return $this->db->get('subOrder')->result();
    }

    function add($orderId,$orderType,$status="CREATED")
    {
        $params = array(
            'orderId' => $orderId,
            'datetime' => date('Y-m-d H:i:s'),
            'orderType' => $orderType,
            'data' => NULL,
            'attempts' => 0,
            'status' => $status
        );
        
        $this->db->insert('subOrder',$params);
        return $this->db->insert_id();
    }

    function update($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('subOrder',$params);
    }
}

