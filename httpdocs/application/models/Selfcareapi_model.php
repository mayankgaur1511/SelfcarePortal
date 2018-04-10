<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Selfcareapi_model extends CI_Model {


    function __construct()
    {
		$this->API_URL	= 'https://isbs-apisc-ws.arkadin.lan/APISelfcare/SSC01_UserCredential/SSC01_UserCredential.svc?wsdl';
		$this->API_USER	= 'OnePortal'; 
		$this->API_PASSWORD	= "c9Er2Cra";
	}
	
	/*
	*	RETREIVE THE USER ID OF A GIVEN LOGIN AND PASSWORD
	*/
	function authenticateUser($login, $password)
	{
		$connection = $this->_SoapClient();

		$params = array(
				"request" => array(
					"Header" => array(
						"APILogin" => $this->API_USER,
						"APIPassword" => $this->API_PASSWORD,
						"RequestId" => 1,
					),
					"OrderChannel" => '',
					"SubmittedBy" => $login,
					"Login" => $login,
					"Password" => $password,
				)
		);

		try{
			$result = $connection->AuthenticateUser($params);
			if(isset($result->AuthenticateUserResult->UserId))
			{
				return $result->AuthenticateUserResult->UserId;
			}
			else{
				return 0;
			}
		}
		catch(Exception $e){
			if(isset($e->detail->FaultResponse->ErrorCode))
			{
				if($e->detail->FaultResponse->ErrorCode == "AUTHENTICATION_FAILED")
				{
					return 0;
				}
				
				return -1;
				
			}
			else{
				return -1;
			}
		}
	}

	function resetPassword($login, $password)
	{
		$connection = $this->_SoapClient();
		
		$params = array(
			"request" => array(
				"Header" => array(
					"APILogin" => $this->API_USER,
					"APIPassword" => $this->API_PASSWORD,
					"RequestId" => 1,
				),
				"OrderChannel" => '',
				"SubmittedBy" => $login,
				"Login" => $login,
				"NewPassword" => $password,
			)
		);

		try{
			$result = $connection->ResetPassword($params);
			if(isset($result->ResetPasswordResult->UserId))
			{
				return $result->ResetPasswordResult;
			}
			else{
				return -1;
			}
		}
		catch(Exception $e){
			return -1;
		}
	}

	function modifyPassword($login, $userId, $oldPassword, $newPassword)
	{
		$connection = $this->_SoapClient();
		
		$params = array(
			"request" => array(
				"Header" => array(
					"APILogin" => $this->API_USER,
					"APIPassword" => $this->API_PASSWORD,
					"RequestId" => 1,
				),
				"OrderChannel" => '',
				"SubmittedBy" => $login,
				"Login" => $login,
				"UserId" => $userId,
				"OldPassword" => $oldPassword,
				"NewPassword" => $newPassword,
			)
		);

		try{
			$result = $connection->ResetPassword($params);
			if(isset($result->ResetPasswordResult))
			{
				return $result->ResetPasswordResult;
			}
			else{
				return -1;
			}
		}
		catch(Exception $e){
			return -1;
		}
	}
	
	function _SoapClient(){
		return new SoapClient($this->API_URL, array("trace" => true, "stream_context" => stream_context_create(
				array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name'  => false,
					)
				)
			))
		);
	}




}