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
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/includes/console/scripts/jquery-2.2.3.min.js"></script>
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body onload="$('.convooverflow').scrollTop($('.convooverflow')[0].scrollHeight);">
<style>body {
	height: 100%;
}</style>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/facebook.js"></script>
<div id="facebookbar">
<ul class="tabs">
<li><a href="/console/social/facebook/" alt="Main" title="Main" class="maintab">&#128441;</a></li>
<li><a href="/console/social/facebook/dms" alt="Messages" title="Messages" class="active messagestab">&#9993;</a></li>
</ul>
</div>
<div id="messages">
<div id="inboxscroll">
<?php
				$fb = new Facebook\Facebook([
 'app_id' => $social2['facebook_apikey'],
 'app_secret' => $social2['facebook_apisecret'],
 'default_graph_version' => 'v2.8',
]);

$pageAccessToken = $social2['facebook_accesstoken'];
	
$convosinit = $fb->get('/'.$social2['facebook_pagename'].'/conversations?fields=senders,message_count', $pageAccessToken);
$convos = $convosinit->getDecodedBody();
//var_dump($convos);

foreach($convos['data'] as $convosid){
echo '<a href="?convoid='.$convosid['id'].'"><div class="convothread';if ($_GET['convoid'] == $convosid['id']){
echo ' convoactive';	
}echo '">';
$imageinit = $fb->get('/'.$convosid['senders']['data']['0']['id'].'/picture?redirect=0', $pageAccessToken);
$image = $imageinit->getDecodedBody();
echo '<img class="convoinboximage" src="'.$image['data']['url'].'" title="'.$convosid['senders']['data']['0']['name'].'" alt="'.$convosid['senders']['data']['0']['name'].'"><div class="convocount">('.$convosid['message_count'].')</div>';
echo '</div></a>';
}
?>
</div>
<div id="messagedisplay"><div class="messagetable"><?php if (isset($_GET['convoid'])) {
$convoinit = $fb->get('/'.$_GET['convoid'].'/messages?fields=from,message,created_time', $pageAccessToken);
$convo = $convoinit->getDecodedBody();
echo '<div class="convooverflow">';
foreach($convo['data'] as $convofield){
echo '<div class="convoitem">';
$imageinit = $fb->get('/'.$convofield['from']['id'].'/picture?redirect=0', $pageAccessToken);
$image = $imageinit->getDecodedBody();
$fbdate = new DateTime($convofield['created_time']);
echo '<img class="convoimage" src="'.$image['data']['url'].'" title="'.$convofield['from']['name'].'" alt="'.$convofield['from']['name'].'"><div class="convomessage">'.$convofield['message'].'</div>';
echo '<div class="convodate">'.$fbdate->format('F j, Y').' at '.$fbdate->format('g:i A').'</div>';
echo '</div>';
}
echo '</div>';
echo '<form id="convomessage">
<textarea name="convomessageinput" id="convomessageinput"></textarea>
<input type="hidden" name="convoid" value="'.$_GET['convoid'].'">
</form>';
} else {?><div class="messageidle">No message to display</div><?php }?></div></div>
</div>

</body>
</html>
<?php exit; }}?>
