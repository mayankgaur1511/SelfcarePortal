<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderOrchestration extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model', 'order');
        $this->load->model('Suborder_model','subOrder');
        $this->load->model('Countrymaster_model','country');
        $this->load->model('Timezone_model','timezone');
        $this->load->model('Language_model','language');
        $this->load->model('W2hierarchy_model','w2hierarchy');
    }

    function new($orderType)
    {
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        
        // Order Data
        $data = $this->input->post();
        
        if(!isset($data['billingSystem']) or !$data['billingSystem']){
            $this->output->set_status_header(400)->_display();
            exit(); 
        }

        $data['API_USER'] = $this->session->userdata('blueskyUserId');
        $data['API_PASSWORD'] = $this->session->userdata('password');

        $orderId = $this->order->add($orderType, $this->encrypt->decode($data['billingSystem']), json_encode($data));

        // Create Suborders
        switch($orderType)
        {
            case 'NEW-USER':
                // Common Fields
                if(!isset($data['firstName']) or !isset($data['lastName']) or !isset($data['email'])
                    or !isset($data['countryId']) or !isset($data['phone']) or !isset($data['timezoneId'])
                    or !isset($data['languageId']) or !isset($data['primaryGroup']) or !isset($data['product'])){
                        $this->output->set_status_header(400)->set_output("MISSING FIELD")->_display();
                        exit(); 
                }
                
                // Wise2 Fields
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    if(!isset($data['spirit'])){
                        $this->output->set_status_header(400)->set_output("SPIRIT IS A REQUIRED FIELD")->_display();
                        exit();
                    }
                }

                // Create Sub Orders
                $this->subOrder->add($orderId, 'ADD USER','WAITING');
                $this->subOrder->add($orderId, 'ADD AUDIO ACCESS');
                
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    if($data['product'] == "Audio+Webex")
                    {
                        $this->subOrder->add($orderId, 'ADD WEBEX ACCESS');
                    }
                    $this->subOrder->add($orderId, 'CONFIGURE AUDIO ACCESS');
                    $this->subOrder->add($orderId, 'SEND WELCOME PACK');
                    
                }
                break;

            case 'NEW-ACCESS':
                if(!isset($data['conferenceTitle'])){
                    $this->output->set_status_header(400)->set_output("MISSING FIELD")->_display();
                    exit(); 
                }
                if($this->encrypt->decode($data['billingSystem']) == "BlueSky" and $data['conferenceType'] == "scheduled")
                {
                    if(!isset($data['demandAccess']) or !isset($data['conferenceTitle'])){
                        $this->output->set_status_header(400)->set_output("MISSING FIELD")->_display();
                        exit(); 
                    }
                }
                    
                // Wise2 Fields
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    if(!isset($data['spirit']) or !isset($data['userId']) or !isset($data['countryId']) or !isset($data['timezoneId']) or 
                        !isset($data['languageId']) or !isset($data['primaryGroup']) or !isset($data['product'])){
                        $this->output->set_status_header(400)->set_output("MISSING FIELD")->_display();
                        exit();
                    }
                }

                // Create Sub Order
                $this->subOrder->add($orderId, 'ADD AUDIO ACCESS', 'WAITING');
                
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    if($data['product'] == "Audio+Webex")
                    {
                        $this->subOrder->add($orderId, 'ADD WEBEX ACCESS');
                    }
                    $this->subOrder->add($orderId, 'CONFIGURE AUDIO ACCESS');
                    $this->subOrder->add($orderId, 'SEND WELCOME PACK');
                }

                if($this->encrypt->decode($data['billingSystem']) == "BlueSky")
                {
                    if(isset($data['conferenceType']) and $data['conferenceType'] == "recurring")
                    {
                        $this->subOrder->add($orderId, 'ADD RECURRING OCCURENCE');
                    }
                }

                // Decode User Id
                $data['userId'] = $this->encrypt->decode($data['userId']);
                $this->order->update($orderId,array('data' => json_encode($data)));
                break;

            case 'MODIFY-ACCESS':
                if(!isset($data['conferenceTitle'])){
                    $this->output->set_status_header(400)->set_output("MISSING FIELD_1")->_display();
                    exit(); 
                }
                if($this->encrypt->decode($data['billingSystem']) == "BlueSky" and $data['conferenceType'] == "scheduled")
                {
                    if(!isset($data['demandAccess']) or !isset($data['conferenceTitle'])){
                        $this->output->set_status_header(400)->set_output("MISSING FIELD_2")->_display();
                        exit(); 
                    }
                }
                    
                // Wise2 Fields
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    if(!isset($data['primaryGroup'])){
                        $this->output->set_status_header(400)->set_output("MISSING FIELD_3")->_display();
                        exit();
                    }
                }



                // Create Sub Order
                $this->subOrder->add($orderId, 'MODIFY ACCESS', 'WAITING');
                
                if($this->encrypt->decode($data['billingSystem']) == "Wise2")
                {
                    $this->subOrder->add($orderId, 'CONFIGURE AUDIO ACCESS');
                }

                // Decode Access Id
                $data['accessId'] = $this->encrypt->decode($data['accessId']);
                $this->order->update($orderId,array('data' => json_encode($data)));
                break;

            case 'DISABLE-ACCESS':
                if(!isset($data['accessId']) or !isset($data['primaryGroup']) or !isset($data['billingSystem'])){
                    $this->output->set_status_header(400)->_display();
                    exit();
                }

                // Create Sub Order
                $this->subOrder->add($orderId, 'DISABLE ACCESS', 'WAITING');
                break;

            case 'MODIFY-USER':
                if(!isset($data['userId']) or !isset($data['billingSystem']) or !isset($data['primaryGroup'])){
                    $this->output->set_status_header(400)->_display();
                    exit();
                }
                // Create Sub Order
                $this->subOrder->add($orderId, 'MODIFY USER', 'WAITING');
                break;
            case 'NEW-OCCURRENCES':
                if(!isset($data['accessId']) or !isset($data['billingSystem']) or !isset($data['recurringType']) or
                    !isset($data['startDate']) or !isset($data['endDate']) or !isset($data['duration'])){
                    $this->output->set_status_header(400)->set_output("MISSING FIELDS")->_display();
                    exit();
                }
                // Create Sub Order
                $this->subOrder->add($orderId, 'ADD RECURRING OCCURENCE', 'WAITING');
                // Decode Access Id
                $data['accessId'] = $this->encrypt->decode($data['accessId']);
                $this->order->update($orderId,array('data' => json_encode($data)));
                break;

            case 'MODIFY-OCCURRENCE':
                if(!isset($data['accessId']) or !isset($data['billingSystem']) or !isset($data['startDate']) or
                    !isset($data['duration']) or !isset($data['instanceId'])){
                    $this->output->set_status_header(400)->set_output("MISSING FIELDS")->_display();
                    exit();
                }
                // Create Sub Order
                $this->subOrder->add($orderId, 'MODIFY RECCURRING OCCURENCE', 'WAITING');
                // Decode Access Id
                $data['accessId'] = $this->encrypt->decode($data['accessId']);
                $this->order->update($orderId,array('data' => json_encode($data)));
                break;

            default:
                $this->output->set_status_header(400)->_display("UNKNOWN METHOD");
                exit();
        }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(array('orderId' => $orderId)));
    }

    function queue()
    {
        // CHECK IF QUEUE IS STILL RUNNING
        log_message("info","Order Orchestration - Starting Queue");
        $this->load->model("Queuestatus_model");
        if(!$this->Queuestatus_model->run()){
            log_message("info","Order Orchestration - Queue still running");
            //exit();
        }

        // Get Opened suborders
        foreach($this->subOrder->getOpenSubOrders() as $subOrder)
        {
            log_message("info","Order Orchestration - Processing Sub Order #{$subOrder->id}");

            $error = false;
            $order = $this->order->get($subOrder->orderId);
            $data = json_decode($order->data);

            if($order->billingSystem == "BlueSky")
            {
                switch($subOrder->status)
                {
                    case "PENDING":
                        $this->load->model('Bluesky_model','bluesky');
                           
                        if($response = $this->bluesky->QueuedOperation($data->API_USER, $data->API_PASSWORD, $subOrder->blueSkyOrderId))
                        {
                            switch(json_decode($response["body"])->queuedOperationStatus)
                            {
                                case "Done":
                                    $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                                    
                                    if($subOrder->orderType == "ADD USER"){ $data->userId = $this->_getOrderUserId($response['header']); }
                                    if($subOrder->orderType == "ADD AUDIO ACCESS"){ $data->accessId = $this->_getOrderAccessId($response['header']); }
                                    if($subOrder->orderType == "DISABLE ACCESS"){ $data->accessId = $this->_getOrderAccessId($response['header']); }
                                    if($subOrder->orderType == "MODIFY USER"){ $data->userId = $this->_getOrderUserId($response['header']); }
                                    if($subOrder->orderType == "MODIFY ACCESS"){ $data->accessId = $this->_getOrderAccessId($response['header']); }
                                    break;
                                    
                                case "Processing":
                                case "Frozen":
                                    $this->subOrder->update($subOrder->id, array('status' => 'PENDING'));
                                    $bsPending = true;
                                    break;

                                case "Error":
                                    $error = true;
                            }
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case "WAITING":
                    case "ERROR":
                        $this->load->model('Bluesky_model','bluesky');
                        if($subOrder->orderType == "ADD USER"){ $bsOrderId = $this->_NewClient($subOrder->id); }
                        if($subOrder->orderType == "ADD AUDIO ACCESS"){ $bsOrderId = $this->_NewAudioAccess($subOrder->id); }
                        if($subOrder->orderType == "DISABLE ACCESS"){ $bsOrderId = $this->_DisableAudioAccess($subOrder->id); }
                        if($subOrder->orderType == "MODIFY USER"){ $bsOrderId = $this->_ModifyClient($subOrder->id); }
                        if($subOrder->orderType == "MODIFY ACCESS"){ $bsOrderId = $this->_ModifyAudioAccess($subOrder->id); }
                        if($subOrder->orderType == "ADD RECURRING OCCURENCE"){ $bsOrderId = $this->_NewRecurringOccurence($subOrder->id); }
                        if($subOrder->orderType == "MODIFY RECCURRING OCCURENCE"){ $bsOrderId = $this->_ModifyRecurringInstance($subOrder->id); }


                        if($bsOrderId)
                        {
                            $this->subOrder->update($subOrder->id, array('blueSkyOrderId' => $bsOrderId, 'status' => 'PENDING'));
                            $bsPending = true;
                            }
                        else{
                            $error = true;
                        }
                        break;
                }
            }

            if($order->billingSystem == "Wise2")
            {
                switch($subOrder->orderType)
                {
                    case 'ADD USER':
                        if($data->userId =  $this->_NewClient($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'ADD AUDIO ACCESS':
                        if($data->access =  $this->_NewAudioAccess($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                            $data->accessId = $data->access->conferenceRef;
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'CONFIGURE AUDIO ACCESS':
                        if($this->_ConfigureAudioAccess($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'SEND WELCOME PACK':
                        if($this->_SendWelcomePack($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'DISABLE ACCESS':
                        if($this->_DisableAudioAccess($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'MODIFY USER':
                        if($this->_ModifyClient($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;

                    case 'MODIFY ACCESS':
                        if($this->_ModifyAudioAccess($subOrder->id)){
                            $this->subOrder->update($subOrder->id, array('status' => 'COMPLETED'));
                        }
                        else{
                            $error = true;
                        }
                        break;
                }
            }

            // IF SUB ORDER COMPLETES, UPDATE NEXT ORDER TO WAITING STATUS
            if(!isset($error) or (isset($error) and !$error))
            {
                if(!isset($bsPending) or !$bsPending)
                {
                    $nextSubOrder = $this->subOrder->getOpenSubOrders($subOrder->orderId);
                    $this->order->update($subOrder->orderId, array('data' => json_encode($data)));
                    if($nextSubOrder)
                    {
                        $this->subOrder->update($nextSubOrder[0]->id, array('status' => 'WAITING'));
                        $this->order->update($subOrder->orderId, array('status' => 'PENDING'));
                    }
                    else{
                        $this->order->update($subOrder->orderId, array('status' => 'COMPLETED'));
                    }
                }
                else{
                    $this->order->update($subOrder->orderId, array('status' => 'PENDING'));
                }
            }
            else{
                // IF SUB ORDER FAILS, INCREASE ATTEMPTS AND CHANGE STATUS TO ERROR
                $this->subOrder->update($subOrder->id, array('status' => 'ERROR', 'attempts' => $subOrder->attempts + 1));
                if($subOrder->attempts >= 2){
                    $this->order->update($subOrder->orderId, array('status' => 'ERROR'));
                }
            }
        }

        // CLEAN STATUS
        $this->Queuestatus_model->clean();
    }

    function _NewClient($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        $country =  $this->country->get($data->countryId);
        $language = $this->language->get($data->languageId);
        $timezone = $this->timezone->get($data->timezoneId);
        
        // CREATE CLIENT
        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));
            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                try{
                    return $this->wapit->CreateClient($primaryGroup->SiteId, $primaryGroup->Bridge, false, 
                        $data->firstName . " " . $data->lastName, $data->phone, $data->email);
                }
                catch(Exception $e)
                {
                    return false;
                }
            }
            else{
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);
                $this->load->model("Wapi_model","wapit");
                try{
                    return $this->wapit->CreateClient($primaryGroup->SiteId, $bridgeId, false, 
                        $data->firstName . " " . $data->lastName, $data->phone, $data->email);
                }
                catch(Exception $e)
                {
                    return false;
                }
            }
        }

        if($order->billingSystem == "BlueSky")
        {
            $this->load->model("Bluesky_model","bluesky");
            if($bsOrder = $this->bluesky->CreateUser($data->API_USER, $data->API_PASSWORD, $this->encrypt->decode($data->primaryGroup), $data->email, $data->firstName,
                $data->lastName, $data->phone, $language->bluesky, $country->countryCodeBS, $timezone->timeZoneBS))
            {
                log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));
                
                if($bsOrder['location'])
                {
                    return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                }
                return false;
            }
            else
            {
                return false;
            }
        }
    }

    function _NewAudioAccess($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);
        // CREATE CONFERENCE
        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));

            $country =  $this->country->get($data->countryId)->countryCode;
            $language = $this->language->get($data->languageId)->wise2;
            $timezone = $this->timezone->get($data->timezoneId);

            if(!isset($data->conferenceTitle) or (isset($data->conferenceTitle) and !$data->conferenceTitle))
            {
                $conferenceTitle = $data->firstName . " " . $data->lastName;
            }
            else{
                $conferenceTitle = $data->conferenceTitle;
            }

            if(isset($data->conferenceType) and $data->conferenceType == "scheduled")
            {
                $isDemand = false;
                $startDate = $data->startDate;
                $duration = $data->duration;
            }
            else{
                $isDemand = true;
                $startDate = null;
                $duration = 0;
            }

            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                return $this->wapit->CreateConference($data->userId, $primaryGroup->Bridge, $conferenceTitle, 
                    $isDemand, null, 0, $data->participants, 'MeetMe', $language, $conferenceTitle, $data->billingCode,
                    null, null, $country, $data->audioOptions);
            }
            else{
                $language = $this->language->get($data->languageId)->avaya;
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);
                $this->load->model("Wapi_model","wapit");

                return $this->wapit->CreateConference($data->userId, $bridgeId, $conferenceTitle, 
                    $isDemand, null, 0, $data->participants, 'MeetMe', $language, $conferenceTitle, $data->billingCode,
                    null, null);
            }
        }

        if($order->billingSystem == "BlueSky")
        {
            $this->load->model("Bluesky_model","bluesky");

            // CHECK IF ACCEESS IS ON OTP
            if(isset($data->conferenceType) and $data->conferenceType == "scheduled")
            {
                if($bsOrder = $this->bluesky->createScheduledConference($data->API_USER, $data->API_PASSWORD,$this->encrypt->decode($data->demandAccess), $data->startDate, $data->duration, 
                    $data->conferenceTitle, $data->billingCode, null, null, $data->audioOptions, 'Instantly'))
                {
                    log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                    if($bsOrder['location'])
                    {
                        return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                    }
                    return false;
                }
                else
                {
                    return false;
                }
            }
            else{
                if(isset($data->conferenceType) and $data->conferenceType == "recurring")
                {
                    $isRecurring = true;
                }
                else{
                    $isRecurring = false;
                }

                if($bsOrder = $this->bluesky->CreateAccess($data->API_USER, $data->API_PASSWORD,$data->userId, $data->product, '', $data->billingCode, null, null,
                $isRecurring, $data->audioOptions, 'Instantly'))
                {
                    log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                    if($bsOrder['location'])
                    {
                        return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                    }
                    return false;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    function _ConfigureAudioAccess($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);
        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));

            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                try{
                    return $this->wapit->ConfigureConference($data->accessId,$primaryGroup->Bridge, $data->audioOptions->broadCast, $data->audioOptions->enterTone, 
                        $data->audioOptions->leavingTone, $data->audioOptions->QandA, $data->audioOptions->rollCall, $data->audioOptions->nameOnEntry, 
                        $data->audioOptions->nameOnExit, $data->audioOptions->waitForModerator, $data->audioOptions->blockDialOut, 
                        $data->audioOptions->recording);
                }
                catch(Exception $e)
                {
                    return false;
                }
            }
            else{
                $this->load->model("Wapi_model","wapi");
                try{
                    return $this->wapi->ConfigureConference($data->accessId,$primaryGroup->Bridge, $data->audioOptions->broadcast, $data->audioOptions->enterTone, 
                        $data->audioOptions->leavingTone, $data->audioOptions->QandA, $data->audioOptions->rollCall, $data->audioOptions->nameOnEntry, 
                        $data->audioOptions->nameOnExit, $data->audioOptions->waitForModerator, $data->audioOptions->blockDialOut, 
                        $data->audioOptions->recording);
                }
                catch(Exception $e)
                {
                    return false;
                }
            }
        }
    }

    // TO BE IMPLEMENTED
    function _NewWebexAccess($subOrderId)
    {
        /*
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);
        
        $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));

        $country =  $this->country->get($data->countryId)->countryCode;
        $language = $this->language->get($data->languageId)->wise2;
        */
        
    }

    function _SendWelcomePack($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);
        
        $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));

        $country =  $this->country->get($data->countryId)->countryCode;
        $language = $this->language->get($data->languageId)->wise2;

        if(isset($data->spirit) and $data->spirit > 0)
        {
            $this->load->model("Spirit_model","spirit");
            $wp = $this->spirit->getCustomerWelcomeEmailTemplates($data->spirit,$data->product, $country);
            if(isset($wp->Id) and $wp->Id)
            {
                $template = $this->spirit->getWelcomeEmailBody($wp->Id);
            }
        }

        if(!isset($template) or (isset($template) and !$template))
        {
            switch($data->product)
            {
                case "Audio":
                    $wp_data['anytime'] = true;
                    break;
                case "Audio+Anywhere":
                    $wp_data['anytime'] = true;
                    $wp_data['anywhere'] = true;
                    break;
                case "Audio+Webex":
                    $wp_data['anytime'] = true;
                    $wp_data['webex'] = true;
                    break;
            }
            
            // Get numbers
            $this->load->model("Anywhere_model","anywhere");
            if($primaryGroup->Product == "Anytime")
            {
                $DDIs = $this->anywhere->selectDDI($primaryGroup->SiteId, $country, $primaryGroup->Extension);
                $extension = $primaryGroup->Extension;
            }
            else{
                $DDIs = $this->anywhere->selectDDI($primaryGroup->SiteId, $country);
                $extension = "";
            }
            $wp = new stdClass();
            $wp->Toll = $DDIs['toll'];
            $wp->TollFree = $DDIs['tollFree'];
            $wp->Subject = "Your Arkadin credentials";
            $wp->SenderEmail = "noreply@arkadin.com";
            
            if(!$wp->Toll and !$wp->TollFree)
            {
                $wp_data['no_ddi'] = true;
            }

            $weblogin = $data->access->conferenceRef;
            $participantPin = $data->access->participantPin;

            $wp->Toll = ($wp->Toll ? $wp->Toll : "<a href='https://portal.conferencing-tools.com/mobile/GlobalNum.aspx?WebLogin={$weblogin}&PPIN={$participantPin}'>Go to list</a>");
            $wp->TollFree = ($wp->TollFree ? $wp->TollFree : "<a href='https://portal.conferencing-tools.com/mobile/GlobalNum.aspx?WebLogin={$weblogin}&PPIN={$participantPin}'>Go to list</a>");

            $template = $this->load->view('welcomePack/skeleton', $wp_data, true);

            log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "message" => "Welcome pack is default, DDIs gotten from Anywhere.")));
        }
        else{
            log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "message" => "Welcome pack and DDIs gotten from Spirit.")));
        }

        if($primaryGroup->Product == "Anytime")
        {
            $extension = $primaryGroup->Extension;
        }
        else{
            $extension = "";
        }

        $this->load->model("Welcomepack_model","welcomePack");
        $html = $this->welcomePack->replace($template, $data->firstName . " " . $data->lastName, $extension . $data->access->conferenceRef,
            $data->access->ModeratorPin, $data->access->participantPin,'www.anywhereconference.com','$WEBEX_URL','$WEBEX_USER','$WEBEX_PSWD',$wp->Toll,$wp->TollFree);

        if(send_email($html, $wp->Subject, $wp->SenderEmail, array(
            array('email'=>$data->email)
        )))
        {
            return true;
        }
        return false;
    }

    function _DisableAudioAccess($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));
            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                try{
                    return $this->wapit->DeleteConference($data->accessId, $primaryGroup->Bridge);
                }
                catch(Exception $e)
                {
                    return false;
                }
                return false;
            }
        }

        if($order->billingSystem == "BlueSky")
        {
            $this->load->model("Bluesky_model","bluesky");
            if($bsOrder = $this->bluesky->deleteAccess($data->API_USER, $data->API_PASSWORD,$data->accessId))
            {
                log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));
                    
                if($bsOrder['location'])
                {
                    return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                }
                return false;
            }
            else
            {
                return false;
            }
        }
    }

    function _ModifyRecurringInstance($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        $occurrences = array(
            array(
                "recurringId" => $data->instanceId,
                "startDate" => $data->startDate,
                "duration" => $data->duration
            )
        );
       
        $this->load->model("Bluesky_model","bluesky");
        if($bsOrder = $this->bluesky->updateRecurringOccurrences($data->API_USER, $data->API_PASSWORD,$data->accessId, $occurrences))
        {
            log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));
                
            if($bsOrder['location'])
            {
                return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
            }
            return false;
        }
        else
        {
            return false;
        }
        
    }

    function _NewRecurringOccurence($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        if($order->billingSystem == "BlueSky")
        {
            $date = new DateTime($data->startDate);
            $endDate = new DateTime($data->endDate);
            
            if(isset($data->weekdays) and $data->weekdays)
            {
                while($date <= $endDate)
                {   
                    foreach($data->weekdays as $day)
                    {
                        if($date->format("D") == $day)
                        {
                            $dates[] = array(
                                'startDate' => $date->format("Y-m-d H:i:s") . "Z",
                                'duration' => $data->duration);
                                
                                var_dump(array(
                                'startDate' => $date->format("Y-m-d H:i:s") . "Z",
                                'duration' => $data->duration));
                        }
                    }
                    
                    if($data->recurringType == "biweekly"){
                        if($date->format("D") == "Sun")
                        {
                            $date->modify('+7 days');
                        }
                    }
                    $date->modify('+1 day');
                }
            }
            else{
                while($date <= $endDate)
                {   
                    $dates[] = array(
                        'startDate' => $date->format("Y-m-d H:i:s") . "Z",
                        'duration' => $data->duration);
                        
                    $date->modify('+1 day');
                }
            }

            $this->load->model("Bluesky_model","bluesky");
            if($bsOrder = $this->bluesky->createRecurringOccurrences($data->API_USER, $data->API_PASSWORD,$data->accessId, $dates))
            {
                log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                if($bsOrder['location'])
                {
                    return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                }
                return false;
            }
            else
            {
                return false;
            }
        }
    }

    function _ModifyClient($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));
            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                try{
                    return $this->wapit->UpdateClient($this->encrypt->decode($data->userId), $primaryGroup->Bridge, 
                        $data->firstName ." " . $data->lastName, $data->phone, $data->email);
                }
                catch(Exception $e)
                {
                    return false;
                }
                return false;
            }
            else{
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);
                $this->load->model("Wapi_model","wapit");
                try{
                    return $this->wapit->UpdateClient($this->encrypt->decode($data->userId), $bridgeId, 
                        $data->firstName ." " . $data->lastName, $data->phone, $data->email);
                }
                catch(Exception $e)
                {
                    return false;
                }
                return false;
            }
        }

        if($order->billingSystem == "BlueSky")
        {
            $country =  $this->country->get($data->countryId);
            $language = $this->language->get($data->languageId);
            $timezone = $this->timezone->get($data->timezoneId);
            
            $this->load->model("Bluesky_model","bluesky");
            if($bsOrder = $this->bluesky->updateUser($data->API_USER, $data->API_PASSWORD,$this->encrypt->decode($data->userId), $data->email, $data->firstName, $data->lastName, 
                $data->phone, $language->bluesky, $country->countryCodeBS, $timezone->timeZoneBS))
            {
                log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                if($bsOrder['location'])
                {
                    return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                }
                return false;
            }
            else
            {
                return false;
            }
        }
    }

    function _ModifyAudioAccess($subOrderId)
    {
        $subOrder = $this->subOrder->get($subOrderId);
        $order = $this->order->get($subOrder->orderId);
        $data = json_decode($order->data);

        if($order->billingSystem == "Wise2")
        {
            $primaryGroup = $this->w2hierarchy->getSite($this->encrypt->decode($data->primaryGroup));
            if($primaryGroup->Product == "SEP/VIPER")
            {
                $this->load->model("Wapit_model","wapit");
                if(isset($data->conferenceType) and $data->conferenceType == "scheduled")
                {
                    if(!$this->wapit->UpdateConferenceScheduleTime($accessId, $primaryGroup->Bridge, $data->startDate)){
                        return false;
                    }
                }
                else{
                    $data->duration = 0;
                }
                return $this->wapit->UpdateConference($data->accessId, $primaryGroup->Bridge, $data->conferenceTitle, $data->duration, $data->participants, $data->conferenceTitle, $data->billingCode);
            }
            else{
                $this->load->model("Wapi_model","wapit");
                if(isset($data->conferenceType) and $data->conferenceType == "scheduled")
                {
                    // Update OTP
                }

                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);

                return $this->wapi->UpdateConference($accessId, $bridgeId, $data->conferenceTitle, $data->duration, $data->participants, $billingCode);
            }
        }

        if($order->billingSystem == "BlueSky")
        {
            $this->load->model("Bluesky_model","bluesky");

            // CHECK IF ACCEESS IS ON OTP
            if(isset($data->conferenceType) and $data->conferenceType == "scheduled")
            {

                //$API_USER, $API_PASSWORD, $accessId, $topic, $billingCode, $accessOptions, $resetModeratorPin, $WelcomePackSendMode

                if($bsOrder = $this->bluesky->updateAccess($data->API_USER, $data->API_PASSWORD, $accessId, $data->conferenceTitle, $data->billingCode,
                    $data->audioOptions, false, false, $data->startDate, $data->duration, "NoSending"))
                {
                    log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                    if($bsOrder['location'])
                    {
                        return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                    }
                    return false;
                }
                else
                {
                    return false;
                }
            }
            else{
                if(isset($data->conferenceType) and $data->conferenceType == "recurring")
                {
                    $isRecurring = true;
                }
                else{
                    $isRecurring = false;
                }

                if($bsOrder = $this->bluesky->CreateAccess($data->API_USER, $data->API_PASSWORD,$data->userId, $data->product, '', $data->billingCode, null, null,
                $isRecurring, $data->audioOptions, 'Instantly'))
                {
                    log_message("info","OO - ". json_encode(array("orderId" => $order->id, "subOrderId" => $subOrderId, 
                    "subOrderType"=> $subOrder->orderType , "header" => $bsOrder['header'], "body" => $bsOrder['body'])));

                    if($bsOrder['location'])
                    {
                        return str_replace("/provisioning/v1/queued_operation/", "", $bsOrder['location']);
                    }
                    return false;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    function _getOrderUserId($header)
    {
        var_dump($header);
        $start = strpos($header, "Location: /provisioning/v1/user/");
        $header = substr($header,$start+32);
        $header = substr($header,0,12);
        return trim($header);
    }

    function _getOrderAccessId($header)
    {
        $start = strpos($header, "Location: /provisioning/v1/access/");
        $header = substr($header,$start+34);
        $header = substr($header,0,12);
        return trim($header);
    }

    function getOrderStatus($orderId)
    {
        $order = $this->order->get($orderId);
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(array("orderId" => $order->id, "status" => $order->status)));
    }

}