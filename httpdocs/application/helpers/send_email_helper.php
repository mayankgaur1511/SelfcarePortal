<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('./application/third_party/PHPMailer-master/PHPMailerAutoload.php');

if ( ! function_exists('send_email')){
    function send_email($html,$subject,$fromAddress,$toAddresses){

        if((!isset($html) or !isset($subject) or !isset($fromAddress) or !isset($toAddresses)) 
            or sizeof($fromAddress) < 1 or sizeof($toAddresses) < 1)
        {
            return false;
        }

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = "10.247.7.30";
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = false;
        $mail->Port       = 25;
        
        $mail->setFrom($fromAddress);
        foreach($toAddresses as $address)
        {
            $mail->addAddress($address['email']);
        }
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        
        if(!$mail->send()) 
        {
            log_message('error', $mail->ErrorInfo);
            return false;
        }

        log_message('info', "Reset password E-mail sent!");
        return true;
    }
}