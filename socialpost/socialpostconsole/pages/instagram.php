<?php require($_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/vendor/autoload.php");
$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
$debug = false;
$truncatedDebug = false;
$instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug);
$username = $social2['instagram_username'];
$password = $social2['instagram_password'];
$retrive = new DB_retrival;
if ($retrive->isLoggedIn() == false){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
} else {
if ($retrive->restrictpermissionlevel('2') or $social2['instagram_enabled'] == '0' or $social2['console_enabled'] == '0'){} else {

$instagram->setUser($username, $password);
$instagram->login();
?>
<html>
<head>
<meta name="robots" content="noindex">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/styles/instagram.css" rel="stylesheet" type="text/css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/featherlight.min.css" rel="stylesheet" type="text/css">
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/includes/console/scripts/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/instagram.js"></script>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/featherlight.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
<style media="screen and (max-width : 482px)">
.gram {
	width: 175px !important;
    padding-bottom:45px;
}

.gramitem{
    max-width: 180px !important;
    max-height: 180px !important;
}

.recentmargin {
	width: 240px !important;
}

#grambar li {
	    margin-right: -25px !important;
}
</style>
<div id="main"><div class="overflow">
<?php $timeline = json_decode(json_encode($instagram->timelineFeed()), true);
//var_dump ($timeline);

 foreach ($timeline['feed_items'] as $key => $timelineitem){
	     foreach ($timelineitem as $timelineitem2) {
	echo '<div class="gram" gramid="'.$timelineitem2['caption']['media_id'].'">';
	echo '<div class="graminfo"><img class="gramavatar" src="'.$timelineitem2['user']['profile_pic_url'].'"><strong>'.$timelineitem2['user']['username'].'</strong>';
	if ($timelineitem2['user']["is_verified"] == true){
	echo '<div class="gramverified alt="Verified" title="Verified">&#10003;</div>';
	}
	echo '</div>';
	if ($timelineitem2['media_type'] == '1'){
	echo '<div style="text-align:center"><img class="gramitem" src="'.$timelineitem2['image_versions2']['candidates'][0]['url'].'"></div>';
	} else if ($timelineitem2['media_type'] == '2'){
	echo '<video class="gramitem" controls>
		<source src="'.$timelineitem2['video_versions'][0]['url'].'" type="video/mp4">
	</video>';
	}
	echo '<div class="gramlikecount">'.$timelineitem2['like_count'].' likes</div>';
	echo '<div class="gramcomment">'.$timelineitem2['caption']['text'].'</div>';
			foreach ($timelineitem2['preview_comments'] as $timelinecomments){
			echo '<div class="gramcomment2"><strong>'.$timelinecomments['user']['username'].'</strong> '.$timelinecomments['text'].'</div>';
		}
		echo '<a class="morecomments" href="https://'.$_SERVER['HTTP_HOST'].'/console/social/instagram/function?commentsmoreid='.$timelineitem2['caption']['media_id'].'" data-featherlight="iframe">View Comments</a>';
	echo '<div class="gramoptions">';
	if ($timelineitem2["has_liked"] == false){
	echo '<a href="#" class="gramfavorite" onclick="favorite('; echo "'".$timelineitem2['caption']['media_id']."'"; echo ')" title="Like" alt="Like">Like</a>';
	}
	else if ($timelineitem2["has_liked"] == true){
	echo '<a href="#" class="gramfavorite" onclick="defavorite('; echo "'".$timelineitem2['caption']['mediaid']."'"; echo ')" style="color: #e0001d; font-weight: bold;" title="Like" alt="Like">Liked</a>';
	}
	echo '<div class="gramdate">'.date("F j, Y \a\\t\ g:i A", $timelineitem2["taken_at"]).'</div>';
	echo '</div>';
	echo '</div>';
	echo '<script>$("[gramid=';echo "'".$timelineitem2['caption']['media_id']."'"; echo'] .gramitem").dblclick(function() {';
	if ($timelineitem2["has_liked"] == false){
		echo 'favorite('; echo "'".$timelineitem2['caption']['media_id']."'"; echo ');';
	} else {
		echo 'defavorite('; echo "'".$timelineitem2['caption']['media_id']."'"; echo ');';
	}
echo '});</script>';
		 }
}

echo "<div id='gramreplace'><script>

	var scrolleddown = false;
	var id = '".$timeline['next_max_id']."';

$('#main .overflow').on('scroll', function() {

        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
			if (scrolleddown == false){
	$('#gramreplace').append('<div class=gramloading>Loading...</div>');
			scrolleddown = true;
		$.get('/console/social/instagram/update?type=main&nextmaxid=' + id, function(data) {
	$('#gramreplace').replaceWith(data) });
			}
        }
    })</script></div>";?>

</div></div>
<div id="recent"><div class="overflow">
<?php $recent = json_decode(json_encode($instagram->getRecentActivity()), true);

//var_dump($recent);
echo '<div class="recentmargin">';

 foreach ($recent['new_stories'] as $key2 => $recentitem){
	 if(!empty($recent['new_stories'][$key2]['args']['media'][0]['image'])){
		echo '<div class="recentimage"><a href="'.$recent['new_stories'][$key2]['args']['media'][0]['image'].'" data-featherlight="image"><img src="'.$recent['new_stories'][$key2]['args']['media'][0]['image'].'"></a></div>';
	 }
	     echo '<div class="recentitem"><img class="gramavatar" src="'.$recent['new_stories'][$key2]['args']['profile_image'].'"><strong>'.$recent['new_stories'][$key2]['args']['text'].'</strong></div>';
 }
 
 foreach ($recent['old_stories'] as $key2 => $recentitem){
	 if(!empty($recent['old_stories'][$key2]['args']['media'][0]['image'])){
		echo '<div class="recentimage"><a href="'.$recent['old_stories'][$key2]['args']['media'][0]['image'].'" data-featherlight="image"><img src="'.$recent['old_stories'][$key2]['args']['media'][0]['image'].'"></a></div>';
	 }
	     echo '<div class="recentitem"><img class="gramavatar" src="'.$recent['old_stories'][$key2]['args']['profile_image'].'"><strong>'.$recent['old_stories'][$key2]['args']['text'].'</strong></div>';
 }
echo '</div>';
?>
</div></div>
<div id="grambar">
<ul class="tabs">
<li><a href="#main" alt="Main" title="Main" class="maintab">&#128441;</a></li>
<li><a href="#recent" alt="Recent Activity" title="Recent Activity" class="recenttab">&#128712;</a></li>
<li><a href="/console/social/instagram/upload" alt="Upload Photos and Videos" title="Upload Photos and Videos" class="uploadtab">&#129129;</a></li>
<script>
$(".uploadtab").click(function( event ) {
  event.preventDefault();
  var url = 'https://' + location.host + '/console/social/instagram/upload';
  $(location).attr('href',url);
});
</script>
</ul>
</div>
</body>
</html>
<?php exit; }}?>