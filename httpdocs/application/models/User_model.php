<?php
 
class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_user($id)
    {
        return $this->db->get_where('user',array('id'=>$id))->row_object();
    }

    function get_by_username($username)
    {
        return $this->db->get_where('user',array('username'=>$username))->row_object();
    }
        
    function get_all_users($params = array())
    {
        $this->db->order_by('id', 'desc');
        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }
        return $this->db->get('user')->result();
    }

    function get_all_users_count()
    {
        $this->db->from('user');
        return $this->db->count_all_results();
    }
        
    function add_user($params)
    {
        $this->db->insert('user',$params);
        return $this->db->insert_id();
    }
    
    function update_user($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('user',$params);
    }
    
    function delete_user($id)
    {
        return $this->db->delete('user',array('id'=>$id));
    }
}
