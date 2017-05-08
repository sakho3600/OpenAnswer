<?php
/************************************
This file is for creating the connection to the
local and remote databases. Local databases are persistant
while remote DB are single.
***********************************/
function local_connect(){
	require("config.php");
	($GLOBALS["___mysqli_ston"] = mysqli_connect($db_host,  $db_user,  $db_pass)) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." pconnect");
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE $db")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." db select $db_host");
}

function local_disconnect(){
	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

function client_connect(){
	($GLOBALS["___mysqli_ston"] = mysqli_connect("localhost",  "openteleservices",  "MALco52brim")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE answer")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}

function asterisk_connect(){
	($GLOBALS["___mysqli_ston"] = mysqli_connect("dialer.openteleservices.com",  "root",  "Fc23zP8")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE asterisk")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}
?>