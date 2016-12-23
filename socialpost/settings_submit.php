<?php

$global = new DB_global;

if(isset($_POST)){
			$global->sqlquery("UPDATE `ddp_socialpost` SET `console_enabled` = '".$_POST['consoleenabled']."'");
			        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>Social console settings updated.</p>';
			
                echo json_encode($resp);
		        exit;
	}
}