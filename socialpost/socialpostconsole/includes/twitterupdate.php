<?php
$retrive = new DB_retrival;
			require $_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
				$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
if ($retrive->isLoggedIn() == true && $social2['twitter_enabled'] == '1' && $social2['console_enabled'] == '1'){
if (isset($_GET['lasttweetid'])){

	if ($_GET['type'] == 'main') {
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$hometimeline = json_decode(json_encode($tconnection->get("statuses/home_timeline", ["count" => 25, "since_id" => $_GET['lasttweetid'], "exclude_replies" => true])), true);
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
		echo '<a href="#" onclick="disretweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" style="color: #29a533; font-weight: bold;" class="tweetretweet">Retweeted ('.$twitterfeed['retweet_count'].')</a> | ';
		} else {
		echo '<a href="#" onclick="retweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" class="tweetretweet">Retweet ('.$twitterfeed['retweet_count'].')</a> | ';
		} echo '<a href="#" onclick="$(&#39;#statusupdate2&#39;).val(&#39;@'.$twitterfeed['user']['screen_name'].'&#39;);$(&#39;#replyid2&#39;).val(&#39;'.$twitterfeed['id'].'&#39;);" title="Reply to @'.$twitterfeed['user']['screen_name'].'" alt="Reply to @'.$twitterfeed['user']['screen_name'].'">Reply</a> | ';
		if ($twitterfeed['favorited'] == true){
		echo '<a href="#" onclick="disfavoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" style="color: #e0001d; font-weight: bold;" class="tweetfavorite">Favorited</a></div>';
		} else {
		echo '<a href="#" onclick="favoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" class="tweetfavorite">Favorite</a></div>';
		}
		echo '</div>';
		}
		exit;
	}
	
		if ($_GET['type'] == 'replies') {
		$tconnection = new TwitterOAuth($social2['twitter_apikey'], $social2['twitter_apisecret'], $social2['twitter_accesstoken'], $social2['twitter_accesstokensecret']);
		$hometimeline = json_decode(json_encode($tconnection->get("statuses/mentions_timeline", ["count" => 30, "since_id" => $_GET['lasttweetid']])), true);
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
		echo '<a href="#" onclick="disretweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" style="color: #29a533; font-weight: bold;" class="tweetretweet">Retweeted ('.$twitterfeed['retweet_count'].')</a> | ';
		} else {
		echo '<a href="#" onclick="retweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Retweet" title="Retweet" class="tweetretweet">Retweet ('.$twitterfeed['retweet_count'].')</a> | ';
		} echo '<a href="#" onclick="$(&#39;#statusupdate2&#39;).val(&#39;@'.$twitterfeed['user']['screen_name'].'&#39;);$(&#39;#replyid2&#39;).val(&#39;'.$twitterfeed['id'].'&#39;);" title="Reply to @'.$twitterfeed['user']['screen_name'].'" alt="Reply to @'.$twitterfeed['user']['screen_name'].'">Reply</a> | ';
		if ($twitterfeed['favorited'] == true){
		echo '<a href="#" onclick="disfavoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" style="color: #e0001d; font-weight: bold;" class="tweetfavorite">Favorited</a></div>';
		} else {
		echo '<a href="#" onclick="favoritetweet'; echo"('".$twitterfeed['id']."')"; echo'" alt="Favorite" title="Favorite" class="tweetfavorite">Favorite</a></div>';
		}
		echo '</div>';
		}
		exit;
	}
}
}  else {
 header("HTTP/1.0 403 Forbidden");
 die();
}
?>
