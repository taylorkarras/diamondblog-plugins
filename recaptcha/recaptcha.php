<?php

if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class recaptcha extends plugin {

    static function post_top(){
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
        return true;
    }
	
    static function page_top(){
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
        return true;
    }
	
    static function comment_captcha(){
		$global = new DB_global;
		$recaptcha1 = $global->sqlquery("SELECT * from ddp_recaptcha");
		$recaptcha2 = $recaptcha1->fetch_assoc();
        echo '<div class="g-recaptcha" data-sitekey="'.$recaptcha2['recaptcha_sitekey'].'"></div>';
        return true;
    }
	
    static function captcha(){
		$global = new DB_global;
		$recaptcha1 = $global->sqlquery("SELECT * from ddp_recaptcha");
		$recaptcha2 = $recaptcha1->fetch_assoc();
		$secret = $recaptcha2['recaptcha_secret'];
$response=$_POST["g-recaptcha-response"];
$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success=json_decode($verify);


if ($captcha_success->success==false) {
	$_SESSION['errors']['commentip'] = "Please successfully complete the captcha.";
	$hasError = true;	
}
        return true;
    }
}
?>