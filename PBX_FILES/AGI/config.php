<?php

$db_address = '';
$db_user = '';
$db_pass = '';
$db = '';

function cdr_connect(){
	global $db_address, $db_user, $db_pass, $db;
        mysql_pconnect($db_address, $db_user, $db_pass) or die(mysql_error());
        mysql_select_db($db) or die(mysql_error());
}

?>
