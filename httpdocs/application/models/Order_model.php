<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('order')->row_object();
    }

    function getOpen()
    {
        $this->db->where_in('status', array("CREATED","PROCESSING"));
        return $this->db->get('order')->result();
    }

    function find($login, $params, $search = false)
    {
        $this->db->select("order.*");
        $this->db->where('userId', $login);
        $this->db->join('subOrder','subOrder.orderId = order.id');

        if($search)
        {
            $search = trim($search);
            if(gettype($search) == "integer")
            {
                $this->db->where('login', $login);    
            }
            else{
                $this->db->where("(id = '{$search}' or data like '%{$search}%')");
            }
        }

        $this->db->order_by('order.id','desc');
        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }

        return $this->db->get('order')->result();
    }

    function count_find($login, $search = false)
    {
        $this->db->where('userId', $login);

        if($search)
        {
            $search = trim($search);
            if(gettype($search) == "integer")
            {
                $this->db->where('login', $login);
            }
            else{
                $this->db->where("(id = '{$search}' or data like '%{$search}%')");
            }
        }
        $this->db->from('order');
        return $this->db->count_all_results();
    }

    function add($orderType,$billingSystem,$data)
    {
        $params = array(
            'userId' => $this->session->userdata('login'),
            'datetime' => date('Y-m-d H:i:s'),
            'orderType' => $orderType,
            'billingSystem' => $billingSystem,
            'data' => $data,
            'status' => 'CREATED'
        );

        $this->db->insert('order',$params);
        return $this->db->insert_id();
    }

    function update($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('order',$params);
    }
}

