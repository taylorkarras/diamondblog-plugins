<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/global.php';

$global = new DB_global;

if(isset($_POST)){
$query = $global->sqlquery ("SELECT * FROM ddp_pushnotifications_list WHERE list_endpoint = '".$_POST['endpoint']."';");
if($query->num_rows > 0){
return false;
exit;
} else {
$global->sqlquery ("INSERT INTO `ddp_pushnotifications_list` (`list_pos`, `list_ip`, `list_endpoint`, `list_useragent`) VALUES (NULL, '".$_POST['ipaddress']."', '".$_POST['endpoint']."', '".$_POST['useragent']."')");
exit;
}
}
