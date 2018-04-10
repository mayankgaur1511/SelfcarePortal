<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language_model extends CI_Model {

    function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('language')->row_object();
    }

    function find($where)
    {
        $this->db->where($where);
        return $this->db->get('language')->row_object();
    }

    function all($params = null, $search = false)
    {
        if($search)
        {
            $this->db->like('language', $search);
            $this->db->or_like('iso', $search);
            $this->db->or_like('wise2', $search);
            $this->db->or_like('bluesky', $search);
            $this->db->or_like('cmb', $search);
        }

        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->order_by('iso','asc');
        return $this->db->get('language')->result();
    }

    function get_all_languages_count($search = false)
    {
        if($search)
        {
            $this->db->like('language', $search);
            $this->db->or_like('iso', $search);
            $this->db->or_like('wise2', $search);
            $this->db->or_like('bluesky', $search);
            $this->db->or_like('cmb', $search);
        }

        $this->db->from('language');
        return $this->db->count_all_results();
    }

    function interface()
    {
        $this->db->where('interface',1);
        $this->db->order_by('iso','asc');
        return $this->db->get('language')->result();
    }

    function add($params)
    {
        if($insert = $this->db->insert('language',$params))
        {
            // If language has been created, create new column in case interface is true.
            if($params['interface'] === 1){
                $this->load->dbforge();
                if($update = $this->dbforge->add_column('translation', array($params['iso'] => 'TEXT')))
                {
                    return $this->db->insert_id();
                }
                else{
                    return $update;
                }
            }
            else{
                return $this->db->insert_id();
            }
        }
        else
        {
            return $insert;
        }
    }

    function update($id,$params)
    {
        $this->db->where('id',$id);
        if($update = $this->db->update('language',$params))
        {
            if($params['interface'])
            {
                // Check if column already exists
                $translation = $this->db->get('translation')->row_array();
                if(!isset($translation[$params['iso']]))
                {
                    $this->load->dbforge();
                    return $this->dbforge->add_column('translation', array($params['iso'] => array('type' => 'NVARCHAR(MAX)')));
                }
            }
            else{
                return $update;
            }
        }
        else{
            return $update;
        }
    }
}