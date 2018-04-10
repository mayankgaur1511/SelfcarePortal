<?php
 
class User extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model('user_model');
        $this->load->model('userType_model');
    } 

    /*
     * Listing of users
     */
    
    function index()
    {
        $params['limit'] = 5; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $this->load->library('pagination');

        $config['attributes'] = array('class' => 'page-link');

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['base_url'] = '/settings/user?';
        $config['total_rows'] = $this->user_model->get_all_users_count();
        $config['page_query_string'] = TRUE;
        $config['per_page'] = 5;

        $this->pagination->initialize($config);

        $data['users'] = $this->user_model->get_all_users($params);
        $data['_title'] = 'Selfcare users';
        $data['_view'] = 'user/index';
        $this->load->view('layout/layout',$data);

        
    }

    /*
     * Adding a new user
     */

    function add()
    {   
        $this->load->library('form_validation');

        $this->form_validation->set_rules('firstName','First name','required');
        $this->form_validation->set_rules('lastName','Last name','required');
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('email','E-mail Address','required');
        $this->form_validation->set_rules('password','Password','required');
        $this->form_validation->set_rules('userTypeId','User type','required');
		
		if($this->form_validation->run())     
        {   
            $params = array(
                'firstName' => $this->input->post('firstName'),
                'lastName' => $this->input->post('lastName'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'phone' => $this->input->post('phone'),
                'title' => $this->input->post('title'),
                'userTypeId' => $this->input->post('userTypeId'),
                'creationDate' => date('Y-m-d H:i:s')
            );
            
            $user_id = $this->user_model->add_user($params);

            $this->history->add('user',$user_id,'creation',$params);

            redirect('settings/user/');
        }
        else
        {   
            $data['userTypes'] = $this->userType_model->all();
            $data['_title'] = 'Add selfcare user';         
            $data['_view'] = 'user/form';
            $this->load->view('layout/layout',$data);
        }
    }  

    /*
     * Editing a user
     */
    function update($id)
    {   
        // check if the user exists before trying to edit it
        $data['user'] = $this->user_model->get_user($id);
        
        if(isset($data['user']->id))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('firstName','FirstName','required');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'firstName' => $this->input->post('firstName'),
                    'lastName' => $this->input->post('lastName'),
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('username'),
                    'phone' => $this->input->post('phone'),
                    'title' => $this->input->post('title'),
                    'userTypeId' => $this->input->post('userTypeId'),
                    'lastModifiedDate' => date('Y-m-d H:i:s')
                );

                $this->user_model->update_user($id,$params);

                $this->history->add('user',$id,'modification',$params);     
                redirect('user/index');
            }
            else
            {
                $data['userTypes'] = $this->userType_model->all();
                $data['_title'] = 'Update selfcare user';
                $data['_view'] = 'user/form';
                $this->load->view('layout/layout',$data);
            }
        }
        else
            show_error('The user you are trying to edit does not exist.');
    } 
}
