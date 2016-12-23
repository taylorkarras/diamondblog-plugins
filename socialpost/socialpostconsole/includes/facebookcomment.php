<?php
$retrive = new DB_retrival;
		$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
		
if ($retrive->isLoggedIn() == true && $social2['console_enabled'] == '1' && $social2['facebook_enabled'] == '1'){
	
	if (empty($_POST['commentcontent'])){
				$resp = array();
				$resp['divfail'] = true;
				$resp['message'] = 'Must have something to comment.';
			
                echo json_encode($resp);
		        exit;
	}

		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
		$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.2',
]);

$linkData = [
 'message' => $_POST['commentcontent']
];

$pageAccessToken = $social2['facebook_accesstoken'];
	
$fb->post('/'.$_POST['postid'].'/comments', $linkData, $pageAccessToken);

							        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = 'Comment posted.';
			
                echo json_encode($resp);
		        exit;
	}
}