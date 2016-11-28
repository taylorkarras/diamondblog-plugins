<?php

$global = new DB_global;
$ratingscale = $global->sqlquery("SELECT * FROM `ddp_reviewratings`");
$ratingscale_init = $ratingscale->fetch_assoc();
$ratingaverage1 = $global->sqlquery("SELECT AVG(c_rating) FROM `ddp_reviewratings_content`");
$ratingaverage1_init = $ratingaverage1->fetch_assoc();
$ratingaverage2 = $global->sqlquery("SELECT AVG(u_rated) FROM `ddp_reviewratings_user`");
$ratingaverage2_init = $ratingaverage2->fetch_assoc();

if(isset($_POST)){
			$global->sqlquery("UPDATE `ddp_reviewratings` SET `userratings_enabled` = '".$_POST['userratingsenabled']."', ratings_range = '".$_POST['ratingscale']."'");
			
			if($ratingscale_init['ratings_range'] == '1' && $ratingaverage1_init['AVG(c_rating)'] > 5){
			$global->sqlquery("UPDATE `ddp_reviewratings_content` SET c_rating =  ROUND(c_rating / 2 * 2, 0) / 2;");
			$global->sqlquery("UPDATE `ddp_reviewratings_user` SET u_rated =  ROUND(u_rated / 2 * 2, 0) / 2;");
			} else if($ratingscale_init['ratings_range'] == '2' && $ratingaverage1_init['AVG(c_rating)'] < 5){
			$global->sqlquery("UPDATE `ddp_reviewratings_user` SET u_rated =  ROUND(u_rated * 2 * 2, 0) / 2;");
			$global->sqlquery("UPDATE `ddp_reviewratings_content` SET c_rating =  ROUND(c_rating * 2 * 2, 0) / 2;");	
			}
				
			
			        		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			
				$resp = array();
				$resp['resp'] = true;
				$resp['message'] = '
	<p>Rating settings updated.</p>';
			
                echo json_encode($resp);
		        exit;
	}
}