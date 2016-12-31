<?php
$retrive = new DB_retrival;
			require $_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
if ($retrive->isLoggedIn() == true && $social2['twitter_enabled'] == '1' && $social2['console_enabled'] == '1'){
if (isset($_GET['favoriteid'])){
	
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("favorites/create", ["id" => $_GET['favoriteid']]);
		echo "Favorited";
		exit;
}

if (isset($_GET['disfavoriteid'])){

		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("favorites/destroy", ["id" => $_GET['disfavoriteid']]);
		echo "Disfavorited";
		exit;
}

if (isset($_GET['retweetid'])){

		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("statuses/retweet/".$_GET['retweetid']."");
		echo "Retweeted";
		exit;
}

if (isset($_GET['disretweetid'])){

		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("statuses/unretweet/".$_GET['retweetid']."");
		echo "Disretweeted";
		exit;
}

    if (isset($_POST))
{
	$update = '';
	$replyid = '';
	
	if (isset($_POST['statusupdate'])){
	if (empty($_POST['statusupdate'])){
				$resp = array();
				$resp['divfail'] = true;
				$resp['message'] = 'Must have something to tweet.';
			
                echo json_encode($resp);
		        exit;
	} else {
	$update = $_POST['statusupdate'];
	}
	} else if (isset($_POST['statusupdate2'])){
	if (empty($_POST['statusupdate2'])){
				$resp = array();
				$resp['divfail'] = true;
				$resp['message'] = 'Must have something to tweet.';
			
                echo json_encode($resp);
		        exit;
	} else {
	$update = $_POST['statusupdate2'];
	}
	} else if (isset($_POST['dmupdate'])){
	if (empty($_POST['dmupdate'])){
				$resp = array();
				$resp['divfail'] = true;
				$resp['message'] = 'Must have something to send.';
			
                echo json_encode($resp);
		        exit;
	} else {
	$update = $_POST['dmupdate'];
	}
	}
	
	if (isset($_POST['replyid'])){
	$replyid = $_POST['replyid'];
	} else if (isset($_POST['replyid2'])){
	$replyid = $_POST['replyid2'];
	}
	
	if (isset($_POST['dmupdate'])){
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("direct_messages/new", ["screen_name" => $_SESSION['socialpost']['convouser'], "text" => $update]);

							        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['divsubmit'] = true;
				$resp['message'] = 'Message sent.';
			
                echo json_encode($resp);
		        exit;
	}
	}
	
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("statuses/update", ["in_reply_to_status_id" => $replyid, "status" => $update]);

							        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['divsubmit'] = true;
				$resp['message'] = 'Update sent.';
			
                echo json_encode($resp);
		        exit;
	}
}

}  else {
 header("HTTP/1.0 403 Forbidden");
 die();
}
?>
