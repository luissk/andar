<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!defined('APPPATH')) exit('No direct script access allowed');



if(!function_exists('help_nombreWeb')){
    function help_nombreWeb(){
		return 'Andamios Andar';
    }
}

if(!function_exists('help_stringRandom')){
    function help_stringRandom($length = 5, $case = 1){
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

if(!function_exists('help_calcularPresu')){
    function help_calcularPresu($precio,$periodo,$nroperiodo,$porcpre,$porcsem){
        $res = 0;
        $p_pre = (1 + $porcpre/100);
        $p_sem = (1 + $porcsem/100);

        if( $periodo == 'd' && $nroperiodo <= 6 ){
            $res = $precio / 4 * $p_pre * $p_sem;
        }else if( $periodo == 's' ){
            if( $nroperiodo < 4 ){
                $res = $precio / 4 * $nroperiodo * $p_pre * $p_sem;
            }
            if( $nroperiodo % 4 == 0 ){//es mes
                $nromes = $nroperiodo / 4;
                $res    = $precio * $nromes * $p_pre;
            }
            if( $nroperiodo > 4 && $nroperiodo % 4 != 0 ){
                $resu = $nroperiodo / 4;
                $mes  = intval($resu);
                $dec  = $resu - $mes;
                $sem  = 4 * $dec;

                $res = ($precio * $mes * $p_pre) + ($precio / 4 * $sem * $p_pre * $p_sem);
            }
        }else if( $periodo == 'm' ){
            $res = $precio * $nroperiodo * $p_pre;//para items
        }

        return $res;
    }
}

if(!function_exists('help_statusPresu')){
    function help_statusPresu($estado){
        $msj = "";
        if( $estado == 1 )
            $msj = "Activo";
        else if( $estado == 2 )
            $msj = "Con Guía";
        else if( $estado == 3 )
            $msj = "Entregado";
        else if( $estado == 4 )
            $msj = "Devuelto";

        return $msj;
    }
}

?>