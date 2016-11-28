<?php 
echo consolemenu();
echo '<div id="page"><div class="center">Review Ratings Notification Settings</div>
<div id="settingslist">';
$global = new DB_global;
$reviewinit = $global->sqlquery("SELECT * from ddp_reviewratings");
$review = $reviewinit->fetch_assoc();
echo '<form id="pluginsettings" method="post"><br /><br /><label name="ratingscale"><b>Rating scale</b></label>
<div class="sitescrolling">
<input type="radio" name="ratingscale"  ';
  if ($review['ratings_range'] == '1'){
	echo 'value="2" checked';
} else {
	echo 'value="1"';
}
echo'> 5 point rating scale<br>
  <input type="radio" name="ratingscale" ';
  if ($review['ratings_range'] == '2'){
	echo 'value="2" checked';
} else {
	echo 'value="1"';
}
echo'> 10 point rating scale<br>';
echo '<br><input type="checkbox" name="userratingsenabled" ';  if ($review['userratings_enabled'] == '1'){
	echo 'value="1" checked';
} else {
	echo 'value="0"';
}echo'> User Review Ratings Enabled<br></div>';
	echo '<script>$(';echo"'";echo'input[type="checkbox"]';echo"').change(function(){
    this.value = (Number(this.checked));
	});
	
    $('input[type=radio][name^=ratingscale]').change(function() {
        if (this.value == '2') {
			$('input[type=radio][name^=ratingscale]').removeAttr('checked');
			$('input[type=radio][name^=ratingscale]').val('2');
			this.value = '1';
			this.setAttribute('checked', '');
			$(this).prop('checked', true);
        }
        else if (this.value == '1') {
			$('input[type=radio][name^=ratingscale]').removeAttr('checked');
			$('input[type=radio][name^=ratingscale]').val('1');
			this.value = '2';
			this.setAttribute('checked', '');
			$(this).prop('checked', true);
        }
    });</script>";
echo '<br /><br /><input class="postsubmit" name="reviewratingssubmit" type="submit" value="Submit">
</form>	
</div></div>';
?>
	