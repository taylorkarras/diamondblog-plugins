<?php 
echo consolemenu();
echo '<div id="page"><div class="center">ReCaptcha Settings</div>
<div id="settingslist">';
$global = new DB_global;
$datainit = $global->sqlquery("SELECT * from ddp_recaptcha");
$data = $datainit->fetch_assoc();
echo '<form id="pluginsettings" method="post">
<br /><label name="sitekey">ReCaptcha SiteKey</label>
<br /><input type="text" name="sitekey" value="'.$data['recaptcha_sitekey'].'" />
<br /><label name="secret">ReCaptcha Secret</label>
<br /><input name="secret" type="text" value="'.$data['recaptcha_secret'].'" />
<br /><br /><input class="postsubmit" name="recaptchasubmit" type="submit" value="Submit">
</form>	
</div></div>';
?>