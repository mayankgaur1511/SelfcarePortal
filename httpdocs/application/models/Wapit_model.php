<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wapit_model extends CI_Model {


    function __construct()
    {
	   
		$this->WAPI_url			=	'http://10.102.10.24/WAPITranslator/WAPITranslator.asmx'; 
		$this->WAPI_service_url	=	'http://10.102.10.24/WAPITranslator/WAPITranslator.asmx'.'?wsdl';
		$this->WAPI_ddi_lang	=	'en'; 
		$this->WAPI_login		=	"super";
		$this->WAPI_password	=	"super";

		
    }

    /* 
     * Primary Group functions
     */

    function GetCompanyWise2($CompanyRef)
    {
		
 		$connection     = $this->_SoapClient($this->WAPI_service_url);
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $this->WAPI_bridge,
			'companyRef'		=> $CompanyRef
		);
		
		 $result 		= $connection->GetCompanyByCompanyRef( $params);
		 $resultCode 	= $result->GetCompanyByCompanyRefResult->resultCode; 
		 if($resultCode){
			return false;
		 }else{
			return $result->GetCompanyByCompanyRefResult->companies->CompanyListByCompanyRefResult;
		 }
		
	}


	function CreateClient($CompanyRef, $bridge, $doBlockDialout, $Name, $Phone, $Email)
    {
		
 		$connection     = $this->_SoapClient($this->WAPI_service_url);
		
		if (!$doBlockDialout) $doBlockDialout = 'false';
		
		$params  = array(
			 'login'			=> $this->WAPI_login,
			 'password'			=> $this->WAPI_password,
			 'bridgeID'			=> $bridge,
			 'CompanyRef'		=> $CompanyRef,
			 'doBlockDialout'	=> $doBlockDialout,
			 'Name'				=> $Name,
			 'Phone'			=> $Phone,
			 'Email'			=> $Email,
			 'ContactName'		=> $Name,
			 'ContactEmail'		=> $Email
		   );

		 
		  $result = $connection->CreateClient($params);

		 $resultCode = $result->CreateClientResult->resultCode; 
		 if($resultCode){
			return false;
		 }else{
			return $result->CreateClientResult->clientRef;
		 }
		
	}


	function UpdateClient($ClientRef, $bridge, $Name, $Phone, $Email)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
				
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'ClientRef'			=> $ClientRef,
			'doBlockDialout'	=> false,
			'Name'				=> $Name,
			'Phone'				=> $Phone,
			'Email'				=> $Email,
			'ContactName'		=> $Name,
			'ContactEmail'		=> $Email
		  );
        
 		 $result = $connection->UpdateClient($params);
		 $resultCode 	= $result->UpdateClientResult ->resultCode; 
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
		
	}

	function GetClient($clientRef, $bridge)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'clientRef'			=> $clientRef
		  );

 		$result = $connection->GetClient($params);
		
		$resultCode = $result->GetClientResult->resultCode;
		if($resultCode){
		   return false;
		}else{
		   return $result->GetClientResult->clientResult;
		}
		
	}
	
	
	function GetClientList($companyRef,$bridge)
	{	 
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'companyRef'		=> $companyRef
		);

		$result = $connection->GetClientList($params);
		
		$resultCode = $result->GetClientListResult->resultCode;
		
		if($resultCode){
		   return false;
		}else{
			if(gettype($result->GetClientListResult->clients->ClientListResult) == "object")
			{
				$result->GetClientListResult->clients->ClientListResult = array(
					$result->GetClientListResult->clients->ClientListResult);
			}
		   return $result->GetClientListResult->clients;
		}
	}


	
	function ReEnableClient ($clientRef)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $this->WAPI_bridge,
			'clientRef'			=> $clientRef
		  );

 		$result = $connection->ReEnableClient($params);
		
		 $resultCode = $result->ReEnableClientResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}


	function DeleteClient ($ClientRef)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $this->WAPI_bridge,
			'ClientRef'			=> $ClientRef
		  );

		 $result = $connection->DeleteClient($params);
		 
		 $resultCode = $result->DeleteClientResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}

	function CreateConference($ClientRef, $bridge, $Name, $demand, $startDateTimeUTC, $Duration, $Participants, $confType, $DDILanguage, $ContactName, $BillingCode, $moderatorPin, $participantPin,$countryCode,$audioOptions)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);
		if (!$countryCode) $countryCode = ''; 
       
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'ClientRef'			=> $ClientRef,
			'Name'				=> $Name,
			'demand'			=> $demand,
			'startDateTimeUTC'	=> ($startDateTimeUTC? $startDateTimeUTC:date('Y-m-d H:i:s')),
			'Duration'			=> ($Duration? $Duration:120),
			'Participants'		=> $Participants,
			'confType'			=> $confType,
			'DDILanguage'		=> $DDILanguage,
			'data'				=> "None",
			'ContactName'		=> $ContactName,
			'BillingCode'		=> $BillingCode,
			'moderatorPin'		=> ($moderatorPin ? $moderatorPin:''),
			'participantPin'	=> ($participantPin ? $participantPin:''),
			'countryCode' 		=> $countryCode
		);

		$result = $connection->CreateConference($params);
		$resultCode = $result->CreateConferenceResult->resultCode;
		var_dump($params);
		var_dump($resultCode);
		if($resultCode){
			return false;
		}else{
			return $result->CreateConferenceResult;
		}
	}

	function ConfigureConference($conferenceRef,$bridge, $broadCast, $enterTone, $leavingTone, $QandA, $rollCall, $nameOnEntry, 
									$nameOnExit, $waitForModerator, $blockDialOut, $recording)
	{
		$client = new SoapClient( $this->WAPI_service_url );
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'conferenceRef'		=> $conferenceRef,
			'broadCast'			=> ($broadCast == "true" ? true:false),
			'enterTone'			=> ($enterTone == "true" ? true:false),
			'leavingTone'		=> ($leavingTone == "true" ? true:false),
			'QandA'				=> ($QandA == "true" ? true:false),
			'rollCall'			=> ($rollCall == "true" ? true:false),
			'nameOnEntry'		=> ($nameOnEntry == "true" ? true:false),
			'nameOnExit'		=> ($nameOnExit == "true" ? true:false),
			'waitForModerator'	=> ($waitForModerator == "true" ? true:false),
			'blockDialOut'		=> ($blockDialOut == "true" ? true:false),
			'recording'			=> ($recording == "true" ? true:false),
		);

 		$result = $client->ConfigureConference($params);
		$resultCode = $result->ConfigureConferenceResult->resultCode;
		if($resultCode){
			return false;
		}else{
			return true;
		}
	}

	function ConfigureConferenceParameters($conferenceRef, $bridge, $optionSet)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$confParameters = array();
		
		foreach($optionSet as $key => $option)
		{
			$confParameters[] = array("Key" => $key, "Value" => $option);
		}

		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'conferenceRef'		=> $conferenceRef,
			'confParameters'	=> $confParameters 
		  );

		 $result = $connection->ConfigureConferenceParameters($params);

		 var_dump($result->ConfigureConferenceParametersResult);

		 $resultCode = $result->ConfigureConferenceParametersResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}


	function GetConference($confRef,$bridgeID)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridgeID,
			'confRef'			=> $confRef
		  );

		 $result = $connection->GetConference($params);
		 
		 $resultCode = $result->GetConferenceResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return $result->GetConferenceResult->conferenceResult;
		 }
		 
	}


	function GetConferencesByClientRef ($clientRef,$bridgeID, $scheduled, $beginDateUTC = "", $endDateUTC = "")
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridgeID,
			'clientRef'			=> $clientRef,
			'scheduled'			=> $scheduled,
			'beginDateUTC'		=> $beginDateUTC,
			'endDateUTC'		=> $endDateUTC
		  );
		//print_r($params); 
 		$result = $connection->GetConferencesByClientRef($params);
		//print_r( $result );
		$resultCode = $result->GetConferencesByClientRefResult->resultCode;
		if($resultCode){
		   return false;
		}else{
		   return $result->GetConferencesByClientRefResult->conferences;
		}
	}

	function GetConferenceConfiguration ($confRef,$bridgeID)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridgeID,
			'confRef'			=> $confRef
		);

 		$result = $connection->GetConferenceConfiguration($params);
		 $resultCode = $result->GetConferenceConfigurationResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return $result->GetConferenceConfigurationResult->conferenceConfigurationResult;
		 }
	}

	function UpdateConferenceBillingCode($conferenceRef, $billingCode)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $this->WAPI_bridge,
			'conferenceRef'		=> $conferenceRef,
			'billingCode'		=> $billingCode
		  );

 		 $result = $connection->UpdateConferenceBillingCode($params);
		 $resultCode = $result->UpdateConferenceBillingCodeResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}

	function UpdateConference($ConferenceRef, $bridge, $Name, $Duration, $Participants, $ContactName, $billingCode)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);

		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'ConferenceRef'		=> $ConferenceRef,
			'Name'				=> $Name,
			'Duration'			=> $Duration,
			'Participants'		=> $Participants,
			'data'				=> 'None',
			'ContactName'		=> $ContactName,
			'billingCode'		=> $billingCode
		  );

 		 $result = $connection->UpdateConference($params);
		 $resultCode = $result->UpdateConferenceResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}

	 
	function UpdateConferenceScheduleTime($conferenceRef, $bridge, $newStartDateTimeUTC)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'conferenceRef'		=> $conferenceRef,
			'newStartDateTimeUTC'	=> $newStartDateTimeUTC
		  );

 		 $result = $connection->UpdateConferenceScheduleTime( $params );
		 $resultCode = $result->UpdateConferenceScheduleTimeResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}

	function DeleteConference($conferenceRef, $bridge)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'ConferenceRef'		=> $conferenceRef 
		  );

 		 $result = $connection->DeleteConference( $params );
		 $resultCode = $result->DeleteConferenceResult->resultCode;
		 
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
	}
 

	function ReEnableConference($conferenceRef)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $this->WAPI_bridge,
			'conferenceRef'		=> $conferenceRef 
		  );

		 $result = $connection->ReEnableConference( $params );
		 
		 $resultCode 	=$result->ReEnableConferenceResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return true;
		 }
		
	}

	function CreateWebexUser($ConfRef, $MicroSiteName, $countryCode, $firstname, $lastname, $email, $mpin, $cpin, $password)
    {
 		$connection     = $this->_SoapClient($this->WAPI_service_url);
		
		

		$dataParameters = array(
			array('Key' => 'email', 'Value'=> $email),
			array('Key' => 'password', 'Value'=> $password),
			array('Key' => 'firstname', 'Value'=> $firstname),
			array('Key' => 'lastname', 'Value'=> $lastname),
			array('Key' => 'mpin', 'Value'=> $mpin),
			array('Key' => 'cpin', 'Value'=> $cpin),
			array('Key' => 'language', 'Value'=> 'English'),
		);

		$params  = array(
			 'login'			=> $this->WAPI_login,
			 'password'			=> $this->WAPI_password,
			 'confRef'			=> $ConfRef,
			 'microSiteName'	=> $MicroSiteName,
			 'parameters'		=> $dataParameters,
		   );
		   
		$orderId = -1;
		$result = $connection->CreateWebexAccount($params);
		$resultCode = $result->CreateWebexAccountResult->resultCode; 

		 if($resultCode){
			log_message('error', $orderId . ' | ' . $resultCode);
			return false;
		 }else{
			return $result->CreateWebexAccountResult->dataDetail;
		 }
		
	}


	function GetBridgeID($extConfRef){

		$connection     = $this->_SoapClient($this->WAPI_service_url);
		 
		$params  = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'extConfRef'		=> $extConfRef 
		  );

		  $result = $connection->GetBridgeID( $params );
		
		  $resultCode 	=$result->GetBridgeIDResult->resultCode;
		 if($resultCode){
			return false;
		 }else{
			return $result->GetBridgeIDResult->bridgeID;
		 }

	}

	function _SoapClient($url){

		return $connection = new SoapClient($url, array('trace' => 1));
	}
}