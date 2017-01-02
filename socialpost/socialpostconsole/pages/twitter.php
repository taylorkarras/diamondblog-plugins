<?php
			require $_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
$retrive = new DB_retrival;
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
if ($retrive->isLoggedIn() == false){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
} else {
if ($retrive->restrictpermissionlevel('2') or $social2['twitter_enabled'] == '0' or $social2['console_enabled'] == '0'){} else {

?>
<html>
<head>
<meta name="robots" content="noindex">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/styles/twitter.css" rel="stylesheet" type="text/css">
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/scripts/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/twitter.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
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
<ul class="tabs">
<li><a href="#main" alt="Main" title="Main" class="maintab">&#128441;</a></li>
<li><a href="#replies" alt="Replies" title="Replies" class="repliestab">@</a></li>
<li><a href="/console/social/twitter/dms" alt="Direct Messages" title="Direct Messages" class="messagestab">&#9993;</a></li>
<script>
$(".messagestab").click(function( event ) {
  event.preventDefault();
  var url = 'https://' + location.host + '/console/social/twitter/dms';
  $(location).attr('href',url);
});
</script>
</ul>
</div>
<div id="main"><div class="overflow">
<?php
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$hometimeline = json_decode(json_encode($tconnection->get("statuses/home_timeline", ["count" => 100])), true);
		foreach ($hometimeline as $twitterfeed) {
		echo '<div class="tweet" tweetid="'.$twitterfeed['id'].'">';
		echo '<div class="tweetimage"><img src="'.$twitterfeed['user']['profile_image_url'].'" alt='.$twitterfeed['user']['name'].' title='.$twitterfeed['user']['name'].'></div>';
		echo '<div class="tweetscreenname">@'.$twitterfeed['user']['screen_name'].'</div><div class="tweetrealname">('.$twitterfeed['user']['name'].')</div>';
		if ($twitterfeed['user']['verified'] == 'true') {
		echo '<div class="tweetverified" alt="Verified" title="Verified">&#10003;</div>';
		}
		preg_match('/http*\S+/', $twitterfeed['text'], $htmllinks);
		echo '<p>'.preg_replace('/http*\S+/', '<a target="_blank" href="'.$htmllinks['0'].'">'.$htmllinks[0].'</a>', $twitterfeed['text']).'</p>';
		$twitterdate = new DateTime($twitterfeed['created_at']);
		echo '<div class="tweetdate">'.$twitterdate->format('F j, Y').' at '.$twitterdate->format('g:i A').'</div>';
		echo '<div class="tweetlinks">';
		if ($twitterfeed['retweeted'] == true){
		echo '<a href="javascript:void(0)" onclick="disretweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" style="color: #29a533; font-weight: bold;" class="tweetretweet">Retweeted ('.$twitterfeed['retweet_count'].')</a> | ';
		} else {
		echo '<a href="javascript:void(0)" onclick="retweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" class="tweetretweet">Retweet ('.$twitterfeed['retweet_count'].')</a> | ';
		} echo '<a href="javascript:void(0)" onclick="$(&#39;#statusupdate&#39;).val(&#39;@'.$twitterfeed['user']['screen_name'].'&#39;);$(&#39;#replyid&#39;).val(&#39;'.$twitterfeed['id'].'&#39;);var text_length = $(&#39;#statusupdate&#39;).val().length;var text_remaining = text_max - text_length;$(&#39;#chars&#39;).html(text_remaining + &#39; characters remaining&#39;);" title="Reply to @'.$twitterfeed['user']['screen_name'].'" alt="Reply to @'.$twitterfeed['user']['screen_name'].'">Reply</a> | ';
		if ($twitterfeed['favorited'] == true){
		echo '<a href="javascript:void(0)" onclick="disfavoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" style="color: #e0001d; font-weight: bold;" class="tweetfavorite">Favorited</a></div>';
		} else {
		echo '<a href="javascript:void(0)" onclick="favoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" class="tweetfavorite">Favorite</a></div>';
		}
		echo '</div>';
		}
?>
</div>
<div class="replybox"><form id="status">
<textarea name="statusupdate" maxlength="140" id="statusupdate"></textarea>
<div id="chars" style="margin-top:10px;"></div>
<input type="hidden" name="replyid" id="replyid">
<input type="submit" name="updatesubmit" style="display:none;">
</form>
</div>
<script>var text_max = 140;
    $('#chars').html(text_max + ' characters remaining');

    $('#statusupdate').keyup(function() {
        var text_length = $('#statusupdate').val().length;
        var text_remaining = text_max - text_length;

        $('#chars').html(text_remaining + ' characters remaining');
    });
	setInterval(function(){
	var id = $("#main .tweet").attr("tweetid");
    $.get('/console/social/twitter/update?type=main&lasttweetid=' + id, function(data) {
	var count = $(data).filter('.tweet').length;
	if (count > 0) {
		if($(".mainnotif")[0]) {
		var currentvalue = parseInt($(".mainnotif").text());
		$(".mainnotif").text(currentvalue += count);
		} else {
		$(".maintab").append('<a href="#main" alt="You have new notifications." title="You have new notifications." class="mainnotif notif">' + count +'</a>');
		}
	}
	$(data).prependTo("#main .overflow") });
}, 90000);

$( ".maintab" ).click(function() {
  $( ".mainnotif" ).remove();
});
</script></div>
<div id="replies" style="display:none;"><div class="overflow"><?php
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$hometimeline = json_decode(json_encode($tconnection->get("statuses/mentions_timeline", ["count" => 50])), true);
		foreach ($hometimeline as $twitterfeed) {
		echo '<div class="tweet" tweetid="'.$twitterfeed['id'].'">';
		echo '<div class="tweetimage"><img src="'.$twitterfeed['user']['profile_image_url'].'" alt='.$twitterfeed['user']['name'].' title='.$twitterfeed['user']['name'].'></div>';
		echo '<div class="tweetscreenname">@'.$twitterfeed['user']['screen_name'].'</div><div class="tweetrealname">('.$twitterfeed['user']['name'].')</div>';
		if ($twitterfeed['user']['verified'] == 'true') {
		echo '<div class="tweetverified" alt="Verified" title="Verified">&#10003;</div>';
		}
		echo '<p>'.$twitterfeed['text'].'</p>';
		$twitterdate = new DateTime($twitterfeed['created_at']);
		echo '<div class="tweetdate">'.$twitterdate->format('F j, Y').' at '.$twitterdate->format('g:i A').'</div>';
				echo '<div class="tweetlinks">';
		if ($twitterfeed['retweeted'] == true){
		echo '<a href="javascript:void(0)" onclick="disretweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" style="color: #29a533; font-weight: bold;" class="tweetretweet">Retweeted ('.$twitterfeed['retweet_count'].')</a> | ';
		} else {
		echo '<a href="javascript:void(0)" onclick="retweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" class="tweetretweet">Retweet ('.$twitterfeed['retweet_count'].')</a> | ';
		} echo '<a href="javascript:void(0)" onclick="$(&#39;#statusupdate2&#39;).val(&#39;@'.$twitterfeed['user']['screen_name'].'&#39;);$(&#39;#replyid2&#39;).val(&#39;'.$twitterfeed['id'].'&#39;);var text_length = $(&#39;#statusupdate&#39;).val().length;var text_remaining = text_max - text_length;$(&#39;#chars&#39;).html(text_remaining + &#39; characters remaining&#39;);" title="Reply to @'.$twitterfeed['user']['screen_name'].'" alt="Reply to @'.$twitterfeed['user']['screen_name'].'">Reply</a> | ';
		if ($twitterfeed['favorited'] == true){
		echo '<a href="javascript:void(0)" onclick="disfavoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" style="color: #e0001d; font-weight: bold;" class="tweetfavorite">Favorited</a></div>';
		} else {
		echo '<a href="javascript:void(0)" onclick="favoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" class="tweetfavorite">Favorite</a></div>';
		}
		echo '</div>';
		}
?></div>
<div class="replybox"><form id="status2">
<textarea name="statusupdate2" maxlength="140" id="statusupdate2"></textarea>
<div id="chars2" style="margin-top:10px;"></div>
<input type="hidden" name="replyid2" id="replyid2">
<input type="submit" name="updatesubmit" style="display:none;">
</form>
<script>var text_max = 140;
    $('#chars2').html(text_max + ' characters remaining');

    $('#statusupdate2').keyup(function() {
        var text_length = $('#statusupdate2').val().length;
        var text_remaining = text_max - text_length;

        $('#chars2').html(text_remaining + ' characters remaining');
    });
	
		setInterval(function(){
	var id = $("#replies .tweet").attr("tweetid");
    $.get('/console/social/twitter/update?type=replies&lasttweetid=' + id, function(data) {
	var count = $(data).filter('.tweet').length;
	if (count > 0) {
		if($(".repliesnotif")[0]) {
		var currentvalue = parseInt($(".repliesnotif").text());
		$(".repliesnotif").text(currentvalue += count);
		} else {
		$(".repliestab").append('<a href="#replies" alt="You have new replies." title="You have new replies." class="repliesnotif notif">' + count +'</a>');
		}
	}
	$(data).prependTo("#replies .overflow") });
}, 210000);

$( ".repliestab" ).click(function() {
  $( ".repliesnotif" ).remove();
});
</script></div></div>
</body>
</html>
<?php exit; }}?>
