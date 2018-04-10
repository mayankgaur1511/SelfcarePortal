<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit (220);

class CaseManagement extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Check if user is logged in
        if(!$this->session->userdata('logged_in'))
        {
            redirect("/login");
        }

        $this->load->model('Casefields_model','casefields');
        $this->load->model('History_model','history');
    }

    function queryCasesSearch($term)
    {
        $this->load->model("Salesforce_model","salesforce");
        $casesSf = $this->salesforce->queryCasesSearch($term);
        foreach($casesSf as $case)
        {
            
            $cases[] = array(
                'Id' => $case->Id,
                'CaseNumber' => $case->CaseNumber,
                'Status' => $case->Status,
                'Subject' => $case->Subject,
            );
        }

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($cases));
    }

    function getCaseNumber($id)
    {
        $this->load->model("Salesforce_model","salesforce");
        $casesSf = $this->salesforce->getCaseNumber($id);
        foreach($casesSf as $case)
        {
            
            $cases = array(
                'Id' => $case->Id,
                'CaseNumber' => $case->CaseNumber,
                'Status' => $case->Status
            );
        }

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($cases));
    }

    function index()
    {
        $this->load->model("Salesforce_model","salesforce");
        
        $data['cases'] = $this->salesforce->queryCases(
            $this->session->userdata("login"),
            $this->input->post('caseNumber'),
            $this->input->post('subject'),
            $this->input->post('status'),
            $this->input->post('offset'));
       
        
        $data['_title'] = $this->lang->line('my_open_cases');
        $data['_view'] = "caseManagement/index";
        $this->load->view('layout/layout',$data);
        
    }

    function details($caseId)
    {
        $this->load->model("Salesforce_model","salesforce");
        $data['case'] = $this->salesforce->getCase($caseId, $this->session->userdata('login'));

        $attachments = $this->salesforce->getAttachments($caseId);
        $documents = $this->salesforce->getDocuments($caseId);
        $files = array();
        foreach($attachments as $attachment)
        {
            $files[] = array(
                'id' => $attachment->Id,
                'fileName' => $attachment->Name,
                'objectType' => 'ATTACHMENT',
                'date' => $attachment->CreatedDate
            );
        }

        foreach($documents as $document)
        {
            $files[] = array(
                'id' => $document->Id,
                'fileName' => $document->Title . "." . strtolower($document->FileType),
                'objectType' => 'DOCUMENT',
                'date' => $document->CreatedDate
            );
        }
        $data['files'] = $files;


        $comments = array();
        $users = array();
        $caseComments = $this->salesforce->getCaseComments($caseId);

        foreach($caseComments as $comment)
        {
            $comm = array(
                'CreatedDate' => $comment->CreatedDate
            );
            
            if(strpos($comment->CommentBody, "#CUSTOMER COMMENT - ") !== false)
            {
                $comm['creator'] = "by you";
                $comm['body'] = str_replace("#CUSTOMER COMMENT - ","",$comment->CommentBody);
            }
            else{
                $comm['creator'] = "by Arkadin";
                $comm['body'] = $comment->CommentBody;
            }

            $data['comments'][] = $comm;
        }

        $data['_title'] = $this->lang->line('Case') . " #" .$data['case']->CaseNumber;
        $data['_view'] = "caseManagement/details";
        $this->load->view('layout/layout',$data); 
    }

    function new()
    {
        $data['countries'] = $this->casefields->Country__c();

        $data['_view'] = "caseManagement/new";
        $data['_title'] = $this->lang->line('new_case');
        $this->load->view('layout/layout',$data);
    }

    function createCase()
    {
        $this->load->model("Salesforce_model","salesforce");
        $case = $this->salesforce->createCase(
                    $this->input->post('ProductType__c'),
                    $this->input->post('Product__c'),
                    $this->input->post('Subject'),
                    $this->input->post('Description'),
                    $this->input->post('Category_NEW__c'),
                    $this->input->post('T_Country_impacted__c'),
                    $this->input->post('BusinessImpact__c'),
                    $this->input->post('Severity__c'),
                    $this->input->post('Product_L2__c'),
                    $this->input->post('Symptom_L1__c'),
                    $this->input->post('T_Country_impacted__c')
        );

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($case));
    }

    function updateCase()
    {
        $this->load->model("Salesforce_model","salesforce");
        $case = $this->salesforce->updateCase(
                    $this->input->post('CaseId'),
                    $this->input->post('ProductType__c'),
                    $this->input->post('Product__c'),
                    $this->input->post('Subject'),
                    $this->input->post('Description'),
                    $this->input->post('Category_NEW__c'),
                    $this->input->post('BusinessImpact__c'),
                    $this->input->post('Severity__c'),
                    $this->input->post('Product_L2__c'),
                    $this->input->post('Symptom_L1__c'),
                    $this->input->post('Moderator_PIN__c'),
                    $this->input->post('Web_Login__c'),
                    $this->input->post('ParentId'),
                    $this->input->post('Status'),
                    $this->input->post('Resolution'),
                    $this->input->post('Resolutioncategory'),
                    $this->input->post('Resolution_details'),
                    $this->input->post('Vendor')
        );

        if($case->success)
        {
            $this->session->set_flashdata('success',true);
            $this->history->add('update','case',$this->input->post('CaseId'),$this->input->post());
        }

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($case));
    }

    function getComments($caseId)
    {
        $this->load->model("Salesforce_model","salesforce");
        $comments = array();
        $users = array();
        $caseComments = $this->salesforce->getCaseComments($caseId);

        foreach($caseComments as $comment)
        {
            
            if(!isset($users[$comment->CreatedById]))
            {
                $users[$comment->CreatedById] = $this->salesforce->getUser($comment->CreatedById);
            }

            $comments[] = array(
                'CommentBody' => $comment->CommentBody,
                'CreatedDate' => $comment->CreatedDate,
                'User' => $users[$comment->CreatedById]
            );
        }

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($comments));
    }

    function addComment()
    {
        $this->load->model("Salesforce_model","salesforce");
        $comment = $this->salesforce->createCaseComment(
            $this->input->post('Id'),
            "#CUSTOMER COMMENT - " . $this->input->post('CommentBody')
        );

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($comment));
    }

    function addAttachment($caseId)
    {
        foreach($_FILES as $key => $file)
        {
            $fieldName = $key;
        }

        $fileName = $_FILES[$key]['name'];
        $fileContent = base64_encode(file_get_contents($_FILES[$key]['tmp_name']));

        $this->load->model("Salesforce_model","salesforce");
        $attachment = $this->salesforce->attachFile(
            $caseId,
            $fileName,
            $fileContent
        );

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($attachment));
        
    }

    function getAttachments($caseId)
    {
        $this->load->model("Salesforce_model","salesforce");
        $attachments = array();
        $users = array();
        $attachments_sf = $this->salesforce->getAttachments($caseId);

        foreach($attachments_sf as $attachment)
        {
            
            if(!isset($users[$attachment->OwnerId]))
            {
                $users[$attachment->OwnerId] = $this->salesforce->getUser($attachment->OwnerId);
            }

            $attachments[] = array(
                'Id' => $attachment->Id,
                'Name' => $attachment->Name,
                'CreatedDate' => $attachment->CreatedDate,
                'LastModifiedDate' => $attachment->LastModifiedDate,
                'User' => $users[$attachment->OwnerId]
            );
        }

        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($attachments));
    }

    function downloadAttachment($attachmentId)
    {
        $this->load->model("Salesforce_model","salesforce");
        $attachment = $this->salesforce->getAttachment($attachmentId);
        $this->load->helper('download');
        force_download($attachment->Name,$attachment->Body);
    }

    function downloadDocument($documentId)
    {
        $this->load->model("Salesforce_model","salesforce");
        $document = $this->salesforce->getDocument($documentId);
        // Get Document Details
        $details = $this->salesforce->getDocumentDetails($documentId);

        $this->load->helper('download');
        force_download($details->Title . "." . strtolower($details->FileType),$document->VersionData);
    }


    // GET REQUEST - CASE OBJECTS
    function ProductType__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->ProductType__c()));
    }

    function Product__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Product__c($this->input->get('ProductType__c'))));
    }

    function Status()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Status()));
    }

    function Resolution_details__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Resolution_details__c($this->input->get('Resolutioncategory__c'))));
    }

    function Vendor__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Vendor__c()));
    }

    function Category_NEW__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Category_NEW__c($this->input->get('ProductType__c'))));
    }

    function BusinessImpact__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->BusinessImpact__c($this->input->get('Category_NEW__c'))));
    }

    function Severity__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Severity__c($this->input->get('Category_NEW__c'))));
    }

    function Product_L2__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Product_L2__c($this->input->get('Product__c'))));
    }

    function Symptom_L1__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Symptom_L1__c($this->input->get('Product__c'))));
    }

    function Symptom_L2__c()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(200);
        $this->output->set_output(json_encode($this->casefields->Symptom_L2__c($this->input->get('Symptom_L1__c'))));
    }
}