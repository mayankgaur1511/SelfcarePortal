<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcomepack_model extends CI_Model {

    function replace($content,$CLIENT_NAME,$WEB_LOG_REF,$MOD_PIN,$PART_PIN,$ANYWHERE_URL,$WEBEX_URL,$WEBEX_USER,$WEBEX_PSWD,$T_NUMBER,$TF_NUMBER)
    {
        $content = str_replace("CLIENT_NAME",$CLIENT_NAME,$content);
        $content = str_replace("WEB_LOG_REF",$WEB_LOG_REF,$content);
        $content = str_replace("MOD_PIN",$MOD_PIN,$content);
        $content = str_replace("PART_PIN",$PART_PIN,$content);
        $content = str_replace("WEBEX_URL",$WEBEX_URL,$content);
        $content = str_replace("ANYWHERE_URL",$ANYWHERE_URL,$content);
        $content = str_replace("WEBEX_USER",$CLIENT_NAME,$content);
        $content = str_replace("WEBEX_PWD",$CLIENT_NAME,$content);
        $content = str_replace("T_NUMBER",$T_NUMBER,$content);
        $content = str_replace("TF_NUMBER",$TF_NUMBER,$content);

        return $content;
    }
}