<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translation extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model('Translation_model');
    }

    function index()
    {
        $params['limit'] = 5; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $this->load->library('pagination');
        $config['base_url'] = '/settings/translation?';
        $config['total_rows'] = $this->Translation_model->get_all_translations_count($this->input->get('search'));
        $config['per_page'] = $params['limit'];
        $this->pagination->initialize($config);

        $data['translations'] = $this->Translation_model->all($params,$this->input->get('search'));
        $data['languages'] = $this->language->interface();
        $data['_view'] = 'translation/index';
        $data['_title'] = 'Translations';
        $this->load->view('layout/layout',$data);
    }

    function add()
    {
        if($this->input->post())
        {
            if($this->Translation_model->add($this->input->post()))
            {
                $this->generateFiles();
                redirect('/translation/index');
            }
        }
        else{
            $data['languages'] = $this->language->interface();
            $data['_view'] = 'translation/form';
            $data['_title'] = 'Add new translation';
            $this->load->view('layout/layout',$data);
        }
    }

    function update($id)
    {
        if($this->input->post())
        {   
                      
            if($this->Translation_model->update($id,$this->input->post()))
            {  
                $this->generateFiles();
                redirect('/translation/index');
            }
        }
        else{
            $data['languages'] = $this->language->interface();
            $data['translation'] = $this->Translation_model->get($id);
            $data['_view'] = 'translation/form';
            $data['_title'] = 'Updating existing translation';
            $this->load->view('layout/layout',$data);
        }
    }

    function generateFiles()
    {
        $translations = (array) $this->Translation_model->all();
        
        $languages = array();
        foreach($this->language->interface() as $language)
        {
            $languages[$language->iso] = array(
                'iso' => $language->iso,
                'language' => $language->language,
            );
        }

        foreach($translations as $translation)
        {
            $translation = (array) $translation;
            foreach($languages as $key => $language)
            {
                $languages[$key]['terms'][$translation['term']] = $translation[$language['iso']];
            }
        }
        
        foreach($languages as $language)
        {
            $lines = "<?php\n";
            foreach($language['terms'] as $key => $term)
            {
                $term = str_replace('"',"'",$term);
                $lines = $lines . "$" . "lang['" . $key . "'] = \"" . $term . "\";\n";
            }

            if(!file_exists("application/language/" . $language['language']))
            {
                mkdir("application/language/" . $language['language']);
            }

           file_put_contents("application/language/" . $language['language'] . "/" . "message_lang.php", $lines);
           //print $lines ."<BR><BR>";
        }
    }
}