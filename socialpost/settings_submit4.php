<?php

$global = new DB_global;
unset($_SESSION["errors"]);

if(isset($_POST)){

if ($_POST['instagramenabled'] == '0'){
		$global->sqlquery("UPDATE `ddp_socialpost` SET `instagram_enabled` = '".$_POST['instagramenabled']."'");
						$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>Instagram settings updated.</p>';
			
                echo json_encode($resp);
		        exit;} else if ($_POST['instagramenabled'] == '1'){
					
if(trim($_POST['instagramusername']) === '' && $_POST['instagramenabled'] == '1')  {
		$_SESSION['errors']['instagramusername'] = "You cannot leave the username blank.";
		$hasError = true;
	} else {
		$instagramusername = $_POST['instagramusername'];
	}
if(trim($_POST['instagrampassword']) === '' && $_POST['instagrampassword'] == '1')  {
		$_SESSION['errors']['instagrampassword'] = "You cannot leave the password blank.";
		$hasError = true;
	} else {
		$instagrampassword = $_POST['instagrampassword'];
	}
	if(isset($hasError)){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode($_SESSION['errors']);
                exit;
	}} else {
			$global->sqlquery("UPDATE `ddp_socialpost` SET `instagram_enabled` = '".$_POST['instagramenabled']."', `instagram_username` = '".$instagramusername."', `instagram_password` = '".$instagrampassword."'");
			        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>Twitter settings updated.</p>';
			
                echo json_encode($resp);
		        exit;
	}
	}
}
}