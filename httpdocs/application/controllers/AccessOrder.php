
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessOrder extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
    }

    function new()
    {
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
            "text" => "Accesses",
            "link" => $this->session->userdata("AccessesUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => "New access",
            "link" => null
        );

        $data['userId'] = $this->input->get('Reference');
        $data['primaryGroup'] = $this->input->get('pg');
        $data['billingSystem'] = $this->input->get('BillingSystem');

        $userId = $this->encrypt->decode($this->input->get('userId'));
        $primaryGroup = $this->encrypt->decode($this->input->get('pg'));
        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));

        if($billingSystem == "Wise2")
        {
            // Get Spirit Profile Id
            $this->load->model("Anywhere_model","anywhere");
            $this->load->model("Spirit_model","spirit");
            $this->load->model("W2hierarchy_model","w2hierarchy");

            $spirit = $this->w2hierarchy->getSpiritProfileId($primaryGroup);
            $data['spiritId'] = (isset($spirit->Spirit_Customer_Profile_Id__c) ? $spirit->Spirit_Customer_Profile_Id__c : 0);
            
            $site = $this->w2hierarchy->getSite($primaryGroup);

            if($site->Product == "Anytime")
            {
                $anywhere = $this->anywhere->anywhereEnabled($site->SiteId, $site->Extension);
            }
            else{
                $anywhere = $this->anywhere->anywhereEnabled($site->SiteId);
            }

            $data['webex'] = $this->w2hierarchy->getWebexMicrosites($primaryGroup);                   
            
            if(isset($data['webex']) and $data['webex'])
            {
                $data['products'][] = array('value' => "Audio+Webex", "name" => "Arkadin Anytime + WebEx");
            }
            if(isset($anywhere) and $anywhere)
            {
                $data['products'][] = array('value' => "Audio+Anywhere", "name" => "Arkadin Anytime + Anywhere");
            }
            else{
                $data['products'][] = array('value' => "Audio", "name" => "Arkadin Anytime");
            }
            
            // Get Audio Options from Spirit
            $this->load->model('Audiooptionsmapping_model','optionSet');
            $customOptions = (isset($data['spiritId']) ? $this->spirit->getCustomAudioOption($data['spiritId']):array());
            $options = $this->optionSet->all();

            foreach($options as &$option)
            {
                foreach($customOptions as $custom)
                {
                    if($custom->OptionCode == $option->spirit)
                    {
                        $option->DefaultValue = $custom->Value;
                    }
                }
                $option->key = $option->wapit;
                $option->OptionCode = $option->wapit;
            }
            $data['audioOptions'] = $options;    
        }

        if($billingSystem == "BlueSky")
        {
            $this->load->model("Bluesky_model","bluesky");
            $subscriptions = json_decode($this->bluesky->getSubscriptions($primaryGroup)['body']);
            foreach($subscriptions as $subscription)
            {   
                // Get Subscription Audio Option
                $options = json_decode($this->bluesky->getSubscription($subscription->subscriptionId)['body'])->accessOptions;
                $optionSets = array();
                foreach($options as $key => $option)
                {
                    $optionSetItem = new \stdClass();
                    $optionSetItem->key = $key;
                    $optionSetItem->DefaultValue = $option;
                    $optionSetItem->OptionType = (gettype($option) == "boolean"? "CHECKBOX":"TEXTBOX");
                    $optionSets[$subscription->subscriptionId][] = $optionSetItem;
                }
                
                $subName = str_replace("ArkadinAnywhere","Anywhere", $subscription->subscriptionName);
                $data['products'][] = array("value" => $subscription->subscriptionId, "name" => $subName, "options" => $optionSets);
            }
        }

        $this->load->model('Countrymaster_model');
        $data['countries'] = $this->Countrymaster_model->all();

        $this->load->model('Timezone_model');
        $data['tmzs'] = $this->Timezone_model->get_all();

        $this->load->model('Language_model');
        $data['languages'] = $this->Language_model->all();

        $data['_view'] =  'access/new';
        $data['_title'] = $this->lang->line('add_new_access');
        $this->load->view('layout/layout',$data); 
    }

    function update()
    {
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
            "text" => "Accesses",
            "link" => $this->session->userdata("AccessesUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('update_access'),
            "link" => null
        );

        $data['accessId'] = $this->input->get('Reference');
        $data['primaryGroup'] = $this->input->get('pg');
        $data['billingSystem'] = $this->input->get('BillingSystem');

        $accessId = $this->encrypt->decode($this->input->get('Reference'));
        $primaryGroup = $this->encrypt->decode($this->input->get('pg'));
        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));

        if($billingSystem == "Wise2")
        {
            // Get Access
            $this->load->model("W2hierarchy_model","w2hierarchy");
            $this->load->model('Language_model','language');
            $primaryGroup = $this->w2hierarchy->getSite($primaryGroup);

            if($primaryGroup->Product == "Anytime")
            {
                $this->load->model("Avayamapping_model");
                $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);

                $this->load->model("Wapi_model","wapi");
                $access = $this->wapi->getConference($accessId, $bridgeId);
                $optionSet = $this->wapi->GetConferenceConfiguration($accessId, $bridgeId);
                $language = $this->language->find(array('avaya' => $access->DDILanguage))->id;
            }
            else{
                $this->load->model("Wapit_model","wapit");
                $access = $this->wapit->getConference($accessId, $primaryGroup->Bridge);
                $optionSet = $this->wapit->GetConferenceConfiguration($accessId, $primaryGroup->Bridge);
                $language = $this->language->find(array('wise2' => strtolower($access->DDILanguage)))->id;
            }

            $data['access'] = array(
                "conferenceTitle" => $access->Name,
                "startDate" => (isset($access->startDateTimeUTC) ? $access->startDateTimeUTC:''),
                "duration" => (isset($access->Duration) ? $access->Duration:''),
                "billingCode" => (isset($access->BillingCode) ? $access->BillingCode:''),
                "isScheduled" => !$access->demand,
                "participants" => $access->Participants,
                "language" => (isset($language) ? $language:null),
            );

            $options = array();
            foreach($optionSet as $key => $option)
            {
                $optionSetItem = new \stdClass();
                $optionSetItem->key = $key;
                $optionSetItem->DefaultValue = ($option ? 'true':'false');
                $optionSetItem->OptionType = (gettype($option) == "boolean"? "CHECKBOX":"TEXTBOX");
                $data['audioOptions'][] = $optionSetItem;
            }
        }

        if($billingSystem == "BlueSky")
        {
            // Get Access
            $this->load->model("Bluesky_model","bluesky");
            $access = json_decode($this->bluesky->getAccess($accessId)['body']);
            $data['access'] = array(
                "conferenceTitle" => $access->audioDetails->topic,
                "startDate" => (isset($access->audioDetails->startDate) ? $access->audioDetails->startDate:''),
                "duration" => (isset($access->audioDetails->duration) ? $access->audioDetails->duration:''),
                "billingCode" => (isset($access->audioDetails->billingCode) ? $access->audioDetails->billingCode:''),
                "isScheduled" => $access->audioDetails->isScheduled
            );


            // Get Conference Settings
            $options = array();
            foreach($access->accessOptions as $key => $option)
            {
                $optionSetItem = new \stdClass();
                $optionSetItem->key = $key;
                $optionSetItem->DefaultValue = ($option ? 'true':'false');
                $optionSetItem->OptionType = (gettype($option) == "boolean"? "CHECKBOX":"TEXTBOX");
                $data['audioOptions'][] = $optionSetItem;
            }
        }

        $this->load->model('Language_model');
        $data['languages'] = $this->Language_model->all();

        $data['_view'] =  'access/update';
        $data['_title'] = $this->lang->line('update_access');
        $this->load->view('layout/layout',$data); 
    }

    function newOccurences()
    {
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
            "link" => $this->session->userdata("occurrencesUrl")
        );

        $data['breadcrumbs'][] = array(
            "text" => $this->lang->line('new_occurrence'),
            "link" => null
        );

        $data['userId'] = $this->input->get('Reference');
        $data['primaryGroup'] = $this->input->get('pg');
        $data['billingSystem'] = $this->input->get('BillingSystem');

        $userId = $this->encrypt->decode($this->input->get('userId'));
        $primaryGroup = $this->encrypt->decode($this->input->get('pg'));
        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));

        $data['_view'] =  'access/new_occurrences';
        $data['_title'] = $this->lang->line('new_occurrences');
        $this->load->view('layout/layout',$data); 
    }
}

    