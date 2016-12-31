<?php
$retrive = new DB_retrival;
$global = new DB_global;
		$social1 = $global->sqlquery("SELECT * FROM ddp_socialpost");
		$social2 = $social1->fetch_assoc();
		try{
if ($retrive->isLoggedIn() == true && $social2['instagram_enabled'] == '1' && $social2['console_enabled'] == '1'){
    if (isset($_POST))
{
	$caption = $_POST['gramcaption'];
	$fileext = pathinfo($_FILES['gramitem']['name'], PATHINFO_EXTENSION);
	if ($fileext !== 'jpg' xor $fileext !== 'jpeg' xor $fileext !== 'gif' xor $fileext !== 'png' xor $fileext !== 'mp4'){
	echo 'File must be an Image with a JPG/JPEG/GIF/PNG extension or a video with an MP4 extension';
	exit;
	}
	
	require($_SERVER['DOCUMENT_ROOT']."/plugins/socialpost/vendor/autoload.php");
$debug = false;
$truncatedDebug = false;
$instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug);
$username = $social2['instagram_username'];
$password = $social2['instagram_password'];
$instagram->setUser($username, $password);
$instagram->login();
	
	if ($fileext == 'jpg' xor $fileext == 'jpeg') {
	if (!getimagesize($_FILES['gramitem']["tmp_name"])){
	echo 'File is not an image!';
	exit;
	}
	list($originalwidth, $originalheight) = getimagesize($_FILES['gramitem']["tmp_name"]);
	if($originalwidth > 1080){
	$ratio = $originalwidth / '1080';
	$new_width = round($originalwidth / $ratio);
	$new_height = round($originalheight / $ratio);
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromjpeg($_FILES['gramitem']["tmp_name"]);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $originalwidth, $originalheight);
	imagejpeg($image_p, $_FILES['gramitem']["tmp_name"], 100);
	imagedestroy($image_p);
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;
	} else {
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;	
	}
	} else if ($fileext == 'png') {
	if (!getimagesize($_FILES['gramitem']["tmp_name"])){
	echo 'File is not an image!';
	exit;
	}
	list($originalwidth, $originalheight) = getimagesize($_FILES['gramitem']["tmp_name"]);
	if($originalwidth > 1080){
	$ratio = $originalwidth / '1080';
	$new_width = round($originalwidth / $ratio);
	$new_height = round($originalheight / $ratio);
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefrompng($_FILES['gramitem']["tmp_name"]);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $originalwidth, $originalheight);
	imagejpeg($image_p, $_FILES['gramitem']["tmp_name"], 100);
	imagedestroy($image_p);
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;
	} else {
	$image = imagecreatefrompng($_FILES['gramitem']["tmp_name"]);
	imagejpeg($image, $_FILES['gramitem']["tmp_name"], 100);
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;	
	}
	} else if ($fileext == 'gif') {
	if (!getimagesize($_FILES['gramitem']["tmp_name"])){
	echo 'File is not an image!';
	exit;
	}
	list($originalwidth, $originalheight) = getimagesize($_FILES['gramitem']["tmp_name"]);
	if($originalwidth > 1080){
	$ratio = $originalwidth / '1080';
	$new_width = round($originalwidth / $ratio);
	$new_height = round($originalheight / $ratio);
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromgif($_FILES['gramitem']["tmp_name"]);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $originalwidth, $originalheight);
	imagejpeg($image_p, $_FILES['gramitem']["tmp_name"], 100);
	imagedestroy($image_p);
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;
	} else {
	$image = imagecreatefromgif($_FILES['gramitem']["tmp_name"]);
	imagejpeg($image, $_FILES['gramitem']["tmp_name"], 100);
	$instagram->uploadPhoto($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;	
	}
	} else if ($fileext == 'mp4') {
	$duration = round(shell_exec('/usr/bin/ffprobe -f mp4 -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 "'.$_FILES['gramitem']['tmp_name'].'" 2>&1'));
	$width = shell_exec('/usr/bin/ffprobe -f mp4 -v error -show_entries stream=width -of default=noprint_wrappers=1:nokey=1 "'.$_FILES['gramitem']['tmp_name'].'" 2>&1');
	$height = shell_exec('/usr/bin/ffprobe -f mp4 -v error -show_entries stream=height -of default=noprint_wrappers=1:nokey=1 "'.$_FILES['gramitem']['tmp_name'].'" 2>&1');
/*	if ($duration > '60' && $width > '1080'){
		$ratio = $width / '1080';
	$new_width = round($width / $ratio);
	$new_height = round($height / $ratio);
			echo exec('ffmpeg -f mp4 -ss 00:00:00 -i '.$_FILES['gramitem']['tmp_name'].' -t 00:01:00 -vf scale='.$new_width.':'.$new_height.' -y /tmp/upload.mp4 2>&1');
			exit;
			$instagram->uploadVideo('/tmp/upload.mp4', $caption);
			unlink('/tmp/upload.mp4');
	echo 'Success';
	exit;
	}*/
	if ($duration > '60'){
		/*
			echo exec('ffmpeg -f mp4 -ss 00:00:00 -i '.$_FILES['gramitem']['tmp_name'].' -t 00:01:00 -y /tmp/upload.mp4 2>&1');
			exit;
			$instagram->uploadVideo('/tmp/upload.mp4', $caption);
			unlink('/tmp/upload.mp4');
			echo 'Success';
	exit; */
	echo 'Duration is longer than 60 seconds.';
	exit;
	}
/*	if ($width > '1080'){
	
		$ratio = $width / '1080';
	$new_width = round($width / $ratio);
	$new_height = round($height / $ratio);
			echo exec('ffmpeg -f mp4 -i '.$_FILES['gramitem']['tmp_name'].' -vf scale='.$new_width.':'.$new_height.' -y /tmp/upload.mp4 2>&1');
			exit;
			$instagram->uploadVideo('/tmp/upload.mp4', $caption);
			unlink('/tmp/upload.mp4');
	echo 'Success';
	exit;
	} */
	$instagram->uploadVideo($_FILES['gramitem']["tmp_name"], $caption);
	echo 'Success';
	exit;
	} 

	} }  else {
 header("HTTP/1.0 403 Forbidden");
 die();
} 
		} catch (Exception $e){
			echo $e->getMessage();
			exit;
		}?>
