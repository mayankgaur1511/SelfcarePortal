<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bluesky_model extends CI_Model {


    function __construct()
    {
        $this->API_USER = $this->session->userdata('blueskyUserId');
        $this->API_PASSWORD = $this->session->userdata('password');
    }

    /* 
     * Primary Group functions
     */

	function getLogo()
    {
        return $this->_GET("logo");
    }

    function getPrimaryGroups($start,$limit)
    {
        return $this->_GET("primary_group?start={$start}&limit={$limit}");
    }

    function getPrimaryGroup($primaryGroupId)
    {
        return $this->_GET("primary_group/{$primaryGroupId}");
    }

    /* 
     * Subscription functions
     */

    function getSubscriptions($primaryGroupId)
    {
        return $this->_GET("primary_group/{$primaryGroupId}/subscription");
    }

    function getSubscription($subscriptionId)
    {
        return $this->_GET("subscription/{$subscriptionId}");
    }

    /* 
     * Client functions
     */

    function CreateUser($API_USER, $API_PASSWORD, $primaryGroupId, $email, $firstName, $lastName, $phoneNumber, $language, $country, $timezone)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = Array(
			"login"			=> $email,
			"email"			=> $email,
			"firstName"		=> $firstName,
			"lastName"		=> $lastName,
			"phoneNumber"	=> $phoneNumber,
			"communicationLanguage"	=>  $language,
			"country"		=> $country,
			"timeZone"		=> $timezone
        );

   
        return $this->_POST("primary_group/{$primaryGroupId}/user",json_encode($params));
    }

    function updateUser($API_USER, $API_PASSWORD, $userId, $email, $firstName, $lastName, $phoneNumber, $language, $country, $timezone)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = Array(
			"login"			=> $email,
			"email"			=> $email,
			"firstName"		=> $firstName,
			"lastName"		=> $lastName,
			"phoneNumber"	=> $phoneNumber,
			"communicationLanguage"	=>  $language,
			"country"		=> $country,
			"timeZone"		=> $timezone
		);

        return $this->_PATCH("user/{$userId}",json_encode($params));
    }

    function getUsers($primaryGroupId, $start = 0, $limit = 10)
    {  
       return $this->_GET("primary_group/{$primaryGroupId}/user?start={$start}&limit={$limit}");
    }

    function getUser($userId)
    {
        return $this->_GET("user/{$userId}");
    }

    function suspendUser($API_USER, $API_PASSWORD, $userId)
    {     
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = array(
            "userStatus" => "Suspended" 
        );     

        $response = $this->_POST("user/{$userId}",json_encode($params));
       
        return $response;
       
       
    }

    function reactivateUser($API_USER, $API_PASSWORD, $userId)
    { 
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = array(
            "userStatus" => "Active" 
        );
        
        return $this->_POST("user/{$userId}",json_encode($params));
    }

    function deleteUser($API_USER, $API_PASSWORD, $userId)
    {  
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $response = $this->_DELETE("user/{$userId}");
        if ($response['status'] == 'HTTP/1.1 202 Accepted'){
            return false;
        }else{
            return $response;
        }
       
    }

    /* 
     * Access functions
     */

    function createAccess($API_USER, $API_PASSWORD, $userId, $subscriptionId, $topic, $billingCode, $moderatorPIN, $participantPIN, $IsRecurring, $accessOptions, $welcomePackSendMode)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        // Access Options: https://api.arkadin.com/help/provisioning/v1/ResourceModel?modelName=AccessOptionModel

        $params = Array(
			"subscriptionId"	    => $subscriptionId,
			"topic"				    => $topic,
			"billingCode"		    => $billingCode,
            "welcomePackSendMode"	=> $welcomePackSendMode,
            "IsRecurring"           => $IsRecurring,
        );
        
        if($moderatorPIN){$params['moderatorPIN'] = $moderatorPIN;}
        if($participantPIN){$params['participantPIN'] = $participantPIN;}
        if($accessOptions){$params['accessOptions'] = $accessOptions;}

        print "<PRE>" . json_encode($params, JSON_PRETTY_PRINT) . "</PRE>";

        return $this->_POST("user/{$userId}/access",json_encode($params));
    }

    function updateAccess($API_USER, $API_PASSWORD, $accessId, $topic, $billingCode, $accessOptions, $resetModeratorPin, $startDate, $duration, $WelcomePackSendMode)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        // Access Options: https://api.arkadin.com/help/provisioning/v1/ResourceModel?modelName=AccessOptionModel

        $params = Array(
            "topic"		            => $topic,
            "billingCode"	        => $billingCode,
			"startDate"		        => $startDate,
            "duration"		        => $duration,
			"resetModeratorPin"		=> $resetModeratorPin,
			"resetParticipantPin"	=> $resetParticipantPin,
            "accessOptions"	        => $accessOptions,
            "welcomePackSendMode"	=> $welcomePackSendMode,
        );

        return $this->_PATCH("access/{$accessId}",json_encode($params));
    }

    function deleteAccess($API_USER, $API_PASSWORD, $accessId)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        return $this->_DELETE("access/{$accessId}");
    }

    function createScheduledConference($API_USER, $API_PASSWORD, $accessId, $startDate, $duration, $topic, $billingCode, $moderatorPIN, $participantPIN, $accessOptions, $WelcomePackSendMode)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = Array(
			"startDate"	=> $startDate,
			"duration"	=> $duration,
			"topic"			=> $topic,
			"billingCode"	=> $billingCode,
			"welcomePackSendMode" => $WelcomePackSendMode
        );

        if($moderatorPIN){$params['moderatorPIN'] = $moderatorPIN;}
        if($participantPIN){$params['participantPIN'] = $participantPIN;}
        if($accessOptions){$params['accessOptions'] = $accessOptions;}
        

        print $accessId;

        $response = $this->_POST("access/{$accessId}/scheduled",json_encode($params));

        var_dump($response);
        return $response;
    }

    function getAccess($accessId)
    {
        return $this->_GET("access/{$accessId}");
    }

    function getAccesses($userId)
    {
        return $this->_GET("user/{$userId}/access");
    }


    function GetConferencesByClientRef($userId)
	{	
		
        $getAccess = $this->_GET("user/{$userId}/access");
        
        if(trim($getAccess['status'])=='HTTP/1.1 200 OK'){
                $body_decoded = json_decode($getAccess['body']);
                $total_access =  count($body_decoded);

            $ARR_ACCESSES = Array();
		
            for ($i = 0; $i < $total_access; $i++)
            {	
                $getConferences=$this->_GET("access/" . $body_decoded[$i]->accessId);
                if(trim($getConferences['status'])=='HTTP/1.1 200 OK'){
                    $conf_body_decoded = json_decode($getConferences['body']);
                    Array_Push($ARR_ACCESSES, $conf_body_decoded);
                }
            }
            return json_encode($ARR_ACCESSES);
         }else{
             return false;
         }
	}

    function createRecurringOccurrences($API_USER, $API_PASSWORD, $accessId,$occurrences)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        return $this->_POST("access/{$accessId}/recurring",json_encode($occurrences));
    }
   
    function getRecurringOccurrences($accessId, $start = 0, $limit = 100)
    {
        return $this->_GET("access/{$accessId}/recurring?start={$start}&limit={$limit}");
    }

    function QueuedOperation($API_USER, $API_PASSWORD, $orderId)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        return $this->_GET("queued_operation/{$orderId}");
    }

    function updateRecurringOccurrences($API_USER, $API_PASSWORD, $accessId,$occurrences)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        /* Occurence: array with startDate and duration (Mins)
            array(
                'recurringId' => '12142452'
                'startDate' => '2018-01-26T05:29:12',
                'duration' => 30
            )
        */
        return $this->_POST("access/{$accessId}/recurring",json_encode($occurrences));
    }

    function deleteRecurringOccurrence($API_USER, $API_PASSWORD, $accessId,$occurrenceId)
    {
        $this->API_USER = $API_USER;
        $this->API_PASSWORD = $API_PASSWORD;

        $params = array(
            $occurrenceId
        );

        return $this->_DELETE("access/{$accessId}/recurring",$params);
    }


    ### Generic Get and Post request methods

    function _GET($uri)
    {
        $headers = array(
			'Content-Type:application/json',
			'Authorization: Basic ' . base64_encode($this->API_USER . ":" . $this->API_PASSWORD),
			'Cache-Control: no-cache'
        );

        $url = "https://api.arkadin.com/provisioning/v1/" . $uri;
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = trim(substr($response, 0, $header_size));
		$body = trim(substr($response, $header_size));
		
		curl_close($ch);
 

		$header_lines = explode("\r\n", $header);

		return Array(
            "header"	=> $header,
			"body"		=> $body,
			"status"	=> $header_lines[0]
		);
    }

    function _POST($uri,$parameters)
    {
        log_message("info", "OO - {$this->API_USER}, {$this->API_PASSWORD} " . json_encode($parameters));
        $headers = array(
			'Content-Type:application/json',
			'Authorization: Basic ' . base64_encode($this->API_USER . ":" . $this->API_PASSWORD),
			'Cache-Control: no-cache',
			'Accept-Asynchronous: poll',
			'Content-Length: ' . strlen($parameters)   
		);

        $url = "https://api.arkadin.com/provisioning/v1/" . $uri;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");         
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = trim(substr($response, 0, $header_size));
		$body = trim(substr($response, $header_size));
        curl_close($ch);
        
		$header_lines = explode("\r\n", $header);
		
		$accepted = false;
		$poll_url = "";
		$poll_after = 0;
		$status_fetched = '';

		foreach($header_lines as $key => $line)
		{	
            if (!$accepted)
			{	
                if ($line == 'HTTP/1.1 202 Accepted')
				{	$accepted = true;
					$status_fetched = $line;
                }   
			}
			else
			{	
                if ($line)
				{	
                    list($meta, $content) = explode(":", $line);
					
					switch($meta)
					{	
                        case "Location":
							$poll_url = trim($content);
							break;
						case "Retry-After":
							$poll_after = trim($content);
							break;
					}
				}
			}
        }

		return Array(
			'accepted'	=> $accepted,
			'location'	=> $poll_url,
			'pollafter'	=> $poll_after,
            'status'	=> $status_fetched,
            'header'    => $header,
            'body'      => $body
		);
    }

    function _PATCH($uri,$parameters)
    {
        $headers = array(
			'Content-Type:application/json',
			'Authorization: Basic ' . base64_encode($this->API_USER . ":" . $this->API_PASSWORD),
            'Cache-Control: no-cache',
            'X-HTTP-Method-Override: PATCH',
			'Accept-Asynchronous: poll',
			'Content-Length: ' . strlen($parameters)
		);

        $url = "https://api.arkadin.com/provisioning/v1/" . $uri;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");         
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = trim(substr($response, 0, $header_size));
		$body = trim(substr($response, $header_size));
        curl_close($ch);
        
		$header_lines = explode("\r\n", $header);
		
		$accepted = false;
		$poll_url = "";
		$poll_after = 0;
		$status_fetched = '';

		foreach($header_lines as $key => $line)
		{	
            if (!$accepted)
			{	
                if ($line == 'HTTP/1.1 202 Accepted')
				{	$accepted = true;
					$status_fetched = $line;
                }
                
			}
			else
			{	
                if ($line)
				{	
                    list($meta, $content) = explode(":", $line);
					
					switch($meta)
					{	
                        case "Location":
							$poll_url = trim($content);
							break;
						case "Retry-After":
							$poll_after = trim($content);
							break;
					}
				}
			}
        }

		return Array(
			'accepted'	=> $accepted,
			'location'	=> $poll_url,
			'pollafter'	=> $poll_after,
            'status'	=> $status_fetched,
            'header'    => $header,
            'body'      => $body
		);
    }

    function _DELETE($uri, $parameters = false)
    {
        $headers = array(
			'Content-Type:application/json',
			'Authorization: Basic ' . base64_encode($this->API_USER . ":" . $this->API_PASSWORD),
            'Cache-Control: no-cache',
			'Accept-Asynchronous: poll',
		);

        $url = "https://api.arkadin.com/provisioning/v1/" . $uri;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");         
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        if($parameters)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        }

		$response = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = trim(substr($response, 0, $header_size));
		$body = trim(substr($response, $header_size));
        curl_close($ch);
        
		$header_lines = explode("\r\n", $header);
		
		$accepted = false;
		$poll_url = "";
		$poll_after = 0;
		$status_fetched = '';

		foreach($header_lines as $key => $line)
		{	
            if (!$accepted)
			{	
                if ($line == 'HTTP/1.1 202 Accepted')
				{	$accepted = true;
					$status_fetched = $line;
                }
                
			}
			else
			{	
                if ($line)
				{	
                    list($meta, $content) = explode(":", $line);
					
					switch($meta)
					{	
                        case "Location":
							$poll_url = trim($content);
							break;
						case "Retry-After":
							$poll_after = trim($content);
							break;
					}
				}
			}
        }

		return Array(
			'accepted'	=> $accepted,
			'location'	=> $poll_url,
			'pollafter'	=> $poll_after,
            'status'	=> $status_fetched,
            'header'    => $header,
            'body'      => $body
		);
    }
}