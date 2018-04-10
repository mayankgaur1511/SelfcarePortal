<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class W2hierarchy_model extends CI_Model {

    function getLogo($nodeId)
    {
        $this->db->select("ParentNodeId");
        $this->db->where("ChildNodeId",$nodeId);
        $children = $this->db->get("W2_NodeLinks")->result();

        foreach($children as $record)
        {
            $node = $this->getNode($record->ParentNodeId);
            if($node->NodeType != "Logo")
            {
                return $this->getLogo($node->Id);
            }
            else{
                return $node;
            }
        }
    }

    function getLocalLogo($nodeId)
    {
        $this->db->select("ParentNodeId");
        $this->db->where("ChildNodeId",$nodeId);
        $children = $this->db->get("W2_NodeLinks")->result();

        foreach ($children as $child)
        {
            $node = $this->getNode($child->ParentNodeId);
            if($node->NodeType != "Local logo")
            {
                return $this->getLocalLogo($node->Id);
            }
            else{
                return $node;
            }
        }
    }

    function getSpiritProfileId($nodeId)
    {
        $this->load->model('Salesforce_model');
        $this->db->select("ParentNodeId");
        $this->db->where("ChildNodeId",$nodeId);
        $children = $this->db->get("W2_NodeLinks")->result();
        
        foreach($children as $me)
        {
            $node = $this->Salesforce_model->getNode($me->ParentNodeId);
            if(!isset($node->Spirit_Customer_Profile_Id__c) or !$node->Spirit_Customer_Profile_Id__c)
            {
                return $this->getSpiritProfileId($node->Id);
            }
            else{
                return $node;
            }
        }
    }

    function getBillingAccount($nodeId)
    {
        $query = "SELECT Parent_Node__c FROM Link_Node_Node__c where Child_Node__c = '{$nodeId}'";
        $response = $this->sfdc->query(($query));

        foreach ($response->records as $record)
        {
            $node = $this->getNode($record->Parent_Node__c);
            if(isset($node->Billing_Account__c) and $node->Billing_Account__c)
            {
                $billingAccount = $this->getBillingAccountByRef($node->Account_Reference__c);
                if(!isset($billingAccount->Master_Billing_Account__c) or !$billingAccount->Master_Billing_Account__c)
                {
                    return $node;
                }
            }

            return $this->getLocalLogo($node->Id);
        }
    }

    /*
    *  Return local logo for a given Logo Id
    */
    function getLocalLogos($nodeId)
    {
        $this->db->select("ChildNodeId");
        $this->db->where("ParentNodeId", $nodeId);
        $records = $this->db->get("W2_NodeLinks")->result();
        
        $nodes = array();
        foreach ($records as $record)
        {
            $nodes[] = $this->getNode($record->ChildNodeId);
        }
        return $nodes;
    }

    function getBillingAccountByRef($accountReference)
    {
        $this->db->select("Id, AccountReference, Name, IsMBA, IsInactive");
        $this->db->where("AccountReference",$accountReference);
        return $this->db->get("W2_BillingAccounts")->row_object();
    }

    function getBillingAccountById($Id)
    {
        $this->db->select("Id, AccountReference, Name, IsMBA, IsInactive");
        $this->db->where("Id",$Id);
        return $this->db->get("W2_BillingAccounts")->row_object();
    }

    var $billingAccounts = array();

    function getBillingAccounts($nodeId)
    {
        // Check current node
        $node = $this->getNode($nodeId);
        if(isset($node->BillingAccountId) and $node->BillingAccountId)
        {
            $billingAccount = $this->getBillingAccountById($node->BillingAccountId);
            if(!$billingAccount->IsMBA)
            {
                $billingAccount->nodeId = $node->Id;
                $this->billingAccounts[] = $billingAccount;
                return $this->billingAccounts;
            }
        }
        $this->getChildrenBA($nodeId);
        return $this->billingAccounts;

    }

    function getChildrenBA($nodeId)
    {
        $this->db->select("ChildNodeId");
        $this->db->where("ParentNodeId", $nodeId);
        $records = $this->db->get("W2_NodeLinks")->result();
       
        foreach ($records as $record)
        {
            $node = $this->getNode($record->ChildNodeId);
            if(isset($node->BillingAccountId) and $node->BillingAccountId)
            {
                $billingAccount = $this->getBillingAccountById($node->BillingAccountId);
                if(!$billingAccount->IsMBA)
                {
                    $billingAccount->nodeId = $node->Id;
                    $this->billingAccounts[] = $billingAccount;
                }
                else{
                    $this->getChildrenBA($node->Id);
                }
            }
            else{
                $this->getChildrenBA($node->Id);
            }
        }
    }

    var $microsites = array();

    function getWebexMicrosites($nodeId)
    {
        // Check current level
        $site = $this->getWebexSite($nodeId);
        if($site and $site->Product == 'Webex')
        {
            $this->microsites[] = $site;
        }

        $this->getParentWebexMicrosites($nodeId);
        return $this->microsites;
    }

    function getParentWebexMicrosites($nodeId)
    {
        $this->db->select("ParentNodeId");
        $this->db->where("ChildNodeId",$nodeId);
        $parents = $this->db->get("W2_NodeLinks")->result();

        foreach($parents as $record)
        {
            $site = $this->getWebexSite($nodeId);
            if($site and $site->Product == 'Webex')
            {
                $this->microsites[] = $site;
            }
            $this->getParentWebexMicrosites($record->ParentNodeId);
        }
    }
    

    var $departments = array();
    function getDepartments($nodeId)
    {
        $site = $this->getSite($nodeId);
        
        if($site)
        {
            $node = $this->getNode($nodeId);
            $node->site = $site;
            // Check inactive users
            if(!strpos($node->Name,"DO NOT USE") and !strpos($node->Name,"DIS_") and  !strpos($node->Name,"_DIS"))
            {
                $this->departments[] = $node;
            }
        }

        $this->getChildrenDepartment($nodeId);
        return $this->departments;
    }

    function getChildrenDepartment($nodeId)
    {
        $this->db->select("ChildNodeId");
        $this->db->where("ParentNodeId", $nodeId);
        $records = $this->db->get("W2_NodeLinks")->result();

        foreach ($records as $record)
        {
            $site = $this->getSite($record->ChildNodeId );
            if($site)
            {
                $node = $this->getNode($record->ChildNodeId);
                $node->site = $site;
                // Check inactive users
                if(!strpos($node->Name,"DO NOT USE") and !strpos($node->Name,"DIS_") and  !strpos($node->Name,"_DIS"))
                {
                    $this->departments[] = $node;
                }
            }
            else{
                $this->getChildrenDepartment($record->ChildNodeId);
            }
        }
    }
  
    function getNode($nodeId)
    {
        $this->db->select("Id, Name, BillingAccountId, NodeType");
        $this->db->where("Id",$nodeId);
        return $this->db->get("W2_Nodes")->row_object();
    }

    function getSelfcareUser($username)
    {
        $query = "SELECT Id, Name, Password__c, Allow_Nodes__c FROM Selfcare_User__c where Name = '{$username}'";
        $response = $this->sfdc->query(($query));
      
        foreach ($response->records as $record)
        {
          return $record;
        }
    }

    function getNodeByNodeReference($Node_reference__c)
    {
        $query = "SELECT Id, Reference__c, Name, Account_Reference__c, Billing_Account__c, Organizational_type__c, User_Node__c, Node_reference__c FROM Node__c where Node_reference__c = '{$Node_reference__c}'";
        $response = $this->sfdc->query(($query));

        foreach ($response->records as $record)
        {
            return $record;
        }
    }

    function getNodeByReference($Reference__c)
    {
        $query = "SELECT Id, Reference__c, Name, Account_Reference__c, Billing_Account__c, Organizational_type__c, User_Node__c, Node_reference__c FROM Node__c where Reference__c = '{$Reference__c}'";
        $response = $this->sfdc->query(($query));

        foreach ($response->records as $record)
        {
            return $record;
        }
    }

    function getSite($nodeId)
    {
        $this->db->select("Id, SiteId, Bridge, Product, Extension");
        $this->db->where("NodeId", $nodeId);
        $this->db->where("IsInactive", 0);
        return $this->db->get("W2_PrimaryGroups")->row_object();
    }

    function getWebexSite($nodeId)
    {
        $this->db->select("Id, SiteId, Bridge, Product");
        $this->db->where("NodeId", $nodeId);
        $this->db->where("IsInactive", 0);
        return $this->db->get("W2_WebExPrimaryGroups")->row_object();
    }

    function getBridge($Id)
    {
        $query = "SELECT Id, Name, Product__c FROM Bridge__c where Id = '{$Id}'";
        $response = $this->sfdc->query(($query));

        foreach ($response->records as $record)
        {
            return $record;
        }
    }

    function getProduct($Id)
    {
        $query = "SELECT Id, Name FROM Product_ARK__c where Id = '{$Id}'";
        $response = $this->sfdc->query(($query));

        foreach ($response->records as $record)
        {
            return $record;
        }
    }

    function resetSelfcarePassword($Id, $password)
    {
        $sObject = new stdclass();
        $sObject->Id = $Id;
        $sObject->Password__c = $password;

        $createResponse = $this->sfdc->update(array($sObject),'Selfcare_User__c');

        foreach ($createResponse as $createResult)
        {
            return $createResult;
        }
    }
}