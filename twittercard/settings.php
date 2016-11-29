<?php 
echo consolemenu();
echo '<div id="page"><div class="center">Twitter Card Settings</div>
<div id="settingslist">';
$global = new DB_global;
$twittercardinit = $global->sqlquery("SELECT * from ddp_twittercard");
$twittercard = $twittercardinit->fetch_assoc();
echo '<form id="pluginsettings" method="post"><br /><br /><label name="cardtype"><b>Twitter Card type</b></label>
<div class="sitescrolling">
<input type="radio" name="cardtype"  ';
  if ($twittercard['twitter_card'] == '0'){
	echo 'value="1" checked';
} else {
	echo 'value="0"';
}
echo'> Summary<br>
  <input type="radio" name="cardtype" ';
  if ($twittercard['twitter_card'] == '1'){
	echo 'value="1" checked';
} else {
	echo 'value="0"';
}
echo'> Summary with Large Image<br></div>';
echo '<br /><label name="twitterusername">Twitter Username</label>
<br /><input name="twitterusername" type="text" value="'.$twittercard['twitter_username'].'" />';
	echo '<script>';echo"
    $('input[type=radio][name^=cardtype]').change(function() {
        if (this.value == '1') {
			$('input[type=radio][name^=cardtype]').removeAttr('checked');
			$('input[type=radio][name^=cardtype]').val('1');
			this.value = '0';
			this.setAttribute('checked', '');
			$(this).prop('checked', true);
        }
        else if (this.value == '0') {
			$('input[type=radio][name^=cardtype]').removeAttr('checked');
			$('input[type=radio][name^=cardtype]').val('0');
			this.value = '1';
			this.setAttribute('checked', '');
			$(this).prop('checked', true);
        }
    });</script>";
echo '<br /><br /><input class="postsubmit" name="twittercardsubmit" type="submit" value="Submit">
</form>	
</div></div>';
?>
	