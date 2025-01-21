<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!defined('APPPATH')) exit('No direct script access allowed');



if(!function_exists('help_nombreWeb')){
    function help_nombreWeb(){
		return 'Andamios Andar';
    }
}

if(!function_exists('stringAleatorio')){
    function stringAleatorio($length = 5, $case = 1){
		$characters       = $case == 1 ? '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString     = '';
	    for($i = 0; $i < $length; $i++){
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
    }
}

if(!function_exists('help_sendMail')){
    function help_sendMail($from, $to, $subject, $body, $username = 'anunciosdelvalle2024@gmail.com', $password = 'gpehshlexrtyfbuc'){
        $mail = new PHPMailer(true);  
        try {            
            $mail->SMTPDebug = 0;
            $mail->isSMTP();  
            $mail->Host         = 'smtp.gmail.com'; //smtp.google.com
            $mail->SMTPAuth     = true;     
            $mail->Username     = $username;  
            $mail->Password     = $password;
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;  
            $mail->Port         = 465;  
            $mail->setFrom($from[0], $from[1]);
            
            $mail->addAddress($to);  
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject      = $subject;
            $mail->Body         = $body;
            
            if(!$mail->send()) {
                //echo "Ocurrió un problema. Por favor vuelve a intentar.";
                return false;
            }
            else {
                //echo "Email enviado!.";
                return true;
            }
            
        } catch (Exception $e) {
            //echo "Hubo un problema." .$e;
            //echo "Hubo un problema, Inténtelo en un momento.";
            return false;
        }
    }
}
?>