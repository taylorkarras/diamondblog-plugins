<?php
if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class reviewratings extends plugin {

static function link_routes(){
	$GLOBALS['router']->post('/userrate', function(){

$templates = new League\Plates\Engine();
$templates->addFolder('reviewratings', ''.$_SERVER['DOCUMENT_ROOT'].'/plugins/reviewratings');
   return $templates->render('reviewratings::userratingsubmit');
});
}

static function ban_eradicate(){
$global = new DB_global;
$global->sqlquery("DELETE FROM `ddp_reviewratings_user` WHERE `u_ip` = '".$_POST['addthree']."'");
}

static function post_form_bottom(){
	$global = new DB_global;
	$reviewsettings = $global->sqlquery("SELECT * FROM ddp_reviewratings");
	$reviewsettings_init = $reviewsettings->fetch_assoc();
	
	if($reviewsettings_init = '2'){
	$rs = '10';}
	else if ($reviewsettings_init = '1'){
	$rs = '5';
	}
	
	if(!empty($_GET["postid"])){
	$creview = $global->sqlquery("SELECT c_rating FROM ddp_reviewratings_content WHERE c_postid = '".$_GET["postid"]."'");
	$creview_init = $creview->fetch_assoc();
	if (!empty($creview_init['c_rating'])){
	$rating = $creview_init['c_rating'];
	$isreview = '1" checked';
	} else {
	$isreview = '0"';
	}} else {
	$isreview = '0"';
		if($reviewsettings_init = '2'){
	$rating = '5';}
	else if ($reviewsettings_init = '1'){
	$rating = '3';
	}
	}
	
        echo '<br /><br /><label title="review"><b>Review rating:</b></label>
<div class="sitescrolling"><br><input type="checkbox" name="isreview" value="'.$isreview.'> This is a review.</div>';
echo '<div style="width:80%; margin:auto;"><input type="range" style="width:90%;" min="1" max="'.$rs.'" step="0.5" name="userrating" value="'.$rating.'" id="userrating" 
oninput="outputUpdate(value)">
<output for="userrating" id="urvalue">'.$rating.'</output>';
echo "<script>function outputUpdate(vol) {
	document.querySelector('#urvalue').value = vol;
}</script>";
	if(!empty($_GET["postid"])){
echo '<script>$(';echo"'";echo'input[type="checkbox"]';echo"').change(function(){
    this.value = (Number(this.checked));
	});</script>";
	}
echo "</div>";
}

    static function inc_post_form_bottom(){

	if ($_POST['isreview'] == '1'){
		$global = new DB_global;
		$post_id = $global->sqlquery("SELECT content_id FROM dd_content WHERE content_shortlink LIKE '".$GLOBALS['shortlink']."'");
		$post_id_init = $post_id->fetch_assoc();
		$the_post_id = $post_id_init['content_id'];
		$global->sqlquery("INSERT INTO `ddp_reviewratings_content` (`c_rating`, `c_postid`) VALUES ('".$_POST['userrating']."', '".$the_post_id."');");
		}
	}
	
	static function inc_post_form_bottom_edit(){
				$global = new DB_global;
			
	if ($_POST['isreview'] == '1' && !empty($_POST['postidtoedit'])){
		if ($global->sqlquery("UPDATE `ddp_reviewratings_content` SET `c_rating` = '".$_POST['userrating']."' WHERE `ddp_reviewratings_content`.`c_postid` = '".$_POST['postidtoedit']."';")){
		$global->sqlquery("INSERT INTO `ddp_reviewratings_content` (`c_rating`, `c_postid`) VALUES ('".$_POST['userrating']."', '".$_POST['postidtoedit']."');");
		}
	} else if ($_POST['isreview'] == '0' && !empty($_POST['postidtoedit'])){
		$global->sqlquery("DELETE FROM `ddp_reviewratings_content` WHERE `c_postid` = '".$_POST['postidtoedit']."';");
	}
	}

static function amp_style(){
	echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/plugins/reviewratings/reviewstyle.css');
	
	echo '.goodrating {
		color:#00ea00
	}
	
	.meidocrerating {
		color:#ffbf01
	}
	
	.badrating {
		color:#f90909
	}';
}
	
static function amp_post_bottom(){
	$check = new DB_check;
	$global = new DB_global;
	$retrive = new DB_retrival;
	$link = explode("/", $_SERVER['REQUEST_URI']);
	$post_id = $global->sqlquery("SELECT * FROM dd_content WHERE content_permalink LIKE '".$link[1]."'");
	$post_id_init = $post_id->fetch_assoc();
	$the_post_id = $post_id_init['content_id'];
	$reviewsettings = $global->sqlquery("SELECT * FROM ddp_reviewratings");
	$reviewsettings_init = $reviewsettings->fetch_assoc();
	$creview = $global->sqlquery("SELECT c_rating FROM ddp_reviewratings_content WHERE c_postid = '$the_post_id'");
	$creview_init = $creview->fetch_assoc();
	$creviewcheck = $global->sqlquery("SELECT c_postid FROM ddp_reviewratings_content WHERE c_postid = '$the_post_id'");
	$creviewcheck_init = $creviewcheck->fetch_assoc();
	$ureview = $global->sqlquery("SELECT * FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id' AND u_ip = '".$_SERVER['REMOTE_ADDR']."';");
	$ureview_init = $ureview->fetch_assoc();
	$ureviewscore = $global->sqlquery("SELECT AVG(u_rated) FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id'");
	$ureviewscore_init = $ureviewscore->fetch_assoc();
	$ureviewcount1 = $global->sqlquery("SELECT u_rated FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id';");
	$ureviewcount = $ureviewcount1->num_rows;
	
	if($reviewsettings_init['ratings_range'] == '2'){
	$rs = '10';
	$value = '5';}
	else if ($reviewsettings_init['ratings_range'] == '1'){
	$rs = '5';
	$value = '3';
	}

if ($the_post_id !== $creviewcheck_init['c_postid']){
} else {
		echo '<div id="ratingsbox" class="plugintheme">';
        echo '<div class="ratingsboxcontent pluginthemeleft">';
		echo '<h2>Our Rating</h2>';
		echo '<h3';
		if ($reviewsettings_init['ratings_range'] == '1'){
		if ($creview_init['c_rating'] > '4'){
		echo ' class="goodrating"';
		} else if ($creview_init['c_rating'] < '2'){
		echo ' class="badrating"';	
		} else {
		echo ' class="meidocrerating"';
		}
		} else {
		if ($creview_init['c_rating'] > '7'){
		echo ' class="goodrating"';
		} else if ($creview_init['c_rating'] < '4'){
		echo ' class="badrating"';	
		} else {
		echo ' class="meidocrerating"';
		}}
		echo '>'.$creview_init['c_rating'].'</h3>';
		echo '</div>';
		echo '<div class="ratingsboxuser">';
		echo '<h2>User Rating</h2>';
		echo '<h3';
		if ($reviewsettings_init['ratings_range'] == '1'){
		if ($ureviewscore_init['AVG(u_rated)'] == 0){
		} else if ($ureviewscore_init['AVG(u_rated)'] > '4'){
		echo ' class="goodrating"';
		} else if ($ureviewscore_init['AVG(u_rated)'] < '2'){
		echo ' class="badrating"';		
		} else {
		echo ' class="meidocrerating"';
		}} else {
		if ($ureviewscore_init['AVG(u_rated)'] == 0){
		} else if ($ureviewscore_init['AVG(u_rated)'] > '7'){
		echo ' class="goodrating"';
		} else if ($ureviewscore_init['AVG(u_rated)'] < '4'){
		echo ' class="badrating"';	
		} else {
		echo ' class="meidocrerating"';
		}
		}
		echo '>'.round($ureviewscore_init['AVG(u_rated)']* '2')/'2'.'</h3>';
		echo '<small>(based on '.number_format($ureviewcount).' ratings)</small>';
		echo '</div>';
		echo '</div>';	
}
}
	
static function post_contentbottom(){
	$check = new DB_check;
	$global = new DB_global;
	$retrive = new DB_retrival;
	$link = str_replace("/", "", $_SERVER['REQUEST_URI']);
	$post_id = $global->sqlquery("SELECT * FROM dd_content WHERE content_permalink LIKE '$link'");
	$post_id_init = $post_id->fetch_assoc();
	$the_post_id = $post_id_init['content_id'];
	$reviewsettings = $global->sqlquery("SELECT * FROM ddp_reviewratings");
	$reviewsettings_init = $reviewsettings->fetch_assoc();
	$creview = $global->sqlquery("SELECT c_rating FROM ddp_reviewratings_content WHERE c_postid = '$the_post_id'");
	$creview_init = $creview->fetch_assoc();
	$creviewcheck = $global->sqlquery("SELECT c_postid FROM ddp_reviewratings_content WHERE c_postid = '$the_post_id'");
	$creviewcheck_init = $creviewcheck->fetch_assoc();
	$ureview = $global->sqlquery("SELECT * FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id' AND u_ip = '".$_SERVER['REMOTE_ADDR']."';");
	$ureview_init = $ureview->fetch_assoc();
	$ureviewscore = $global->sqlquery("SELECT AVG(u_rated) FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id'");
	$ureviewscore_init = $ureviewscore->fetch_assoc();
	$ureviewcount1 = $global->sqlquery("SELECT u_rated FROM ddp_reviewratings_user WHERE u_postid = '$the_post_id';");
	$ureviewcount = $ureviewcount1->num_rows;
	
	if($reviewsettings_init['ratings_range'] == '2'){
	$rs = '10';
	$value = '5';}
	else if ($reviewsettings_init['ratings_range'] == '1'){
	$rs = '5';
	$value = '3';
	}

if ($the_post_id !== $creviewcheck_init['c_postid']){
} else {
		echo '<style>';
		echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/plugins/reviewratings/reviewstyle.css');
		echo '</style>';
		echo '<div id="ratingsbox" class="plugintheme">';
        echo '<div class="ratingsboxcontent pluginthemeleft">';
		echo '<h2>Our Rating</h2>';
		echo '<h3';
		if ($reviewsettings_init['ratings_range'] == '1'){
		if ($creview_init['c_rating'] > '4'){
		echo ' style="color:#00ea00"';
		} else if ($creview_init['c_rating'] < '2'){
		echo ' style="color:#f90909"';	
		} else {
		echo ' style="color:#ffbf01"';
		}
		} else {
		if ($creview_init['c_rating'] > '7'){
		echo ' style="color:#00ea00"';
		} else if ($creview_init['c_rating'] < '4'){
		echo ' style="color:#f90909"';	
		} else {
		echo ' style="color:#ffbf01"';
		}}
		echo '>'.$creview_init['c_rating'].'</h3>';
		echo '</div>';
		echo '<div class="ratingsboxuser">';
		echo '<h2>User Rating</h2>';
		echo '<h3';
		if ($reviewsettings_init['ratings_range'] == '1'){
		if ($ureviewscore_init['AVG(u_rated)'] == 0){
		} else if ($ureviewscore_init['AVG(u_rated)'] > '4'){
		echo ' style="color:#00ea00"';
		} else if ($ureviewscore_init['AVG(u_rated)'] < '2'){
		echo ' style="color:#f90909"';	
		} else {
		echo ' style="color:#ffbf01"';
		}} else {
		if ($ureviewscore_init['AVG(u_rated)'] == 0){
		} else if ($ureviewscore_init['AVG(u_rated)'] > '7'){
		echo ' style="color:#00ea00"';
		} else if ($ureviewscore_init['AVG(u_rated)'] < '4'){
		echo ' style="color:#f90909"';	
		} else {
		echo ' style="color:#ffbf01"';
		}
		}
		echo '>'.round($ureviewscore_init['AVG(u_rated)']* '2')/'2'.'</h3>';
		echo '<small>(based on '.number_format($ureviewcount).' ratings)</small>';
		echo '</div>';
		echo '</div>';
		if ($reviewsettings_init['userratings_enabled'] == '1'){
				if ($check->ifbanned() or $check->istor() or $check->isLoggedIn()){
		} else {
		echo '<div id="userrate" class="printhide">';
		if ($ureview_init['u_ip'] == $_SERVER['REMOTE_ADDR']){
		echo '<h2 style="text-align:center">You rated this as: '.$ureview_init['u_rated'].'</h3>';
		} else {
		echo '<h2>What would you rate this as?</h2>';
		echo '<form method="post" id="userrate">
		<input type="range" min="1" max="'.$rs.'" step="0.5" name="userrating" value="'.$value.'" id="userrating" 
oninput="outputUpdate(value)">
<output for="userrating" id="urvalue">'.$value.'</output>';
echo "<script>function outputUpdate(vol) {
	document.querySelector('#urvalue').value = vol;
}</script>";
echo '<input name="voteip" type="hidden" value="'; echo $_SERVER['REMOTE_ADDR']; echo '">';
echo '<br /><br /><input style="margin:auto" class="ratingsubmit" name="ratingsubmit" type="submit" value="Rate"></form>';
		echo '</div></div>';
		}
		}
$titleinit = $global->sqlquery("SELECT site_name FROM dd_settings LIMIT 1;");
$title = $titleinit->fetch_assoc();

echo '<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Review",
  "itemReviewed": {
    "@type": "Thing"
  },
  "author": {
    "@type": "Person",
    "name": "'.$retrive->realname($post_id_init['content_author']).'"
  },
  "reviewRating": {
    "@type": "Rating",
    "ratingValue": "'.$creview_init['c_rating'].'",
    "bestRating": "'.$rs.'",
    "worstRating": "1"
  },
  "publisher": {
    "@type": "Organization",
    "name": "'.$title['site_name'].'"
  }
}
</script>';
		}
		return true;
}
    }
}
