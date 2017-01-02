<html>
<head>
<meta name="robots" content="noindex">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/styles/twitter.css" rel="stylesheet" type="text/css">
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/scripts/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/twitter.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body onload="$('.convooverflow').scrollTop($('.convooverflow')[0].scrollHeight);">
<style media="screen and (max-width : 535px)">
.tweetdate {
	    margin-bottom: 15px;
}
</style>
<style media="screen and (max-width : 482px)">
.tweet {
    padding-bottom: 35px;
}

.tweetimage {
    padding: 10px;
}

#twitterbar li {
    margin-right: -15px !important;
}
</style>
<div id="twitterbar">
<ul>
<li><a href="/console/social/twitter#main" alt="Main" title="Main" class="maintab">&#128441;</a></li>
<li><a href="/console/social/twitter#replies" alt="Replies" title="Replies" class="repliestab">@</a></li>
<li><a href="#messages" alt="Messages" title="Messages" class="active messagestab">&#9993;</a></li>
</ul>
</div>
<div id="messages">
<div id="inboxscroll">
<?php
			require $_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
$retrive = new DB_retrival;
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();

		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$dmtimeline = json_decode(json_encode($tconnection->get("direct_messages", ["count" => 200])), true);
		if (!isset($_SESSION['socialpost']['owntwitterscreenname'])){
		$userinfo = json_decode(json_encode($tconnection->get("account/settings", ["count" => 200])), true);
		$_SESSION['socialpost']['owntwitterscreenname'] = $userinfo['screen_name'];
		}
		$count = 0;
		$namecounts = array();
		foreach($dmtimeline as $key => $dmfeed){
		if ($dmtimeline[$key]["sender_screen_name"] === $dmtimeline[$key]["sender_screen_name"]){
		$_SESSION['socialpost']['twitterscreennames'][$count] = $dmfeed["sender_screen_name"];
		array_push($namecounts, $dmtimeline[$key]["sender_screen_name"]);
		$count++;
		}
		}
		$messagecount = array();
		$messagecountn = 0;
		$count = 0;
		$names = array_values(array_unique($_SESSION['socialpost']['twitterscreennames']));
		unset($_SESSION['socialpost']['twitterscreennames']);
		$namecount = array_count_values($namecounts);
		foreach($dmtimeline as $key => $dmfeed){
		if ($dmtimeline[$key]["sender_screen_name"] === $names[$count]){
		$messagecountn++;
		}
		$value = $namecount[$dmtimeline[$key]["sender_screen_name"]];
		if ($messagecountn === $value){
		$messagecount[$count] = $messagecountn;
		$messagecountn = 0;
		$count++;
		}
		}
		foreach($names as $name){
echo '<a href="?convouser='.$name.'"><div class="convothread';if ($_GET['convouser'] == $name) {
echo ' active';
}echo'">';
$userinfo = json_decode(json_encode($tconnection->get("users/show", ["screen_name" => $name])), true);
echo '<img class="convoinboximage" src="'.$userinfo["profile_image_url_https"].'" title="'.$name.'" alt="'.$name.'"><div class="convocount">('.$namecount[$name].')</div>';
echo '</div></a>';
		}
?></div>
<div id="messagedisplay"><div class="messagetable"><?php if (isset($_GET['convouser']) && in_array($_GET['convouser'], $names)) {
$_SESSION['socialpost']['convouser'] = $_GET['convouser'];
$owndmtimeline = json_decode(json_encode($tconnection->get("direct_messages/sent", ["count" => 200])), true);
$mergeddmtimelines = array_merge($dmtimeline, $owndmtimeline);

function cmp($a, $b)
{
    return strcmp($a['id'], $b['id']);
}

usort($mergeddmtimelines, "cmp");

echo '<div class="convooverflow">';
foreach($mergeddmtimelines as $key => $dmfeed){
if($_GET['convouser'] === $mergeddmtimelines[$key]["sender_screen_name"] or $_GET['convouser'] === $mergeddmtimelines[$key]["recipient_screen_name"]){
echo '<div class="convoitem">';
$twitterdate = new DateTime($mergeddmtimelines[$key]['created_at']);
echo '<img class="convoimage" src="'.$mergeddmtimelines[$key]['sender']['profile_image_url_https'].'" title="'.$mergeddmtimelines[$key]["sender_screen_name"].'" alt="'.$mergeddmtimelines[$key]["sender_screen_name"].'"><div class="convomessage">'.$mergeddmtimelines[$key]["text"].'</div>';
echo '<div class="convodate">'.$twitterdate->format('F j, Y').' at '.$twitterdate->format('g:i A').'</div>';
echo '</div>';
}
}
echo '<div class="replybox dmbox"><form id="dm">
<textarea name="dmupdate" maxlength="10000" style="overflow-y:scroll" id="dmupdate"></textarea>
<div id="chars" style="margin-top:10px;"></div>
<input type="submit" name="dmsubmit" style="display:none;">
</form></div>';
echo "<script>var text_max = 10000;
    $('#chars').html(text_max + ' characters remaining');

    $('#dm').keyup(function() {
        var text_length = $('#dmupdate').val().length;
        var text_remaining = text_max - text_length;

        $('#chars').html(text_remaining + ' characters remaining');
    });</script>";
echo '</div>';
} else {?><div class="messageidle">No message to display</div><?php }?></div></div>
</div>
</body>
</html>
<?php exit;?>
