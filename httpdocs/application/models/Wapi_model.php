<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wapi_model extends CI_Model {


    function __construct()
    {   $this->WAPI_service_url="http://ap-wapi.arkadin.com/WAPIFullService.asmx?wsdl";
        $this->WAPI_login = "super";
        $this->WAPI_password = "super";
	}
	
	function CreateClient($CompanyRef, $bridge, $doBlockDialout, $Name, $Phone, $Email)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);

		if (!$doBlockDialout) $doBlockDialout = 'false';
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'CompanyRef'		=> $CompanyRef,
			'doBlockDialout'	=> $doBlockDialout,
			'Name'				=> $Name,
			'Phone'				=> $Phone,
			'Email'				=> $Email,
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

	function GetClient($clientRef, $bridge)
	{	 
		$connection     = $this->_SoapClient($this->WAPI_service_url);		
		$params = array(
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

	function UpdateClient($ClientRef, $bridge, $Name, $Phone, $Email)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);		
		$params = array(
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
		
		$resultCode = $result->UpdateClientResult ->resultCode; 
		if($resultCode){
			return false;
		}else{
			return true;
		}
	}

	function GetClientList($companyRef, $bridge)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);

		$url = $this->WAPI_service_url . '/GetClientList';
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'companyRef'		=> $companyRef
		  );

		  $result = $connection->GetClientList($fields1);

		  if($result->GetClientListResult->resultCode != 0){
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


	/* TO MODIFY */
	function ReEnableClient ($clientRef, $bridge)
	{	$url = $this->WAPI_service_url . '/ReEnableClient';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'clientRef'			=> urlencode($clientRef)
		  );

		  $result =  $this->_GET($url,$fields1);
		  $resultCode = $result->ReEnableClientResult->resultCode;
		  if($resultCode){
			 return false;
		  }else{
			 return true;
		  }
	}

	/* TO MODIFY */
	function DeleteClient ($ClientRef, $bridge)
	{	$url = $this->WAPI_service_url . '/DeleteClient';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($bridge),
			'ClientRef'			=> urlencode($ClientRef)
		  );

 
		  $result =  $this->_GET($url,$fields1);
		  $resultCode = $result->DeleteClientResult->resultCode;
		  if($resultCode){
			 return false;
		  }else{
			 return true;
		  }
	}

	function CreateConference($ClientRef, $bridge, $Name, $demand, $startDateTimeUTC, $Duration, $Participants, $confType, $DDILanguage, $ContactName, $BillingCode, $moderatorPin, $participantPin)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'ClientRef'			=> $ClientRef,
			'Name'				=> $Name,
			'demand'			=> $demand,
			'startDateTimeUTC'	=> $startDateTimeUTC,
			'Duration'			=> $Duration,
			'Participants'		=> $Participants,
			'confType'			=> $confType,
			'DDILanguage'		=> $DDILanguage,
			'data'				=> "None",
			'ContactName'		=> $ContactName,
			'BillingCode'		=> $BillingCode,
			'moderatorPin'		=> ($moderatorPin ? $moderatorPin:''),
			'participantPin'	=> ($participantPin ? $participantPin:''),
		);
 
		$result = $connection->CreateConference($params);
		var_dump($params);
		var_dump($result);
		$resultCode = $result->CreateConferenceResult->resultCode;

		if($resultCode){
			return false;
		}else{
			return $result->CreateConferenceResult;
		}
	}

	/* TO MODIFY */
	function ConfigureConference($conferenceRef, $bridge, $broadCast, $enterTone, $leavingTone, $QandA, $rollCall, $nameOnEntry, 
									$nameOnExit, $waitForModerator, $blockDialOut, $recording)
	{	
		$url = $this->WAPI_service_url . '/ConfigureConference';
		$connection = $this->_SoapClient($this->WAPI_service_url);
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'conferenceRef'		=> $conferenceRef,
			'broadCast'			=> $broadCast,
			'enterTone'			=> $enterTone,
			'leavingTone'		=> $leavingTone,
			'QandA'				=> $QandA,
			'rollCall'			=> $rollCall,
			'nameOnEntry'		=> $nameOnEntry,
			'nameOnExit'		=> $nameOnExit,
			'waitForModerator'	=> $waitForModerator,
			'blockDialOut'		=> $blockDialOut,
			'recording'			=> $recording
		  );
		  
		$result = $connection->ConfigureConference($params);
		var_dump($params);
		var_dump($result);
		$resultCode = $result->ConfigureConferenceResult->resultCode;

		if($resultCode){
			return false;
		}else{
			return true;
		}
	}


	function GetConference ($confRef, $bridge)
	{	
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
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


	function GetConferencesByClientRef($clientRef, $bridge, $scheduled, $beginDateUTC = false, $endDateUTC = false)
	{	
		$connection = $this->_SoapClient($this->WAPI_service_url);

		$url = $this->WAPI_service_url . '/';
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
			'clientRef'			=> $clientRef,
			'scheduled'			=> $scheduled,
			'beginDateUTC'		=> $beginDateUTC,
			'endDateUTC'		=> $endDateUTC
		  );
 
		  $result = $connection->GetConferencesByClientRef($fields1);
		  $result = $result->GetConferencesByClientRefResult;		 

		  if($result->resultCode != 0){
			 return false;
		  }else{
			 return $result->conferences;
		  }
	}

	function UpdateConferencePINS ($conferenceRef, $bridge, $newModeratorPin, $newParticipantPin)
	{	$url = $this->WAPI_service_url . '/UpdateConferencePINS';
		 

		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($bridge),
			'conferenceRef'		=> urlencode($conferenceRef),
			'newModeratorPin'	=> urlencode($newModeratorPin),
			'newParticipantPin'	=> urlencode($newParticipantPin)
		  );
 

          return  $this->_GET($url,$fields1);
	}
  

	function GetConferenceConfiguration ($confRef, $bridge)
	{
		$connection     = $this->_SoapClient($this->WAPI_service_url);
		
		$params = array(
			'login'				=> $this->WAPI_login,
			'password'			=> $this->WAPI_password,
			'bridgeID'			=> $bridge,
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


	function ConfigureConference6200($conferenceRef, $enterTone, $leavingTone,  $QandA, $rollCall, $nameOnEntry, $nameOnExit, $waitForModerator, $blockDialOut)
	{	$url = $this->WAPI_service_url . '/ConfigureConference6200';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'conferenceRef'		=> urlencode($conferenceRef),
			'enterTone'			=> urlencode($enterTone),
			'leavingTone'		=> urlencode($leavingTone),
			'QandA'				=> urlencode($QandA),
			'rollCall'			=> urlencode($rollCall),
			'nameOnEntry'		=> urlencode($nameOnEntry),
			'nameOnExit'		=> urlencode($nameOnExit),
			'waitForModerator'	=> urlencode($waitForModerator),
			'blockDialOut'		=> urlencode($blockDialOut)
		  );

 
          return  $this->_GET($url,$fields1);
	}


	function UpdateConferenceBillingCode($conferenceRef, $billingCode)
	{	$url = $this->WAPI_service_url . '/UpdateConferenceBillingCode';
		 

		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'conferenceRef'		=> urlencode($conferenceRef),
			'billingCode'		=> urlencode($billingCode)
		  );

 
          return  $this->_GET($url,$fields1);
	}


	function UpdateConference($ConferenceRef, $Name, $Duration, $Participants, $data, $ContactName)
	{	$url = $this->WAPI_service_url . '/UpdateConference';
		 
		if (!$data) $data = "None";
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'ConferenceRef'		=> urlencode($ConferenceRef),
			'Name'				=> urlencode($Name),
			'Duration'			=> urlencode($Duration),
			'Participants'		=> urlencode($Participants),
			'data'				=> urlencode($data),
			'ContactName'		=> urlencode($ContactName)
		  );

 
		 
          return  $this->_GET($url,$fields1);
	}

	function UpdateConference6200($ConferenceRef, $Name, $Duration, $Participants, $data)
	{	$url = $this->WAPI_service_url . '/UpdateConference6200';
		if (!$data) $data = "None"; 

		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'ConferenceRef'		=> urlencode($ConferenceRef),
			'Name'				=> urlencode($Name),
			'Duration'			=> urlencode($Duration),
			'Participants'		=> urlencode($Participants),
			'data'				=> urlencode($data) 
		  );

 

          return  $this->_GET($url,$fields1);
	}


	function UpdateConferenceScheduleTime($conferenceRef, $newStartDateTimeUTC)
	{	$url = $this->WAPI_service_url . '/UpdateConferenceScheduleTime';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'conferenceRef'		=> urlencode($conferenceRef),
			'newStartDateTimeUTC'	=> urlencode($newStartDateTimeUTC) 
		  );

 

          return  $this->_GET($url,$fields1);
	}


 
	function DeleteCompany($CompanyRef, $bridge)
	{	$url = $this->WAPI_service_url . '/DeleteCompany';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'CompanyRef'		=> urlencode($CompanyRef)
		  );

 

          return  $this->_GET($url,$fields1);
	}

	function DeleteConference($ConferenceRef, $bridge)
	{	$url = $this->WAPI_service_url . '/DeleteConference';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'ConferenceRef'		=> urlencode($ConferenceRef)
		  );

 

          return  $this->_GET($url,$fields1);
	}
 
	function CreateConferenceFromModel($modelRef, $name, $demand, $startDateTimeUTC, $duration, $participants, $BillingCode)
	{	$url = $this->WAPI_service_url . '/CreateConferenceFromModel';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'modelRef'			=> urlencode($modelRef), 
			'name'				=> urlencode($name), 
			'demand'			=> urlencode($demand), 
			'startDateTimeUTC'		=> urlencode($startDateTimeUTC), 
			'duration'			=> urlencode($duration), 
			'participants'		=> urlencode($participants), 
			'billingCode'		=> urlencode($BillingCode)
		  );

		 

          return  $this->_GET($url,$fields1);
	}


	function CreateConferenceFromModel6200($modelRef, $name, $demand, $startDateTimeUTC, $duration, $participants, $BillingCode)
	{	$url = $this->WAPI_service_url . '/CreateConferenceFromModel6200';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'modelRef'			=> urlencode($modelRef), 
			'name'				=> urlencode($name), 
			'demand'			=> urlencode($demand), 
			'startDateTimeUTC'	=> urlencode($startDateTimeUTC), 
			'duration'			=> urlencode($duration), 
			'participants'		=> urlencode($participants), 
			'BillingCode'		=> urlencode($BillingCode)
		  );

 

          return  $this->_GET($url,$fields1);
	}
 
	function MoveConferenceToClient($conferenceRef, $newClientRef)
	{	$url = $this->WAPI_service_url . '/MoveConferenceToClient';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'conferenceRef'		=> urlencode($conferenceRef), 
			'newClientRef'		=> urlencode($newClientRef) 
		  );

 

          return  $this->_GET($url,$fields1);
	}


	function ReEnableConference($conferenceRef)
	{	$url = $this->WAPI_service_url . '/ReEnableConference';
		 
		$fields1 = Array();
		
		$fields1 = array(
			'login'				=> urlencode($this->WAPI_login),
			'password'			=> urlencode($this->WAPI_password),
			'bridgeID'			=> urlencode($this->WAPI_bridge),
			'conferenceRef'		=> urlencode($conferenceRef) 
		  );

          return  $this->_GET($url,$fields1);
    }
    
    /* common local function for Curl operation */
    function _GET($url,$params){
        $fields_string1='';
        foreach($params as $key=>$value) 
		{	$fields_string1 .= $key.'='.$value.'&'; 
		} 
		 
		$ch1 = curl_init();
		curl_setopt($ch1,CURLOPT_URL, $url);
		curl_setopt($ch1,CURLOPT_POST, count($params));
		curl_setopt($ch1,CURLOPT_POSTFIELDS, $fields_string1);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true); 
		$result	 = curl_exec($ch1);					
		$responceData		= new SimpleXMLElement($result);
		curl_close($ch1);		
        return $responceData;   

    }

	function _SoapClient($url){
		return $connection = new SoapClient($url, array('trace' => 1));
	}
     
}