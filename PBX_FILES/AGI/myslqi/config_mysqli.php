<?php

$db_address = '';
$db_user = '';
$db_pass = '';
$db = '';

function cdr_connect(){
	global $db_address, $db_user, $db_pass, $db;
        ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_address,  $db_user,  $db_pass)) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        ((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE $db")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}

cdr_connect();

?>
