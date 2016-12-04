<?php 

if (isset($_POST)){

	$check = new DB_check;
	if ($check->ifbanned()){
	}else{
		
	$global = new DB_global;
	$parsed_link = parse_url ($_SERVER['HTTP_REFERER']);
	$link = str_replace("/", "", $parsed_link['path']);
	$linkinit = $global->sqlquery("SELECT content_id FROM dd_content WHERE content_permalink = '".$link."';");
	$post_id = $linkinit->fetch_assoc();
	$vote_post_id = $post_id['content_id'];
	$vote_ip = $_SERVER['REMOTE_ADDR'];
		
	$vote_id = $global->sqllastid("INSERT INTO `ddp_reviewratings_user` (`u_ip`, `u_ratingid`, `u_postid`, `u_rated`) VALUES ('".$vote_ip."', NULL, '".$vote_post_id."', '".$_POST['userrating']."');");
	
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	   $_SESSION['resp']['formrefresh'] = true;
       echo json_encode($_SESSION['resp']);
       exit;
	}}
}
