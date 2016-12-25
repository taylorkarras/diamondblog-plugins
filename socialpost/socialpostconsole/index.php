<?php
$global = new DB_global;
$retrive = new DB_retrival;
		$ss1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$ss2 = $ss1->fetch_assoc();
if ($retrive->isLoggedIn() == true){
if ($retrive->restrictpermissionlevel('2') or $ss2['console_enabled'] == '0'){

echo consolemenu();
echo '<div id="page"><div class="center">You are not authorized to view this section!</div></div>';

} else {
echo consolemenu();
echo '<div id="page">';
if ($ss2['twitter_enabled'] == '1'){
echo '<div class="center"><h2>Twitter</h2>
<iframe src="/console/social/twitter" style="width: 100%; height: 550px; border: 1px solid black;"></iframe></div>';
}
if ($ss2['instagram_enabled'] == '1'){
echo '<div class="center"><h2>Instagram</h2>
<iframe src="/console/social/instagram" style="width: 100%; height: 550px; border: 1px solid black;"></iframe></div>';
}
if ($ss2['facebook_enabled'] == '1'){
echo '<div class="center"><h2>Facebook</h2>
<iframe src="/console/social/facebook" style="width: 100%; height: 550px; border: 1px solid black;"></iframe></div></div>';
}
if ($ss2['twitter_enabled'] == '0' && $ss2['facebook_enabled'] == '0' && $ss2['instagram_enabled'] == '0'){
echo '<div class="center">Please enable a social media service and have it properly configured from the settings page.</div>';
}
echo '</div>';
}} else {
	header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
}
?>
