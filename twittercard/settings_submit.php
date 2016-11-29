<?php

$global = new DB_global;

if(isset($_POST)){
			$global->sqlquery("UPDATE `ddp_twittercard` SET `twitter_username` = '".$_POST['twitterusername']."', `twitter_card` = '".$_POST['cardtype']."'");
			        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>Twitter Card settings updated.</p>';
			
                echo json_encode($resp);
		        exit;
	}
}