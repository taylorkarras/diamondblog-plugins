<?php

if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class relatedcontent extends plugin {

	static function amp_style(){
echo '.ampspacing {
    padding: 10px;
    margin-bottom: 20px;
}

#relatedcontent{
    list-style: none;
    margin-right: 15px;
	width: 273px;
}

#relatedcontent a{
color: #ffffff;
text-decoration: none;
}

.relatedcontentimage{
	width: 264px;
}

.imagenotavaliable {
    padding-left: 5px;
    padding-right: 5px;
    height: 235px;
    font-size: 57px;
    width: 235px;
    background: linear-gradient(#e4e4e4, #909090);
    text-shadow: 0px 1px 0px rgba(0, 0, 0, 0.5);
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    color: #e6e6e6;
    font-family: Arial;
    text-decoration: none;
}';
	}

	static function amp_bottom(){
	$check = new DB_check;
	$global = new DB_global;
	$retrive = new DB_retrival;
	$link = explode("/", $_SERVER['REQUEST_URI']);
	$post_id = $global->sqlquery("SELECT * FROM dd_content WHERE content_permalink LIKE '".$link[1]."'");
	$post_id_init = $post_id->fetch_assoc();
	$the_post_id = $post_id_init['content_id'];
	$tags = str_replace(',', '', $post_id_init['content_tags']);
	$relatedcontent = $global->sqlquery("select * from dd_content where match(content_title, content_description, content_tags)
  against ('".$tags."' in boolean mode)
  order by rand() desc limit 3;");
  if ($relatedcontent->num_rows > 0) {
    // output data of each row
	echo '<div id="relatedcontent2">
	<h3>You might also like:</h3>
	<ul id="relatedcontent">';
    while($row = $relatedcontent->fetch_assoc()) {
	preg_match('/[^< *img*src *= *>"\']?(http[^"\']*)+(png|jpg|gif)/' , $row['content_description'], $image);
	echo '<li class="plugintheme ampspacing">';
	if (empty($image[0])){
	echo '<a class="imagenotavaliable plugintheme ampspacing" href="https://'.$_SERVER['HTTP_HOST'].'/'.$row['content_permalink'].'/amp">Image Not Avaliable</a>';
	} else {
	echo '<a href="https://'.$_SERVER['HTTP_HOST'].'/'.$row['content_permalink'].'/amp"><amp-img class="relatedcontentimage plugintheme" width="250" height="250" src="'.$image[0].'" alt="'.$row['content_title'].'"></a>';
	}echo '</a><a href="https://'.$_SERVER['HTTP_HOST'].'/'.$row['content_permalink'].'"><h3>'.$row['content_title'].'</h3></a></li>';
	}
	echo '</ul></div>';
  }
  }

    static function post_bottom(){
	$check = new DB_check;
	$global = new DB_global;
	$retrive = new DB_retrival;
	$link = str_replace("/", "", $_SERVER['REQUEST_URI']);
	$post_id = $global->sqlquery("SELECT * FROM dd_content WHERE content_permalink LIKE '".$link."'");
	$post_id_init = $post_id->fetch_assoc();
	$the_post_id = $post_id_init['content_id'];
	$tags = str_replace(',', '', $post_id_init['content_tags']);
	$relatedcontent = $global->sqlquery("select * from dd_content where match(content_title, content_description, content_tags)
  against ('".$tags."' in boolean mode)
  order by rand() desc limit 3;");
  if ($relatedcontent->num_rows > 0) {
    // output data of each row
echo '<style media="screen and (max-width : 615px)">
.relatedcontentimage {
    width: 141px !important;
    height: 141px !important;
}

.imagenotavaliable {
	padding-left: 9px !important;
    padding-right: 9px !important;
	height: 141px !Important;
    font-size: 30px !important;
}
</style><style media="screen and (max-width : 535px)">
.relatedcontentimage {
    width: 127px !important;
    height: 127px !important;
}

.imagenotavaliable {
    padding-left: 5px!important;
    padding-right: 5px !important;
    height: 117px !important;
    font-size: 27px !important;
    width: 122px!important;
    margin-right: 10px!important;
}</style><style media="screen and (max-width : 482px)">

#relatedcontent td {
    width: 262px !important;
    float: left;
}

.relatedcontentimage {
    width: 260px !important;
    height: 260px !important;
}

.imagenotavaliable {
    padding-left: 10px !important;
    padding-right: 10px !important;
    height: 255px !important;
    font-size: 58px !important;
}
</style><style>
#relatedcontent2 {
    margin: auto;}

#relatedcontent {
word-break: break-all;
}

.relatedcontentimage{
	width: 200px;
	vertical-align:top;
}
#relatedcontent td{
padding:10px;
width: 200px;
}

#relatedcontent a{
    color: #ffffff;
    text-decoration: none;
}

$relatedcontent h3 {
	margin-top: 5px;
}

.imagenotavaliable {
	word-break: initial !important;
    padding-left: 19px;
    padding-right: 19px;
    vertical-align: middle;
    display: table-cell;
    text-align: center;
    background: linear-gradient(#e4e4e4, #909090);
    text-shadow: 0px 1px 0px rgba(0, 0, 0, 0.5);
    font-size: 40px;
    color: #e6e6e6;
    font-family: Arial;
    text-decoration: none;
    height: 200px;
}
</style>';
echo '<div id="relatedcontent2">';
	echo '<table id="relatedcontent">
	<h3>You might also like:</h3>
	<br>';
    while($row = $relatedcontent->fetch_assoc()) {
	preg_match('/[^< *img*src *= *>"\']?(http[^"\']*)+(png|jpg|gif)/' , $row['content_description'], $image);
	echo '<td class="plugintheme">';
	if (empty($image[0])){
	echo '<a class="imagenotavaliable plugintheme" href="'.$row['content_permalink'].'" alt="'.$row['content_title'].'" title="'.$row['content_title'].'">Image Not Avaliable</a>';
	} else {
	echo '<a href="'.$row['content_permalink'].'" alt="'.$row['content_title'].'" title="'.$row['content_title'].'"><img class="relatedcontentimage plugintheme" width="200" height="200" src="'.$image[0].'" alt="'.$row['content_title'].'" title="'.$row['content_title'].'"></a>';
	}echo '</a><a href="'.$row['content_permalink'].'" alt="'.$row['content_title'].'" title="'.$row['content_title'].'"><h3>'.$row['content_title'].'</h3></a></td>';
	echo '<td style="padding: 5px;"></td>';
	}
	echo '</table></div><br>';
  }
        return true;
    }
}
?>