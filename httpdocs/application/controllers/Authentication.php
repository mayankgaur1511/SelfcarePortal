<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {

	function __construct()
    {
        parent::__construct();
    }

	/*
	* Sign in page
	*/

	public function show(){
		var_dump($this->session->userdata());
	}

	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if($this->form_validation->run())
		{
			// Check if user exists in the local database (Arkadin Admin Users)
			$this->load->model('user_model');
			$user = $this->user_model->get_by_username($this->input->post('username'));

			if($user and $user->password == md5($this->input->post('password')))
			{
				$this->session->set_userdata(
					array(
						'userId' => $user->id,
						'logged_in' => true,
						'admin' => true,
				));
			}
			$bridgeType=null;

			/*
			* CHECK IF USER EXISTS IN BLUESKY
			*/

			$this->load->model('Selfcareapi_model');
			$bluesky_userId = $this->Selfcareapi_model->authenticateUser($this->input->post('username'),
				$this->input->post('password'));
			
			$bsUserId = (($bluesky_userId and ($bluesky_userId != -1)) ? $bluesky_userId:null);
			$password = ($bsUserId ? $this->input->post('password'):null);

			/*
			* CHECK IF USER EXISTS IN WISE2
			*/
			$this->load->model('Salesforce_model','salesforce');
			if($selfcareUser = $this->salesforce->getSelfcareUser($this->input->post('username')))
			{
				// IF USER AND PASSWORD MATCH IN WISE2 OR USER AND PASSWORD MATCHED IN BS AND USER EXISTS IN W2.
				if($selfcareUser->Password__c == $this->input->post('password') or $bsUserId)
				{
					$w2UserId = $selfcareUser->Id;
					$w2Node = $this->salesforce->getNodeByNodeReference($selfcareUser->Allow_Nodes__c);
					$w2Pass = $selfcareUser->Password__c;
				}
				$bridgeType='WISE2';
			}

			// Start Session if W2 or BS user has been found
			if(isset($w2UserId) or isset($bsUserId)){
				$this->session->set_userdata(
					array(
						'wise2UserId'	=> $w2UserId,
						'wise2Node' 	=> $w2Node,
						'wise2Pass'		=> $w2Pass,
						'blueskyUserId' => $bsUserId,
						'password'	 	=> $password,
						'login'  		=> $this->input->post('username'),
						'logged_in' 	=> true
					));

				$this->load->model("Loginpreferences_model","preferences");
				$preferences = $this->preferences->find(array("login" => $this->input->post('username')));
				if($preferences)
				{
					$this->session->set_userdata("preferencesId",$preferences->id);
				}
				redirect("/dashboard");
			}

			// If session is not created, return error message
			if(!$this->session->userdata('logged_in'))
			{
				$data['user_pass_incorrect'] = true;
			}
		}

		// Display login page
		$data['_view'] = 'authentication/sign_in';
		$this->load->view('layout/layout_signin',$data);
	}
	
	/* 
	* Forget Password Functions
	*/

	// FORM TO REQUEST RESET PASSWORD CONFIRMATION LINK
	public function resetPassword()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','Username','required');

		if($this->form_validation->run())
		{
			// Check if user exists in BlueSky
			$this->load->model('Selfcareapi_model');
			$message = $this->Selfcareapi_model->authenticateUser($this->input->post('username'),"INVALID@PASS!ARKA");
			if($message === 0)
			{
				$userExists = true;
			}

			// Check if user exists in Wise2
			if(!isset($userExists))
			{
				$this->load->model('Salesforce_model','salesforce');
				$user = $this->salesforce->getSelfcareUser($this->input->post('username'));
				if(isset($user->Id) and $user->Id){
					$userExists = true;
				}
			}

			if(isset($userExists) and $userExists)
			{
				$this->load->model('Passwordreset_model');
				$token = bin2hex(random_bytes(60));
				$this->Passwordreset_model->add(array(
					"token" => $token,
					"login" => $this->input->post('username'),
					"requestDate" => date("Y-m-d H:i:s")
				));

				// Send E-mail with Password Reset Link
				$html = $this->load->view('authentication/reset_password_email', 
							array(
								'token'=>$token,
								'login'=>$this->input->post('username')), true);

				send_email($html, "Selfcare Portal - Reset Password", "noreply@arkadin.com", array(
					array('email'=>$this->input->post('username'))
				));
				
				$this->session->set_flashdata('password_reset_link_sent', true);
				redirect("/signin");
			}
			else{
				$data['invalid_login'] = true;
			}
		}

		$data['_view'] = 'authentication/forget_password';
		$this->load->view('layout/layout_signin',$data);
	}

	// FORM TO ENTER NEW PASSWORD AND TO PROCESS REQUEST
	public function resetPasswordLink()
	{
		$this->load->model('Passwordreset_model');
		// CHECK IF REQUEST COMES FROM RESET PASSWORD E-MAIL LINK
		if($this->input->get('token') and $this->input->get('login'))
		{
			// CHECK IF TOKEN IS VALID
			$request = $this->Passwordreset_model->get($this->input->get('token'),
				$this->input->get('login'));
			if(isset($request->id) and $request->id)
			{
				$data['request'] = $request;
				$data['_view'] = 'authentication/reset_password';
				$this->load->view('layout/layout_signin',$data);
			}
		}
		else{
			// CHECK IF FORM WAS SENT WITH NEW PASSWORD
			if($this->input->post('token') and $this->input->post('login'))
			{
				$token = $this->input->post('token');
				$login = $this->input->post('login');

				$request = $this->Passwordreset_model->get($token,$login);

				if(isset($request->id) and $request->id)
				{
					// TRY TO RESET BLUESKY PASSWORD
					$this->load->model('Selfcareapi_model');
					log_message("info","Trying to reset BlueSky Selfcare Password - Token {$token}");

					if($this->Selfcareapi_model->resetPassword($request->login, $this->input->post('password')) != -1)
					{
						log_message("info","BlueSky Selfcare Password succesfuly reset - Token {$token}");
						$data['billingSystem'] = 'BlueSky';
					}
					else{
						// CHECK IF USER EXISTS IN W2
						$this->load->model('Salesforce_model','salesforce');
						$user = $this->salesforce->getSelfcareUser($request->login);
						if(isset($user->Id) and $user->Id){
							if($this->salesforce->resetSelfcarePassword($user->Id,$this->input->post('password')))
							{
								log_message("info","Wise2 Selfcare Password succesfuly reset - Token {$token}");
								$data['billingSystem'] = 'Wise2';
							}
						}
						else{
							log_message("info","No Wise2 Selfcare user found for password reset - Token {$token}");
						}
					}
					$data['processingDate'] = date("Y-m-d H:i:s");
					$this->Passwordreset_model->update($request->id, $data);

					if(!isset($data['billingSystem']))
					{
						print "Users not found!";
					}
					else{
						
						$this->session->set_flashdata('password_reset_succesful', true);
						redirect("/signin");
					}
				}
			}
			else{
				print "No token ot login";
			}
		}
	}

	function changePassword()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('new_password','New Password','required');
		$this->form_validation->set_rules('old_password','Old Password','required');

		if($this->form_validation->run())
		{
			// CHECK IF USER IS BLUESKY SELFCARE USER
			if($this->session->userdata('blueskyUserId'))
			{
				$this->load->model('Selfcareapi_model');
				if($this->Selfcareapi_model->modifyPassword(
					$this->session->userdata('login'), $this->session->userdata('blueskyUserId'),
					$this->input->post('old_password'), $this->input->post('new_password')))
				{
					$data['password_changed'] = true;
				}
				else{
					$data['wrong_password'] = true;
				}
			}
			else{
				// CHECK IF USER IS WISE2 SELFCARE USER
				if($this->session->userdata('wise2UserId'))
				{
					$this->load->model('Salesforce_model','salesforce');
					$selfcareUser = $this->salesforce->getSelfcareUser($this->session->userdata('login'));
					if($selfcareUser->Password__c == $this->input->post('old_password')){
						$this->salesforce->resetSelfcarePassword($this->session->userdata('wise2UserId'),$this->input->post('new_password'));
						$data['password_changed'] = true;
					}
					else{
						$data['wrong_password'] = true;
					}
				}
			}
		}

		$data['_title'] = 'Change my password';
		$data['_view'] = 'authentication/change_password';
		$this->load->view('layout/layout',$data);
	}

	/* 
	* Sign out
	*/
	public function signout()
	{
		$this->session->sess_destroy();
		redirect("/");
	}
}
