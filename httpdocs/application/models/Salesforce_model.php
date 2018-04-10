<?php

set_time_limit(900);
defined('BASEPATH') OR exit('No direct script access allowed');
define("SOAP_CLIENT_BASEDIR", "application/third_party/sfdc");
require_once (SOAP_CLIENT_BASEDIR.'/SforceEnterpriseClient.php');
require_once (SOAP_CLIENT_BASEDIR.'/SforceHeaderOptions.php');

class Salesforce_model extends CI_Model {

    function __construct()
    {
        // Authenticate to SFDC
        try {
			$this->sfdc = new SforceEnterpriseClient();
		  	$this->sfdc->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
            $this->sfdc->login("apac.informationsystems@arkadin.com", "ISAPAC_1234****1nbJeixUV4ilRmpEy5H53q4bn");
        }
        catch (Exception $e) {
            echo $sfdc->getLastRequest();
            echo $e->faultstring;
        }

        $this->nodes = array();
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

    function getNode($nodeId)
    {
        $query = "SELECT Id, Reference__c, Spirit_Customer_Profile_Id__c, Name, Account_Reference__c, Billing_Account__c, Organizational_type__c, User_Node__c, Node_reference__c FROM Node__c where Id = '{$nodeId}'";
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

    /*
    *   CASE MANAGAMENT
    */

    function createCase($productType, $product, $subject, $description, $category, $country, $businessImpact, $severity, $productL2, $symptomL1)
    {
        $sObject = new stdclass();
        $sObject->Status = 'New';
        $sObject->ProductType__c = $productType;
        $sObject->Product__c = $product;
        $sObject->Subject = $subject;
        $sObject->Description = $description;
        $sObject->Category_NEW__c = $category;
        $sObject->T_Country_impacted__c = $country;
        $sObject->BusinessImpact__c = $businessImpact;
        $sObject->Severity__c = $severity;
        $sObject->Product_L2__c = $productL2;
        $sObject->Symptom_L1__c = $symptomL1;
        $sObject->Origin = "Customer Portal";
        $sObject->AccountId = "0013000000OMBV5"; // Arkadin Account
        $sObject->On_behalf_Supplied_Email__c = $this->session->userdata('login');
        $sObject->Company_Name__c = 'NO SRM CONTRACTED';
        $createResponse = $this->sfdc->create(array($sObject), 'Case');

        foreach($createResponse as $createResult)
        {
            return $createResult;
        }
    }

    function attachFile($parentId,$title,$file)
    {
        $sObject = new stdclass();
        $sObject->ParentId = $parentId;
        $sObject->Name = $title;
        $sObject->body = $file;

        $createResponse = $this->sfdc->create(array($sObject),"Attachment");

        foreach ($createResponse as $createResult)
        {
            return $createResult;
        }
    }

    function getUser($userId)
    {
        $query = "SELECT FirstName,LastName,Email from User where Id = '{$userId}'";
        $response = $this->sfdc->query(($query));
      
        foreach ($response->records as $record)
        {
          return $record;
        }
    }

    function getGroup($userId)
    {
        $query = "SELECT Name from Group where Id = '{$userId}'";
        $response = $this->sfdc->query(($query));
      
        foreach ($response->records as $record)
        {
          return $record;
        }
    }

    function getAttachments($caseId)
    {
        $query = "SELECT Id, Name, ContentType, OwnerId, CreatedDate, LastModifiedDate from Attachment where ParentId = '{$caseId}' ORDER BY CreatedDate DESC";
        $response = $this->sfdc->query(($query));
        return $response->records;
    }

    function getAttachment($attachmentId)
    {
        $query = "SELECT  Name, Body from Attachment  where Id = '{$attachmentId}'";
        $response = $this->sfdc->query(($query));
      
        foreach ($response->records as $record)
        {
          return $record;
        }
    }


    function getDocuments($caseId)
    {
        $documents = array();
        $query = "SELECT  ContentDocumentId  from ContentDocumentLink  where  LinkedEntityId = '{$caseId}'";
        $response = $this->sfdc->query(($query));
        foreach($response->records as $record)
        {
            $query = "SELECT  Id, Title, FileType, CreatedDate from ContentDocument where Id = '{$record->ContentDocumentId}'";
            $response = $this->sfdc->query(($query));
    
            foreach ($response->records as $record)
            {
                $documents[] = $record;
            }
        }
        return $documents;
    }

    function getDocument($documentId)
    {
        $query = "SELECT VersionNumber, IsMajorVersion, VersionData from ContentVersion  where  ContentDocumentId = '{$documentId}' and IsMajorVersion = true";
        $response = $this->sfdc->query(($query));
        foreach ($response->records as $record)
        {
          return $record;
        }
    }

    function getDocumentDetails($documentId)
    {
        $query = "SELECT  Id, Title, FileType, CreatedDate from ContentDocument where Id = '{$documentId}'";
        $response = $this->sfdc->query(($query));
        foreach ($response->records as $record)
        {
          return $record;
        }
    }


    function queryCases($login,$caseNumber,$subject,$status,$offset)
    {
        $query = "SELECT Id, CaseNumber, Status, CreatedDate, CreatedById, LastModifiedDate, On_behalf_Supplied_Email__c, ".
                 "ProductType__c, Product__c, Subject, Category_NEW__c, OwnerId from Case where On_behalf_Supplied_Email__c = '$login'";
        
        if($caseNumber){
            $query = $query . " and CaseNumber = '{$caseNumber}'";
        }

        if($subject){
            $query = $query . " and (Subject like '%{$subject}%')";
        }
        
        if($status)
        {
            foreach($status as $st)
            {
                if(!isset($status_query))
                {
                    $status_query = " and (status='" . $st ."'";
                }
                else{
                    $status_query = $status_query . " or status='" . $st ."'";
                }
            }

            if(isset($status_query))
            {
                $status_query = $status_query . ")";
                $query = $query . $status_query;
            }
        }

        if($offset)
        {
            $query = $query . " ORDER BY CaseNumber DESC LIMIT 1000 OFFSET {$offset}";
        }
        else{
            $query = $query . " ORDER BY CaseNumber DESC LIMIT 1000 OFFSET 0";
        }

        $response = $this->sfdc->query(($query));
        return $response->records;
    }


    function getCaseNumber($id)
    {
        $query = "SELECT Id, CaseNumber, Status ".
                 "from Case where TECH_Country__c = 'China' and Id = '{$id}'";
    
        $response = $this->sfdc->query(($query));
        return $response->records;
    }

    function getCase($caseId, $login)
    {
        $query =    "SELECT Id, Status, CaseNumber, Reasonclosure__c, Resolutioncategory__c, Resolution_details__c, ClosedDate, CreatedDate, LastModifiedDate, ProductType__c, Product__c, Subject, ".
                    "Description, Category_NEW__c, T_Country_impacted__c, Web_Login__c,Vendor__c, Moderator_PIN__c, ".
                    "BusinessImpact__c, Severity__c, OwnerId, On_behalf_Supplied_Email__c, ParentId, Product_L2__c,Symptom_L1__c, Symptom_L2__c ".
                    "from Case where Id = '{$caseId}' and On_behalf_Supplied_Email__c = '$login'";

        $response = $this->sfdc->query(($query));
      
        foreach ($response->records as $record)
        {
          return $record;
        }
    }

    function createCaseComment($caseId,$comment)
    {
        $sObject = new stdclass();
        $sObject->ParentId = $caseId; 
        $sObject->CommentBody = $comment;
        $createResponse = $this->sfdc->create(array($sObject),"CaseComment");

        foreach ($createResponse as $createResult)
        {
            return $createResult;
        }
    }

    function getCaseComments($caseId)
    {
        $query =    "SELECT CreatedById, CommentBody, CreatedDate from CaseComment ".
                    "where ParentId = '{$caseId}' and IsDeleted = false ORDER BY CreatedDate DESC";

        $response = $this->sfdc->query(($query));
      
        return $response->records;
    }
}