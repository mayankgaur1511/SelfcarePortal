<?php
 
class Spirit_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }
    
    /*
    * Get default Audio Options (Global)
    */

    function getDefaultAudioOptions()
    {
        $rainbow = $this->load->database('spirit', TRUE);
        $rainbow->select("Name, OptionCode, DefaultValue, OptionType");
        $rainbow->where("ProductId",1);
        return $rainbow->get("ProductOptions")->result();
    }

    /*
    * Get custom Audio Options (Customer)
    */

    function getCustomAudioOption($customerId)
    {
        $rainbow = $this->load->database('spirit', TRUE);
        $rainbow->select("po.Name, po.OptionCode, cpo.Value");
        $rainbow->from("CustomerProductOptions as cpo");
        $rainbow->join("ProductOptions as po","po.OptionId = cpo.OptionID");
        $rainbow->where("cpo.CustomerID",$customerId);
        $rainbow->where("po.ProductId",1);
        return $rainbow->get()->result();
    }

    /*
    * Get customer WebEx Microsite
    */

    function getCustomerMicrosite($customerId)
    {
        $rainbow = $this->load->database('spirit', TRUE);
        $rainbow->select("po.Name, po.OptionCode, cpo.Value");
        $rainbow->from("CustomerProductOptions as cpo");
        $rainbow->join("ProductOptions as po","po.OptionId = cpo.OptionID");
        $rainbow->where("cpo.CustomerID",$customerId);
        $rainbow->where("po.ProductId",2);
        return $rainbow->get()->row_object();

    }

    /*
    * Get Customer Products (Audio and/or WebEx)
    */

    function getCustomerProducts($customerId)
    {
        $rainbow = $this->load->database('spirit', TRUE);
        $rainbow->select("p.Name, p.ProductCode");
        $rainbow->from("CustomerProducts as cp");
        $rainbow->join("Products as p","p.ProductID = cp.ProductID");
        $rainbow->where("cp.CustomerID",$customerId);
        $rainbow->where_in("p.ProductType",array(1,2));
        return $rainbow->get()->result();
    }

    /*
    * Get Welcome E-mail Template Information
    */

    function getCustomerWelcomeEmailTemplates($customerId, $type = null, $country = null)
    {
        $rainbow = $this->load->database('spirit', TRUE);
        $rainbow->select("e.Id, e.Name, e.Type, e.SenderEmail, e.SenderLabel, e.Subject, e.Toll, e.TollFree, e.Country, e.Language");
        $rainbow->from("CustomerEmailTemplates as c");
        $rainbow->join("EmailTemplates as e","e.Id = c.EmailTemplateID");
        if($type)
        {
            switch($type){
                case "Audio":
                    $rainbow->where("(e.Type = 'Audio-Classic' or e.Type = 'Audio-Cloud')");
                    break;
                case "Audio+Anywhere":
                    $rainbow->where("(e.Type = 'Audio-Classic+Anywhere' or e.Type = 'Audio-Cloud+Anywhere')");
                break;
                case "Audio+Webex":
                    $rainbow->where("(e.Type = 'Audio-Classic+WebEx' or e.Type = 'Audio-Cloud+WebEx')");
                break;
            }
        }
        if($country)
        {
            $rainbow->where("e.Country",$country);
        }

        $rainbow->where("c.CustomerID",$customerId);
        $rainbow->where("e.Enabled",1);
        if($type and $country)
        {
            return $rainbow->get()->row_object();
        }
        return $rainbow->get()->result();
    }

    /*
    * Get Welcome E-mail HTML content
    */
    
    function getWelcomeEmailBody($templateId)
    {
        $folder = "\\\\10.115.129.16\\spirit\\MsgBody{$templateId}\\";

        foreach(scandir($folder) as $file) {
            if(strpos($file,"htm"))
            {
                return file_get_contents($folder . $file);
            }
        }
    }

}
