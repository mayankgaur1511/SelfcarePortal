<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Casefields_model extends CI_Model {

    function ProductType__c()
    {
        return $this->db->get('SFDC_ProductType__c')->result_array();
    }

    function Status()
    {
        return $this->db->get('SFDC_CaseStatus')->result_array();
    }

    function Resolution_details__c($Resolutioncategory__c)
    {
        $this->db->where('Resolutioncategory__c',$Resolutioncategory__c);
        return $this->db->get('SFDC_Resolution_details__c')->result_array();
    }

    function Product__c($ProductType__c)
    {
        $this->db->where('ProductType__c',$ProductType__c);
        $this->db->where('display is not null');
        return $this->db->get('SFDC_Product__c')->result_array();
    }

    function Category_NEW__c($ProductType__c)
    {
        $this->db->where('ProductType__c',$ProductType__c);
        return $this->db->get('SFDC_Category_NEW__c')->result_array();
    }

    function BusinessImpact__c($Category_NEW__c)
    {
        $this->db->where('Category_NEW__c',$Category_NEW__c);
        return $this->db->get('SFDC_BusinessImpact__c')->result_array();
    }

    function Severity__c($Category_NEW__c)
    {
        $this->db->where('Category_NEW__c',$Category_NEW__c);
        return $this->db->get('SFDC_Severity__c')->result_array();
    }

    function Product_L2__c($Product__c)
    {
        $this->db->where('Product__c',$Product__c);
        return $this->db->get('SFDC_Product_L2__c')->result_array();
    }

    function Symptom_L1__c($Product__c)
    {
        $this->db->where('Product__c',$Product__c);
        return $this->db->get('SFDC_Symptom_L1__c')->result_array();
    }

    function Symptom_L2__c($Symptom_L1__c)
    {
        $this->db->where('Symptom_L1__c',$Symptom_L1__c);
        return $this->db->get('SFDC_Symptom_L2__c')->result_array();
    }

    function Vendor__c()
    {
        return $this->db->get('SFDC_Vendor__c')->result_array();
    }

    function Country__c()
    {
        return $this->db->get('SFDC_Country__c')->result_array();
    }
}