<?php
$global = new DB_global;
if($_GET['uninstall'] == 'true'){
$global->sqlquery("DROP TABLE ddp_twittercard");
header('Location: '.$_SERVER['HTTP_REFERER']);
}else {
$global->sqlquery("CREATE TABLE `ddp_twittercard` (
  `plugin_enabled` int(1) NOT NULL,
  `twitter_username` varchar(15) NOT NULL,
  `twitter_card` int(1) NOT NULL DEFAULT '0'
) DEFAULT CHARSET=utf8mb4;");
$global->sqlquery("INSERT INTO `ddp_twittercard` (`plugin_enabled`, `twitter_username`, `twitter_card`) VALUES ('1', '', '0')");
header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>