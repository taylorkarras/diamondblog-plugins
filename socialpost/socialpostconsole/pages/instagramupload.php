<?php
$retrive = new DB_retrival;
$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
if ($retrive->isLoggedIn() == false){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
} else {
if ($retrive->restrictpermissionlevel('2') or $social2['instagram_enabled'] == '0' or $social2['console_enabled'] == '0'){} else {

?>
<html>
<head>
<meta name="robots" content="noindex">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/styles/instagram.css" rel="stylesheet" type="text/css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/featherlight.min.css" rel="stylesheet" type="text/css">
<script src="https://<?php echo $_SERVER['HTTP_HOST']?>/includes/console/scripts/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/featherlight.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
<style media="screen and (max-width : 482px)">
#instagramupload {
width: 165px !important;
}

#instagramupload input{
font-size: initial !important;
}

#instagramupload label{
	    font-size: 25px !important;
}

#grampreview {
	    max-height: 150px;
    max-width: 150px;
}

#grambar li {
	    margin-right: -25px !important;
}

#gramupload {
	    width: 235px;
}
</style>
<div class="overflow">
<form id="instagramupload">
<input type="hidden" id="gramerror">
<img src="" id="grampreview">
<label title="gramitem">Photo or Video</label>
<br><br><input type="file" id="gramupload" name="gramitem">
<br><br><label title="gramcaption">Caption</label>
<br><br><textarea id="gramcaption" name="gramcaption"></textarea>
<br><br>
  <input type="reset"><input type="submit" name="gramsubmit">
</form>
<script type="text/javascript" src="https://<?php echo $_SERVER['HTTP_HOST']?>/plugins/socialpost/socialpostconsole/scripts/instagramupload.js"></script>
</div>
<div id="grambar">
<ul class="tabs">
<li><a href="/console/social/instagram#main" alt="Main" title="Main" class="maintab">&#128441;</a></li>
<li><a href="/console/social/instagram#recent" alt="Recent Activity" title="Recent Activity" class="recenttab">&#128712;</a></li>
<li><a href="#" alt="Upload Photos and Videos" title="Upload Photos and Videos" class="active uploadtab">&#129129;</a></li>
</ul>
</div>
</body>
</html>
<?php exit; }}?>