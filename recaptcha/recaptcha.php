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

static function user_contact(){
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
        return true;
    }

	
    static function page_top(){
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
        return true;
    }
	
    static function ampcomments_captcha(){
        echo "<script src='https://www.google.com/recaptcha/api.js'
        async defer></script>";
        return true;
    }
	
    static function comment_captcha(){
		$global = new DB_global;
		$recaptcha1 = $global->sqlquery("SELECT * from ddp_recaptcha");
		$recaptcha2 = $recaptcha1->fetch_assoc();
        echo '<div class="g-recaptcha" data-sitekey="'.$recaptcha2['recaptcha_sitekey'].'"></div>';
		echo '<input type="hidden" name="captcha">';
        return true;
    }
	
    static function captcha(){
		$global = new DB_global;
		$check = new DB_check;
		if (!$check->isLoggedIn()){	
		$recaptcha1 = $global->sqlquery("SELECT * from ddp_recaptcha");
		$recaptcha2 = $recaptcha1->fetch_assoc();
		$secret = $recaptcha2['recaptcha_secret'];
$response=$_POST["g-recaptcha-response"];
$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success=json_decode($verify);


if ($captcha_success->success==false) {
	$_SESSION['errors']['captcha'] = "Please successfully complete the captcha.";
	$hasError = true;	
}

		if(isset($hasError)){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode($_SESSION['errors']);
                exit;
	}}

        return true;
    }
	}
}
?>
