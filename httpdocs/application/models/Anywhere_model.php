<?php
 
class Anywhere_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function anywhereEnabled($company, $extension = false)
    {
        $anywhere = $this->load->database('anywhere', TRUE);
        $anywhere->select("(r.ConferenceTypeRights & 2) as AnywhereEnabled");
        $anywhere->join("Rights r WITH(NOLOCK)","r.ID = Company.Rights_ID");
        $anywhere->from("Company WITH(NOLOCK)");

        if(!$extension){
            $anywhere->where("Company.ID",$company);
        }
        else{
            $anywhere->where("Company.CompanyRef",$company);
            $anywhere->where("Company.Bridge_Id",$extension);
        }

        $result = $anywhere->get()->row_object();
        
        // System default
        if(!$result){
            return true;
        } 
        else{
            if($result->AnywhereEnabled){
                return true;
            }
        }
        return false;
    }

    function getAudioAccessNumbers($companyId)
    {
        $anywhere = $this->load->database('anywhere', TRUE);
        $anywhere->select("DDIList.PhoneNumber, DDIList.TypeOfNumber, DDIList.OriginCountryCode, LINKTBL.IsDisplayedAsDefault");
        $anywhere->join("AudioAccessNumberSet AS DDISet WITH(NOLOCK)","DDISet.ID = Company.AudioAccessNumberSet_ID");
        $anywhere->join("AudioAccessNumberHierarchy AS LINKTBL WITH(NOLOCK)","DDISet.ID = LINKTBL.AudioAccessNumberSetID");
        $anywhere->join("AudioAccessNumber AS DDIList WITH(NOLOCK)","LINKTBL.AudioAccessNumberID = DDIList.AudioAccessNumberID");
        $anywhere->from("Company WITH(NOLOCK)");
        $anywhere->where("Company.ID", $companyId);
        $anywhere->like("LINKTBL.AudioAccessNumberID","");
        $anywhere->order_by("DDIList.OriginCountryCode");
        
        return $anywhere->get()->result();   
    }

    function getAudioAccessNumbersAvaya($companyRef, $extension)
    {
        $anywhere = $this->load->database('anywhere', TRUE);
        $anywhere->select("DDIList.PhoneNumber, DDIList.TypeOfNumber, DDIList.OriginCountryCode, LINKTBL.IsDisplayedAsDefault");
        $anywhere->join("AudioAccessNumberSet AS DDISet WITH(NOLOCK)","DDISet.ID = Company.AudioAccessNumberSet_ID");
        $anywhere->join("AudioAccessNumberHierarchy AS LINKTBL WITH(NOLOCK)","DDISet.ID = LINKTBL.AudioAccessNumberSetID");
        $anywhere->join("AudioAccessNumber AS DDIList WITH(NOLOCK)","LINKTBL.AudioAccessNumberID = DDIList.AudioAccessNumberID");
        $anywhere->from("Company WITH(NOLOCK)");
        $anywhere->where("Company.CompanyRef",$companyRef);
        $anywhere->where("Company.Bridge_Id",$extension);
        $anywhere->like("LINKTBL.AudioAccessNumberID","");
        $anywhere->order_by("DDIList.OriginCountryCode");
        
        return $anywhere->get()->result();   
    }

    function selectDDI($company, $country, $extension = false)
    {
        if(!$extension)
        {
            $numbers = $this->getAudioAccessNumbers($company);
        }
        else{
            $numbers = $this->getAudioAccessNumbersAvaya($company,$extension);
        }

        foreach($numbers as $number)
        {
            if($number->OriginCountryCode == $country)
            {
                if($number->TypeOfNumber == 1)
                {
                    if($number->IsDisplayedAsDefault == 1)
                    {
                        $toll_default = $number->PhoneNumber;
                    }
                    else{
                        if(!isset($toll_secondary))
                        {
                            $toll_secondary = $number->PhoneNumber;
                        }
                    }
                }

                if($number->TypeOfNumber == 2)
                {
                    if($number->IsDisplayedAsDefault == 1)
                    {
                        $tollFree_default = $number->PhoneNumber;
                    }
                    else{
                        if(!isset($toll_secondary))
                        {
                            $tollFree_secondary = $number->PhoneNumber;
                        }
                    }
                }
            }
        }

        $toll = (isset($toll_default) ? $toll_default:false);
        $tollFree = (isset($tollFree_default) ? $tollFree_default:false);

        if(!isset($toll_default) and isset($toll_secondary)){
            $toll = $toll_secondary;
        }

        if(!isset($tollFree_default) and isset($tollFree_secondary)){
            $tollFree = $tollFree_secondary;
        }

        return array(
            "toll" => $toll,
            "tollFree" => $tollFree
        );
    }
}
