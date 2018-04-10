<?php
 
class Dashboard extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
       
    } 

    /*
     * Listing of users
     */
    
    function index()
    {
       
        $data['_title'] = 'Welcome to Dashboard';
        $data['_viewLeftMenu'] = 'layout/layout_leftmenu';
        $data['_view'] = 'dashboard/index';
        $this->load->view('layout/layout',$data);

        
    }

   
}
