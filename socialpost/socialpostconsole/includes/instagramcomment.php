<?php
$retrive = new DB_retrival;
$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
if ($retrive->isLoggedIn() == true && $social2['instagram_enabled'] == '1' && $social2['console_enabled'] == '1'){
    if (isset($_POST))
{
if (isset($_POST['dmbox'])){
	$id = $_POST['dmrecp'];
	$comment = $_POST['dmbox'];
} else {
	$comment = $_POST['gramcommentbox'];
	$id = $_POST['gramcommentid'];
}
	
require($_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/vendor/autoload.php");
$debug = false;
$truncatedDebug = false;
$instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug);
$username = $social2['instagram_username'];
$password = $social2['instagram_password'];
$instagram->setUser($username, $password);
$instagram->login();

if (isset($_POST['dmbox'])){
$instagram->direct_message($id, $comment);


							        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['success'] = true;
			
                echo json_encode($resp);
		        exit;
	}
}

$instagram->comment($id, $comment);

							        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['success'] = true;
			
                echo json_encode($resp);
		        exit;
	}
}
}  else {
 header("HTTP/1.0 403 Forbidden");
 die();
} ?>
