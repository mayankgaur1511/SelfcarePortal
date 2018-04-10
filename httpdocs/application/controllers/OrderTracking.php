<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderTracking extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model('Order_model', 'order');
        $this->load->model('Suborder_model','subOrder');
        $this->load->model('Countrymaster_model','country');
        $this->load->model('Timezone_model','timezone');
        $this->load->model('Language_model','language');
        $this->load->model('W2hierarchy_model','w2hierarchy');
    }


    function index()
    {
        $params['limit'] = 5; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $this->load->library('pagination');
        $config['base_url'] = '/OrderTracking/index?';
        $config['total_rows'] = $this->order->count_find($this->session->userdata('login'),$this->input->get('search'));
        $config['per_page'] = $params['limit'];
        $this->pagination->initialize($config);

        $data['orders'] = $this->order->find($this->session->userdata('login'), $params, $this->input->get('search'));

        $data['_view'] = 'orderTracking/index';
        $data['_title'] = 'Order history';
        $this->load->view('layout/layout',$data);
    }

    function getStatus($orderId)
    {
        $order = $this->order->get($orderId);
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(array("orderId" => $order->id, "status" => $order->status)));
    }
}