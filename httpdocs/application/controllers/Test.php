<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        /*
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model('Translation_model');
        */
    }

    function index()
    {
        /*
            print "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
        */
        /*
        $data['anytime'] = true;
        $data['anywhere'] = true;
        $this->load->view('welcomePack/skeleton', $data);
        */
        
        /*$this->load->model("W2hierarchy_model","anywhere");
        print_r($this->anywhere->getWebexMicrosites("a0Ka000000Gkz3zEAB"));
        /*

        $this->load->model("Anywhere_model","anywhere");
        var_dump($this->anywhere->anywhereEnabled("622502"));*/

        $this->load->model("Salesforce_model");
        var_dump($this->Salesforce_model->getDocument("0691O0000072qvPQAQ"));
    }

}