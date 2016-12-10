<?php
if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

			require $_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
			require $_SERVER['DOCUMENT_ROOT'].'/plugins/socialpost/Facebook/autoload.php';
			require $_SERVER['DOCUMENT_ROOT'].'/plugins/socialpost/Pinterest/autoload.php';
			use DirkGroenen\Pinterest\Pinterest;

class socialpost extends plugin {

    static function inc_post_form_bottom_error(){
		if(isset($_POST['posttotwitter']) && $_POST['posttotwitter'] == '1' && strlen('New in "'.$_POST['category'].'" - '.$GLOBALS['posttitle'].' https://'.$_SERVER['HTTP_HOST'].'/examplexxx '.$_POST['twitterextra']) > 140)  {
		$_SESSION['errors']['twitterextra'] = "Twitter post cannot be longer than 140 characters.";
		$hasError = true;	
	}
	
			if(isset($hasError)){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode($_SESSION['errors']);
                exit;
	}}
	}
	
    static function inc_post_form_bottom(){
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
				if (isset($_POST['posttotwitter']) && $_POST['posttotwitter'] == '1'){
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$tconnection->post("statuses/update", ["status" => 'New in "'.$_POST['category'].'" - '.$GLOBALS['posttitle'].' https://'.$_SERVER['HTTP_HOST'].'/'.$GLOBALS['shortlink'].' '.$_POST['twitterextra']]);
				}
				if (isset($_POST['posttofacebook']) && $_POST['posttofacebook'] == '1'){
		$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.2',
]);
 if (isset($GLOBALS['embedlink'])){
$embedlink = $GLOBALS['embedlink'];
 } else if (isset($GLOBALS['embedlinkinstagram'])) {
$embedlink = $GLOBALS['embedlinkinstagram'];
 } else if (isset($GLOBALS['embedlinkyoutube'])) {
$embedlink = $GLOBALS['embedlinkyoutube'];
 } else if (isset($GLOBALS['embedlinkimgur'])) {
$embedlink = $GLOBALS['embedlinkimgur'];
 } else if (isset($GLOBALS['embedlinksoundcloud'])) {
$embedlink = $GLOBALS['embedlinksoundcloud'];
 }

 if (isset($embedlink)){
$linkData = [
 'link' => $_SERVER['HTTP_HOST'].'/'.$GLOBALS['shortlink'],
 'message' => strip_tags($GLOBALS['$postsummary']),
 'image' => $embedlink,
 'name' => $GLOBALS['posttitle']
];
 } else {
$linkData = [
 'link' => $_SERVER['HTTP_HOST'].'/'.$GLOBALS['shortlink'],
 'message' => strip_tags($GLOBALS['$postsummary']),
 'name' => $GLOBALS['posttitle']
];
 }
$pageAccessToken = $social2['facebook_accesstoken'];
	
$fb->post('/'.$social2['facebook_pagename'].'/feed', $linkData, $pageAccessToken);
				}
				if (isset($_POST['posttopinterest']) && $_POST['posttopinterest'] == '1'){
 if (isset($GLOBALS['embedlink'])){
$embedlink = $GLOBALS['embedlink'];
 } else if (isset($GLOBALS['embedlinkinstagram'])) {
$embedlink = $GLOBALS['embedlinkinstagram'];
 } else if (isset($GLOBALS['embedlinkyoutube'])) {
$embedlink = $GLOBALS['embedlinkyoutube'];
 } else if (isset($GLOBALS['embedlinkimgur'])) {
$embedlink = $GLOBALS['embedlinkimgur'];
 } else if (isset($GLOBALS['embedlinksoundcloud'])) {
$embedlink = $GLOBALS['embedlinksoundcloud'];
 }

$pinterest = new Pinterest($social2['pinterest_apikey'], $social2['pinterest_apisecret']);
$pinterest->auth->setOAuthToken($social2['pinterest_token']);
$pinterest->pins->create(array(
    "note"          => $GLOBALS['posttitle'],
    "image_url"     => $embedlink,
    "board"         => $social2['pinterest_board'],
	"link"          => 'http://'.$_SERVER['HTTP_HOST'].'/'.$GLOBALS['shortlink']
));
				}
    }
	
    static function post_form_bottom(){
		$global = new DB_global;
			if (empty($_GET["postid"])){
		$ifenabled1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$ifenabled2 = $ifenabled1->fetch_assoc();
        echo '<div class="sitescrolling">';
		if($ifenabled2['twitter_enabled'] == '1'){
		echo '<br><input type="checkbox" name="posttotwitter" value="1" checked> Post to Twitter</div>';
		echo '<script>$( document ).ready(function() {
			$("textarea#twitterexample").val("New in \"" + $("#post select[name=category]").val() + "\" - " + $("#post input[name=posttitle]").val() + " https://'.$_SERVER['HTTP_HOST'].'/examplexxx " + $("#post input[name=twitterextra]").val());
						    var left = 140 - $("textarea#twitterexample").val().length;
    if (left < 0) {
        left = 0;
    }
    $(".countdown").text("Characters left: " + left);
		})
		$( "#post" ).bind("change keyup", function(event){
		$("textarea#twitterexample").val("New in \"" + $("#post select[name=category]").val() + "\" - " + $("#post input[name=posttitle]").val() + " https://'.$_SERVER['HTTP_HOST'].'/examplexxx "  + $("#post input[name=twitterextra]").val()) ;
    var left = 140 - $("textarea#twitterexample").val().length;
    if (left < 0) {
        left = 0;
    }
    $(".countdown").text("Characters left: " + left);
	});</script>';
		echo '<textarea style="width:100%; height:50px; color:#000; background-color:white; resize:none;" maxlength="140" disabled id="twitterexample"></textarea>';
		echo '<span class="countdown" style="font-size: 20px;"></span>';
		$twitterextra = '';
		if (isset($ifenabled2['twitter_extradefault'])){
		$twitterextra='value="'.$ifenabled2['twitter_extradefault'].'"';
		}
		echo '<br><br><label title="twitterextra"><b>Add to Twitter post:</b></label>
		<br><input type="text" name="twitterextra" '.$twitterextra.'>';
				}
		if($ifenabled2['facebook_enabled'] == '1'){
		echo '<br><div class="sitescrolling"><br><input type="checkbox" name="posttofacebook" value="1" checked> Post to Facebook';
		}
				if($ifenabled2['pinterest_enabled'] == '1'){
		echo '<br><input type="checkbox" name="posttopinterest" value="1" checked> Post to Pinterest';
				}
		echo '</div>';
		echo '<script>$(';echo"'";echo'input[type="checkbox"]';echo"').change(function(){
    this.value = (Number(this.checked));
	});</script>";
			}
    }
}
?>
