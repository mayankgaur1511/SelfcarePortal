
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeratorOrder extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect("/signin");
        }
        $this->load->model("W2hierarchy_model","w2hierarchy");
    }

    function new()
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
            "text" => $this->lang->line('add_new_moderator'),
            "link" => null
        );

        $data['primaryGroup'] = $this->input->get('pg');
        $data['billingSystem'] = $this->input->get('BillingSystem');

        $primaryGroup = $this->encrypt->decode($this->input->get('pg'));
        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));

        if($billingSystem == "Wise2")
        {
            // Get Spirit Profile Id
            $this->load->model("Anywhere_model","anywhere");
            $this->load->model("Spirit_model","spirit");

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
                    $optionSetItem->DefaultValue = ($option ? 'true':'false');
                    $optionSetItem->OptionType = (gettype($option) == "boolean"? "CHECKBOX":"TEXTBOX");
                    $optionSets[$subscription->subscriptionId][] = $optionSetItem;
                }
                $data['products'][] = array("value" => $subscription->subscriptionId, "name" => $subscription->subscriptionName, "options" => $optionSets);
            }
        }

        $this->load->model('Countrymaster_model');
        $data['countries'] = $this->Countrymaster_model->all();

        $this->load->model('Timezone_model');
        $data['tmzs'] = $this->Timezone_model->get_all();

        $this->load->model('Language_model');
        $data['languages'] = $this->Language_model->all();

        $data['_view'] =  'moderator/new';
        $data['_title'] = 'Add new moderator';
        $this->load->view('layout/layout',$data); 
    }

    function update()
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
            "text" => "Update User",
            "link" => null
        );

        $data['primaryGroup'] = $this->input->get('primaryGroup');
        $data['billingSystem'] = $this->input->get('BillingSystem');
        $data['userId'] = $this->input->get('Reference');

        $primaryGroup = $this->encrypt->decode($this->input->get('primaryGroup'));
        $billingSystem = $this->encrypt->decode($this->input->get('BillingSystem'));
        $primaryGroup = $this->w2hierarchy->getSite($primaryGroup);

        if($billingSystem == "Wise2")
        {

            if($primaryGroup->Product == "Anytime")
            {
                $this->load->model("Avayamapping_model");
                    $wapiBridge = $this->Avayamapping_model->get($primaryGroup->Bridge);
                    $bridgeId = (isset($wapiBridge->wapi) ? $wapiBridge->wapi:$primaryGroup->Bridge);
                    $this->load->model("Wapi_model","wapit");
                    $client = $this->wapit->GetClient($this->encrypt->decode($this->input->get('Reference')),$bridgeId);
            }
            else{
                $this->load->model('Wapit_model','wapit');
                $client = $this->wapit->GetClient($this->encrypt->decode($this->input->get('Reference')),$this->input->get('Bridge'));
            }

            $firstName = substr($client->ClientName, 0, strpos($client->ClientName, " "));
            $lastName = substr($client->ClientName,strpos($client->ClientName, " ")+1);

            $num_space = (strpos($client->ClientName, " ") ? strpos($client->ClientName, " ")+1:0);

            $data['user'] = array(
                'firstName' => $firstName,
                'lastName' => $lastName,
                'phone' => $client->ClientMainPhone,
                'email' => $client->ClientEMail
            );
        }
           
        if($billingSystem == "BlueSky")
        {
            $this->load->model('Bluesky_model','bluesky');
            $client = json_decode($this->bluesky->getUser($this->encrypt->decode($this->input->get('Reference')))['body']);

            $this->load->model('Timezone_model','timezone');
            $this->load->model('Language_model','language');
            $this->load->model('Countrymaster_model','country');

            $data['user'] = array(
                'userId'    => $client->userId,
                'firstName' => $client->firstName,
                'lastName'  => $client->lastName,
                'email'     => $client->email,
                'timezone'  => $this->timezone->find(array('timeZoneBS' => $client->timeZone))->id,
                'phone'     => $client->phoneNumber,
                'language'  => $this->language->find(array('bluesky' => $client->communicationLanguage))->id,
                'country'   => $this->country->find(array('countryCodeBS' => $client->country))->id,
            );
        }

        $this->load->model('Countrymaster_model');
        $data['countries'] = $this->Countrymaster_model->all();

        $this->load->model('Timezone_model');
        $data['tmzs'] = $this->Timezone_model->get_all();

        $this->load->model('Language_model');
        $data['languages'] = $this->Language_model->all();

        $data['_view'] =  'moderator/update';
        $data['_title'] = 'Update user';
        $this->load->view('layout/layout',$data); 
    }
}