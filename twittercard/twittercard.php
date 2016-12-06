<?php

if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class twittercard extends plugin {
	
static function head(){
$global = new DB_global;

$twitter1 = $global->sqlquery("SELECT * FROM ddp_twittercard");
$twitter2 = $twitter1->fetch_assoc();

$link = str_replace('/', '', $_SERVER['REQUEST_URI']);

$resultpost = $global->sqlquery("SELECT * FROM dd_content WHERE content_permalink = '$link' LIMIT 1");
$resultpost2 = $resultpost->fetch_assoc();

if (!empty($twitter2['twitter_username'])){
if ($twitter2['twitter_card'] == '1'){
echo '<meta name="twitter:card" content="summary_large_image">';
} else {
echo '<meta name="twitter:card" content="summary">';
}
echo '<meta name="twitter:site" content="@'.$twitter2['twitter_username'].'">';
preg_match('/[^< *img*src *= *>"\']*(http[^"\']*)+(png|jpg|gif)/' , $resultpost2['content_description'], $image); 
if (!empty($resultpost2['content_title'])){
echo '<meta property="twitter:title" content="'.$resultpost2['content_title'].'">';
}
if (!empty($resultpost2['content_description'])){
if (!empty($image[0])){
echo '<meta property="twitter:image" content="'.$image[0].'">';
}
}
if (!empty($resultpost2['content_summary'])){
echo '<meta property="twitter:description" content="'.strip_tags($resultpost2['content_summary']).'">';
}
}
}

}
