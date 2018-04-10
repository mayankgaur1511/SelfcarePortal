<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translation_model extends CI_Model {

    function all($params = array(), $search = false)
    {
        if($search)
        {
            $this->load->model('language_model');
            $languages = $this->language_model->all();

            $this->db->where('term',$search);
            foreach($languages as $language)
            {
                $this->db->or_like($language->iso, $search);
            }
        }

        $this->db->order_by('id','desc');
        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }
        return $this->db->get('translation')->result();
    }
    
    function get_all_translations_count($search)
    {
        if($search)
        {
            $this->load->model('language_model');
            $languages = $this->language_model->all();
            
            $this->db->like('term',$search);
            foreach($languages as $language)
            {
                $this->db->or_like($language->iso, $search);
            }
        }

        $this->db->from('translation');
        return $this->db->count_all_results();
    }

    function get($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('translation')->row_object();
    }

    function add($params)
    {
        return $this->db->insert('translation',$params);
    }

    function update($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('translation',$params);
    }

    function delete($id)
    {
        $this->db->where('id',$id);
        return $this->db->delete('translation');
    }

}