<?php
$global = new DB_global;
if($_GET['uninstall'] == 'true'){
$global->sqlquery("DROP TABLE ddp_relatedcontent");
header('Location: '.$_SERVER['HTTP_REFERER']);
}else {
$global->sqlquery("CREATE TABLE `ddp_relatedcontent` (
  `plugin_enabled` int(1) NOT NULL DEFAULT '1'
) DEFAULT CHARSET=utf8mb4;
);");
$global->sqlquery("INSERT INTO `ddp_relatedcontent` (`plugin_enabled`) VALUES ('1')");
header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>