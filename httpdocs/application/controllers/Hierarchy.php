
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hierarchy extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        
        $this->load->model('W2hierarchy_model','w2hierarchy');
        $this->load->model('Bluesky_model','bluesky');
    }

    function index()
    {
        // GET BLUESKY LOGOS
        if($this->session->userdata('blueskyUserId'))
        {
            /*
            *   Bluesky Public API does not retreive the list of Local Logos.
            *   In this case, let's display the Bluesky Logo as a Local Logo.
            */
            $bluesky_logo = json_decode($this->bluesky->getLogo()['body']);
            
            $data['localLogos'][] = array(
                'Name' => $bluesky_logo->logoName,
                'Id' => $bluesky_logo->logoId,
                'Reference' => $bluesky_logo->logoId,
                'BillingSystem' => 'BlueSky',
            );

        }

        // GET WISE 2 INFORMATION
        if($this->session->userdata['wise2UserId']){
            if($this->session->userdata['wise2Node']->Organizational_type__c=='Logo'){
                $wise2_LocalLogos = $this->w2hierarchy->getLocalLogos($this->session->userdata['wise2Node']->Id);
                foreach($wise2_LocalLogos as $localLogo)
                {
                    $data['localLogos'][] = array(
                        'Name' => $localLogo->Name,
                        'Id' => $localLogo->Id,
                        'Reference' => $localLogo->Id,
                        'BillingSystem' => 'Wise2',
                    );
                }
            }
            if($this->session->userdata['wise2Node']->Organizational_type__c=='Local logo'){
                $data['localLogos'][] = array(
                    'Name' => $this->session->userdata['wise2Node']->Name,
                    'Id' => $this->session->userdata['wise2Node']->Id,
                    'Reference' => $this->session->userdata['wise2Node']->Id,
                    'BillingSystem' => 'Wise2',
                );
            }
            if($this->session->userdata['wise2Node']->Organizational_type__c=='Department'){
                $node = $this->w2hierarchy->getLocalLogo($this->session->userdata['wise2Node']->Id);
                if($node)
                {
                    $data['localLogos'][] = array(
                        'Name' => $node->Name,
                        'Id' => $node->Id,
                        'Reference' => $node->Id,
                        'BillingSystem' => 'Wise2',
                    );
                }
            }
        }

        if($this->input->get('search'))
        {
            foreach($data['localLogos'] as $key => $logo)
            {
                
                if(!strpos(strtoupper($logo['Name']), strtoupper($this->input->get('search')))){
                    unset($data['localLogos'][$key]);
                }
            }
        }

        $data['_view'] =  'hierarchy/localLogos';
        $data['_title'] = $this->lang->line('regional_organization');  
        $this->load->view('layout/layout',$data);  
    }

    function billingAccounts()
    {
        // Navigation
        $this->session->set_userdata("billingAccountsUrl", current_url() . "?" . $_SERVER['QUERY_STRING']);
        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('regional_organizations'),
            "link" => "/hierarchy",
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('billing_accounts'),
            "link" => null,
        );

        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));
        $Reference = $this->encrypt->decode($this->input->get('Reference'));
        $Id = $this->encrypt->decode($this->input->get('Id'));        

        // GET WISE2 BILLING ACCOUNTS
        if($billingSystem == "Wise2")
        {
            if($this->session->userdata['wise2Node']->Organizational_type__c=='Department'){
                $ba = $this->w2hierarchy->getBillingAccountByRef($this->session->userdata['wise2Node']->Account_Reference__c);
                $data['billingAccounts'][] = array(
                    'Name' => $ba->Name,
                    'Id' => $ba->Id,
                    'Reference' => $ba->AccountReference,
                    'NodeId' => $this->session->userdata['wise2Node']->Id,
                    'IsInactive' => $ba->IsInactive,
                    'BillingSystem' => 'Wise2',
                );
            }
            else
            {
                $billingAccounts = $this->w2hierarchy->getBillingAccounts($Id);
                foreach($billingAccounts as $ba)
                {
                    $data['billingAccounts'][] = array(
                        'Name' => $ba->Name,
                        'Id' => $ba->Id,
                        'Reference' => $ba->AccountReference,
                        'NodeId' => $ba->nodeId,
                        'IsInactive' => $ba->IsInactive,
                        'BillingSystem' => 'Wise2',
                    );
                }
            }
        }
        //exit();
        $data['_view'] =  'hierarchy/billingAccounts';
        $data['_title'] = $this->lang->line('billing_accounts');
        $this->load->view('layout/layout',$data);  
    }

    function primaryGroups()
    {
        $this->session->set_userdata("primaryGroupsUrl", current_url() . "?" . $_SERVER['QUERY_STRING']);
        // Navigation
        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('regional_organizations'),
            "link" => "/hierarchy",
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('billing_accounts'),
            "link" => $this->session->userdata("billingAccountsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('departments'),
            "link" => null,
        );

        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));
        $Reference = $this->encrypt->decode($this->input->get('Reference'));
        $NodeId = $this->encrypt->decode($this->input->get('NodeId'));

        // GET BLUESKY LOGOS
        if($billingSystem == "BlueSky")
        {
            $start = 0;
            $query = json_decode($this->bluesky->getPrimaryGroups($start,500)['body']);
            
            foreach($query->items as $pg)
            {
                $record = array(
                    'Id' => $pg->primaryGroupId,
                    'Reference' => $pg->primaryGroupId,
                    'Name' => $pg->primaryGroupName,
                    'BillingSystem' => 'BlueSky',
                );
                $data['primaryGroups'][] = $record;
            }
        }

        if($billingSystem == "Wise2")
        {
            $primary_groups = $this->w2hierarchy->getDepartments($NodeId);
            foreach($primary_groups as $pg)
            {
                $record = array(
                    'Id' => $pg->Id,
                    'Reference' => $pg->Id,
                    'Name' => $pg->Name,
                    'BillingSystem' => 'Wise2',
                    'SiteId' => $pg->site->SiteId,
                    'Bridge' => $pg->site->Bridge,
                    'Product' => $pg->site->Product,
                );
                $data['primaryGroups'][] = $record;
            }
        }

        $data['_view'] =  'hierarchy/primaryGroups';
        $data['_title'] = $this->lang->line('departments_and_ccs'); 
        $this->load->view('layout/layout',$data);  
    }

    function users()
    {
        // Navigation
        $this->session->set_userdata("usersUrl", current_url() . "?" . $_SERVER['QUERY_STRING']);
        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('regional_organizations'),
            "link" => "/hierarchy",
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('billing_accounts'),
            "link" => $this->session->userdata("billingAccountsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('departments'),
            "link" => $this->session->userdata("primaryGroupsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('moderators'),
            "link" => null,
        );

        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));
        $Reference = $this->encrypt->decode($this->input->get('Reference'));
        $Bridge = $this->encrypt->decode($this->input->get('Bridge'));
        $SiteId = $this->encrypt->decode($this->input->get('SiteId'));
        $Product = $this->encrypt->decode($this->input->get('Product'));

        // GET BLUESKY USERS
        if($billingSystem == "BlueSky")
        {
                $query = json_decode($this->bluesky->getUsers($Reference, 0, 500)['body']);
                $start = 0;
                while(sizeof($query->items) > 0)
                {
                    $start = $start + 500;
                    foreach($query->items as $user)
                    {
                        switch($user->userStatus){
                            case "Active":
                                $status = "ACTIVE";
                                break;
                            case "Suspended":
                                $status = "SUSPENDED";
                                break;
                            default:
                                $status = "CLOSED";
                                break;
                        }

                        $record = array(
                            'primaryGroup' => $Reference,
                            'Id' => $user->userId,
                            'Reference' => $user->userId,
                            'Name' => null,
                            'Email' => $user->email,
                            'BillingSystem' => 'BlueSky',
                            'Status' => $status,
                            'Bridge' => null,
                            'SiteId' => null
                        );
                        $data['users'][] = $record;
                    }

                    $query = json_decode($this->bluesky->getUsers($Reference, $start, 500)['body']);
                }
        }

        // GET WISE2 USERS
        if($billingSystem == "Wise2")
        {
           
            // VIPER PRIMARY GROUP
            if($Product == "SEP/VIPER" )
            {
                $this->load->model("Wapit_model");
                $users = $this->Wapit_model->GetClientList($SiteId,$Bridge);
            }              

            // AVAYA PRIMARY GROUP
            if($Product == "Anytime" )
            {
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$Bridge);

                $this->load->model("Wapi_model");
                $users = $this->Wapi_model->GetClientList($SiteId,$bridgeId);
            }

            if(isset($users->ClientListResult))
            {
                foreach($users->ClientListResult as $user)
                {
                    switch($user->DisabledInd){
                        case false:
                            $status = "ACTIVE";
                            break;
                        case true:
                            $status = "SUSPENDED";
                            break;
                        default:
                            $status = "CLOSED";
                            break;
                    }

                    $record = array(
                        'Id' => $user->ClientRef,
                        'primaryGroup' => $Reference,
                        'Reference' => $user->ClientRef,
                        'Name' => $user->ClientName,
                        'Email' => $user->ClientEMail,
                        'BillingSystem' => 'Wise2',
                        'Status' => $status,
                        'Bridge' => $Bridge,
                        'SiteId' => $SiteId
                    );
                    $data['users'][] = $record;
                }
            }
        }

        $data['pgId'] = $Reference;
        $data['_view'] =  'hierarchy/users';
        $data['_title'] = $this->lang->line('moderators');
        $this->load->view('layout/layout',$data);  
    }

    function accesses()
    {
        // Navigation
        $this->session->set_userdata("AccessesUrl", current_url() . "?" . $_SERVER['QUERY_STRING']);
        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('regional_organizations'),
            "link" => "/hierarchy",
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('billing_accounts'),
            "link" => $this->session->userdata("billingAccountsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('departments'),
            "link" => $this->session->userdata("primaryGroupsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('moderators'),
            "link" => $this->session->userdata("usersUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('accesses'),
            "link" => null
        );
        
        $BillingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));
        $Reference = $this->encrypt->decode($this->input->get('Reference'));
        $data['userId'] = $Reference;
        $data['pg'] = $this->input->get('primaryGroup');
        $Id = $this->encrypt->decode($this->input->get('Id'));
        $Bridge = $this->encrypt->decode($this->input->get('Bridge'));
        $site = $this->w2hierarchy->getSite($this->encrypt->decode($this->input->get('primaryGroup')));

        if($BillingSystem == "BlueSky")
        {
            $accesses = json_decode($this->bluesky->getAccesses($Reference)['body']);
            foreach($accesses as $access)
            {
                if($access->accessType == "Audio"){
                    // Get Access details
                    
                    /*  THIS IS TAKING A VERY LONG TIME  */
                    
                    $details = json_decode($this->bluesky->getAccess($access->accessId)['body']);
                    $startDate = (isset($details->audioDetails->startDate)? $details->audioDetails->startDate:'-');
                    $duration = (isset($details->audioDetails->duration)? $details->audioDetails->duration:'-');
                   
                    $data['accesses'][] = array(
                        'Id' => $access->accessId,
                        'Reference' => $access->accessId,
                        'Weblogin' => $details->audioDetails->conferenceRef,
                        'ModeratorPin' => $details->audioDetails->moderatorPIN,
                        'ParticipantPin' => $details->audioDetails->participantPIN,
                        'IsScheduled' => $details->audioDetails->isScheduled,
                        'StartDate' => $startDate,
                        'Duration' => $duration,
                        'IsRecurring' => $details->audioDetails->isRecurring,
                        'BillingSystem' => 'BlueSky',
                        'Status' => 'ACTIVE',
                    );

                   
                    
                    // Save demand accesses in the session in case of "New Access request"
                    if(!$details->audioDetails->isScheduled)
                    {
                        $demandAccesses[] = array('conferenceRef' => $details->audioDetails->conferenceRef, 'Id' => $access->accessId);
                    }
                }
            }
            if(isset($demandAccesses))
            {
                $this->session->set_userdata('demandAccesses',$demandAccesses);
            }
        }

        if($BillingSystem == "Wise2")
        {
            if($site->Product == "SEP/VIPER" )
            {
                $this->load->model("Wapit_model");
                $conferences = $this->Wapit_model->GetConferencesByClientRef($Reference, $Bridge, false);

                if(isset($conferences->ConferencesListResult)){
                    $accesses_all[] = $conferences->ConferencesListResult;
                }

                $conferences = $this->Wapit_model->GetConferencesByClientRef($Reference,
                $Bridge, true);

                if(isset($conferences->ConferencesListResult)){
                    $accesses_all[] = $conferences->ConferencesListResult;
                }

            } 
        
            if($site->Product == "Anytime" )
            {
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$bridge);

                $this->load->model("Wapi_model");
                $conferences = $this->Wapi_model->GetConferencesByClientRef($Reference, $bridgeId, false, '1999-01-01 00:00:00', '2999-01-01:00:00');

                if(isset($conferences->ConferencesListResult)){
                    $accesses_all[] = $conferences->ConferencesListResult;
                }

                $conferences = $this->Wapi_model->GetConferencesByClientRef($Reference, $bridgeId, true, '1999-01-01 00:00:00', '2999-01-01:00:00');

                if(isset($conferences->ConferencesListResult)){
                    $accesses_all[] = $conferences->ConferencesListResult;
                }

                $data['extension'] = $site->Extension;
                
            }

            foreach($accesses_all as $accesses)
            {
                if(sizeof($accesses) == 1)
                {
                    $accesses = array($accesses);
                }

                foreach($accesses as $access)
                {
                    
                    $data['accesses'][] = array(
                        'Id' => $access->ConferenceRef,
                        'Reference' => $access->ConferenceRef,
                        'Weblogin' => $access->ConferenceRef,
                        'ModeratorPin' => $access->moderatorPin,
                        'ParticipantPin' => $access->participantPin,
                        'IsScheduled' => !$access->demand,
                        'StartDate' => ($access->startDateTimeUTC ? date("d/m/y H:i", strtotime($access->startDateTimeUTC)) . ' GMT':'-'),
                        'Duration' => ($access->Duration ? $access->Duration:'-'),
                        'IsRecurring' => false,
                        'BillingSystem' => 'Wise2',
                        'Status' => 'ACTIVE',
                    );
                }
            }
        }
        $data['_view'] =  'hierarchy/accesses';
        $data['_title'] = $this->lang->line('accesses'); 
        $this->load->view('layout/layout',$data);
    }

    function occurrences()
    {
        $this->session->set_userdata("occurrencesUrl", current_url() . "?" . $_SERVER['QUERY_STRING']);
        // Navigation
        $data['breadcrumbs'][] = array(
            "text" =>  $this->lang->line('regional_organizations'),
            "link" => "/hierarchy",
        );

        $data['breadcrumbs'][] = array(
            "text" =>  $this->lang->line('billing_accounts'),
            "link" => $this->session->userdata("billingAccountsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" =>  $this->lang->line('departments'),
            "link" => $this->session->userdata("primaryGroupsUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" =>  $this->lang->line('moderators'),
            "link" => $this->session->userdata("usersUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('accesses'),
            "link" => $this->session->userdata("AccessesUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('occurrences'),
            "link" => null
        );

        $data['accessId'] = $this->input->get('Reference');
        $accessId = $this->encrypt->decode($this->input->get('Reference'));

        // Get Access
        $this->load->model("Bluesky_model","bluesky");
        $access = json_decode($this->bluesky->getRecurringOccurrences($accessId)['body']);

        if(isset($access->items) and $access->items)
        {
            foreach($access->items as $occurence)
            {
                $data['occurences'][] = array(
                    "recurringId" => $occurence->recurringId,
                    "startDate" => $occurence->startDate,
                    "duration" => $occurence->duration
                );
            }
        }

        $data['_view'] =  'access/occurences';
        $data['_title'] = $this->lang->line('conference_occurences');
        $this->load->view('layout/layout',$data); 
    }

}