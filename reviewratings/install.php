<?php
$global = new DB_global;
if($_GET['uninstall'] == 'true'){
$global->sqlquery("DROP TABLE ddp_reviewratings");
$global->sqlquery("DROP TABLE ddp_reviewratings_content");
$global->sqlquery("DROP TABLE ddp_reviewratings_user");
header('Location: '.$_SERVER['HTTP_REFERER']);
}else {
$global->sqlquery("CREATE TABLE `ddp_reviewratings` (
  `plugin_enabled` int(1) NOT NULL,
  `userratings_enabled` int(1) NOT NULL,
  `ratings_range` int(2) NOT NULL
) DEFAULT CHARSET=utf8mb4;
");
$global->sqlquery("CREATE TABLE `ddp_reviewratings_content` (
  `c_rating` varchar(3) NOT NULL,
  `c_postid` int(11) NOT NULL
) DEFAULT CHARSET=utf8mb4;


ALTER TABLE `ddp_reviewratings_content`
  ADD PRIMARY KEY (`c_postid`);");
$global->sqlquery("CREATE TABLE `ddp_reviewratings_user` (
  `u_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_ratingid` int(11) NOT NULL,
  `u_postid` int(11) NOT NULL,
  `u_rated` varchar(3) NOT NULL
) DEFAULT CHARSET=utf8mb4;


ALTER TABLE `ddp_reviewratings_user`
  ADD PRIMARY KEY (`u_ratingid`);


ALTER TABLE `ddp_reviewratings_user`
  MODIFY `u_ratingid` int(11) NOT NULL AUTO_INCREMENT;");
$global->sqlquery("INSERT INTO `ddp_reviewratings` (`plugin_enabled`, `userratings_enabled`, `ratings_range`) VALUES ('1', '1', '1')");
header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>