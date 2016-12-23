<?php
$retrive = new DB_retrival;
		$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();

if ($retrive->isLoggedIn() == true && $social2['instagram_enabled'] == '1' && $social2['console_enabled'] == '1'){
	require($_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/vendor/autoload.php");
$debug = false;
$truncatedDebug = false;
$instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug);
$username = $social2['instagram_username'];
$password = $social2['instagram_password'];
$instagram->setUser($username, $password);
$instagram->login();
if (isset($_GET['nextmaxid'])){

	if ($_GET['type'] == 'main') {
		
		$timeline = json_decode(json_encode($instagram->timelineFeed($_GET['nextmaxid'])), true);

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
	echo '<script>
	$("[gramid=';echo "'".$timelineitem2['caption']['media_id']."'"; echo'] .gramitem").dblclick(function() {
  alert( "Handler for .dblclick() called." );
});</script>';
		 }
}

echo "<div id='gramreplace'><script>

	var scrolleddown = false;
	var id = '".$timeline['next_max_id']."'

$('#main .overflow').on('scroll', function() {
	
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
			if (scrolleddown == false){
			$('#gramreplace').append('<div class=gramloading>Loading...</div>');
			scrolleddown = true;
		$.get('/console/social/instagram/update?type=main&nextmaxid=' + id, function(data) {
	$('#gramreplace').replaceWith(data) });
			}
        }
    })</script></div>";
exit;
	}
	
	if ($_GET['type'] == 'comments') {
		
		$comments = json_decode(json_encode($instagram->getMediaComments($_GET['commentsmoreid'], $_GET['nextmaxid'])), true);
//var_dump($comments);

 foreach ($comments['comments'] as $commentsitem){
			echo '<div class="gramcomment2"><img class="gramavatar" src="'.$commentsitem['user']['profile_pic_url'].'"><strong>'.$commentsitem['user']['username'].'</strong> '.$commentsitem['text'].'</div>';
 }
 if(!empty($comments['next_max_id'])){
echo "<div id='gramreplace'><a href='#' onclick='loadcomments()' alt='Load More Comments' title='Load More Comments'>Load More Comments</a><script>

	var id = '".$comments['next_max_id']."';
	
function loadcomments() {
	$('#gramreplace').append('<div class=gramloading>Loading...</div>');
		$.get('/console/social/instagram/update?type=comments&commentsmoreid=".$_GET['commentsmoreid']."&nextmaxid=' + id, function(data) {
	$('#gramreplace').replaceWith(data) });
			}</script></div>";
 }
 }
		
}

}
?>
