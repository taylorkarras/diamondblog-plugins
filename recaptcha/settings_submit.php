<?php

$global = new DB_global;

if(isset($_POST)){
			$global->sqlquery("UPDATE `ddp_recaptcha` SET `recaptcha_sitekey` = '".$_POST['sitekey']."', recaptcha_secret = '".$_POST['secret']."'");
			        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>ReCaptcha code updated.</p>';
			
                echo json_encode($resp);
		        exit;
	}
}