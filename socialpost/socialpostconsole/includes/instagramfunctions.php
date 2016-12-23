<?php
$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
$retrive = new DB_retrival;
if ($retrive->isLoggedIn() == true && $social2['instagram_enabled'] == '1' && $social2['console_enabled'] == '1'){
require($_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/vendor/autoload.php");
$debug = false;
$truncatedDebug = false;
$instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug);
$username = $social2['instagram_username'];
$password = $social2['instagram_password'];
$instagram->setUser($username, $password);
$instagram->login();

if(isset($_GET['commentsmoreid'])){
echo '<html>
<head>
<meta name="robots" content="noindex">
<link href="https://'.$_SERVER['HTTP_HOST'].'/plugins/socialpost/socialpostconsole/styles/instagram.css" rel="stylesheet" type="text/css">
<script src="https://'.$_SERVER['HTTP_HOST'].'/includes/console/scripts/jquery-2.2.3.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body><div id="gramcomments" style="padding:15px;">';
echo '<form id="gramcomment">
<label title="posttitle"><b>Leave a comment:</b></label>
<textarea name="gramcommentbox" id="gramcommentbox"></textarea>
<input type="hidden" name="gramcommentid" value="'.$_GET['commentsmoreid'].'">
<input type="submit" name="commentsubmit" style="display:none;">
</form>';
echo "<script>var data = {};
$(function() {
$('#gramcomment').keypress(function (e) {
        if(e.which == 13) {
	  var myinstances = [];
	  $('input, button, select, textarea').prop('disabled', true);
      $.each($('form input, form select'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
	  }); $.each($('textarea'), function(i, v) {
          if (v.type !== 'submit') {
              data[v.name] = v.value;
          }
      });
	  //end each
     $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/console/social/instagram/comment',
          data: data,
          success: function(resp) {
              if (resp.success === true) {
				  window.location.reload(true);
			  }
          },
          error: function(xhr, status, error) {
			  var err = eval('(' + xhr.responseText + ')');
              console.log(err.Message);
          }
      }); 
      return false;
}});
});
</script>";
$comments = json_decode(json_encode($instagram->getMediaComments($_GET['commentsmoreid'])), true);
//var_dump($comments);
if (empty($comments['comments'])){
echo '<h2>No comments</h2>';	
} else {
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
echo '</div></body>
</html>';
exit;
}

if(isset($_GET['favoriteid'])){
	$instagram->like($_GET['favoriteid']);
	echo 'Favorited';
	exit;
}

if(isset($_GET['disfavoriteid'])){
	$instagram->unlike($_GET['disfavoriteid']);
	echo 'Disfavorited';
	exit;
}
}  else {
 header("HTTP/1.0 403 Forbidden");
 die();
} ?>