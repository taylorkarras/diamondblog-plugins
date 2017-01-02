<?php $retrive = new DB_retrival;
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();	
if ($retrive->isLoggedIn() == false){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
} else {
if ($retrive->restrictpermissionlevel('2') or $social2['facebook_enabled'] == '0' or $social2['console_enabled'] == '0'){} else {

?>
<html>
<head>
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/styles/facebook.css" rel="stylesheet" type="text/css">
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/scripts/jquery-2.2.3.min.js"></script>
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/facebook.js"></script>
<div id="facebookbar">
<ul class="tabs">
<li><a href="/console/social/facebook/" alt="Main" title="Main" class="active maintab">&#128441;</a></li>
<li><a href="/console/social/facebook/dms" alt="Messages" title="Messages" class="messagestab">&#9993;</a></li>
</ul>
</div>
<?php 
if(strpos($_SERVER['REQUEST_URI'], 'deletecomment')){
		
				$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.8',
]);

$pageAccessToken = $social2['facebook_accesstoken'];

$linkData = [];

$fb->delete('/'.$_GET['deletecomment'], $linkData, $pageAccessToken);

header("Location: ".$_SESSION['referral_url']['facebook']);

}
if(strpos($_SERVER['REQUEST_URI'], 'postid')){
$_SESSION['referral_url']['facebook'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
				$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.8',
]);

$pageAccessToken = $social2['facebook_accesstoken'];
	
$postinit = $fb->get('/'.$_GET['postid'], $pageAccessToken);
$post = $postinit->getDecodedBody();
echo '<div id="overflow">';
echo '<div class="facebookpost">';
$fbdate = new DateTime($post['created_time']);
echo '<div class="facebookpostdate">'.$fbdate->format('F j, Y').' at '.$fbdate->format('g:i A').'</div>';
echo '<div class="facebookmessage">'.$post['message'].'</div>';
echo '</div>';?>
<form id="facebookpostcomment">
<label>Comment:</label>
<textarea name="commentcontent" id="commentcontent"></textarea>
<input type="hidden" name="postid" value="<?php echo $_GET['postid']?>">
<input type="submit" value="Post" style="float:right">
</form>
<?php

$commentsinit = $fb->get('/'.$_GET['postid'].'/comments', $pageAccessToken);
$comments = $commentsinit->getDecodedBody();
foreach($comments['data'] as $fbcomment){
echo '<div class="facebookcomment">';
$fbdate = new DateTime($fbcomment['created_time']);
echo '<div class="facebookpostdate"><div class="facebookname">'.$fbcomment['from']['name'].'</div> '.$fbdate->format('F j, Y').' at '.$fbdate->format('g:i A').'</div>';
echo '<div class="facebookmessage">'.$fbcomment['message'].'</div>';
echo '<div class="facebookcomments"><a href="/console/social/facebook?deletecomment='.$fbcomment['id'].'" title="Delete Comment" alt="Delete Comment">Delete Comment</a></div>';
echo '</div>';
}
echo '</div>';}
else {
		
				$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.8',
]);

$pageAccessToken = $social2['facebook_accesstoken'];
	
$pagefeedinit = $fb->get('/'.$social2['facebook_pagename'].'/feed', $pageAccessToken);
$pagefeed = $pagefeedinit->getDecodedBody();
echo '<div id="overflow">';?>
<form id="facebookfeedpost">
<label>New Update:</label>
<textarea name="feedcontent" id="feedcontent"></textarea>
<input type="submit" value="Post" style="float:right">
</form>
<?php
foreach($pagefeed['data'] as $fbpost){
echo '<div class="facebookpost">';
$fbdate = new DateTime($fbpost['created_time']);
echo '<div class="facebookpostdate">'.$fbdate->format('F j, Y').' at '.$fbdate->format('g:i A').'</div>';
echo '<div class="facebookmessage">'.$fbpost['message'].'</div>';
echo '<div class="facebookcomments"><a href="/console/social/facebook?postid='.$fbpost['id'].'" title="Get comments for this post" alt="Get comments for this post">Get comments for this post</a></div>';
echo '</div>';
}
echo '</div>';} ?>
</body>
</html>
<?php exit; }}?>
