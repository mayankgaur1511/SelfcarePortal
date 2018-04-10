<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model('language_model');
    }

    function index()
    {
        $params['limit'] = 5; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $this->load->library('pagination');
        $config['base_url'] = '/settings/language?';
        $config['total_rows'] = $this->language_model->get_all_languages_count($this->input->get('search'));
        $config['per_page'] = $params['limit'];
        $this->pagination->initialize($config);

        $data['languages'] = $this->language_model->all($params,$this->input->get('search'));
        $data['_view'] = 'language/index';
        $data['_title'] = 'Languages';
        $this->load->view('layout/layout',$data);
    }

    function add()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('language','Langugae','required');
        $this->form_validation->set_rules('iso','ISO Code','required');
        $this->form_validation->set_rules('interface','Interface','required');

        if($this->form_validation->run())
        {
            $language_id = $this->language_model->add($this->input->post());

            $this->history->add('language',$language_id,'creation',$params);

            redirect('settings/language/');
        }

        else{
            $data['_view'] = 'language/form';
            $data['_title'] = 'Add new language';
            $this->load->view('layout/layout',$data);
        }
    }

    function update($id)
    {
        if($this->input->post())
        {       
            $this->language_model->update($id,$this->input->post());
            redirect('settings/language/');
        }
        else{
            $data['language'] = $this->language_model->get($id);
            $data['_view'] = 'language/form';
            $data['_title'] = 'Updating existing language';
            $this->load->view('layout/layout',$data);
        }
    }
}