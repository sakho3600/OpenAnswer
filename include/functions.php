<?php
require_once('config.php');
function local_connect(){
	global $db_host, $db_user, $db_pass,$db;
	($GLOBALS["___mysqli_ston"] = mysqli_connect($db_host,  $db_user,  $db_pass)) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." pconnect");
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE $db")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." db select $db_host");
}

function local_disconnect(){
	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

function asterisk_connect(){
	($GLOBALS["___mysqli_ston"] = mysqli_connect("dialer.openteleservices.com",  "root",  "Fc23zP8")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE asterisk")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
}

function __autoload($className) {
  require_once("$className"."_class.php");
}

function test_row(){
	global $new_row_op, $new_row_cl, $cell;
	if($cell==2){
		$new_row_op = "<tr>";
		$new_row_cl = "<tr />";
		$cell = 0;
	}
	else{
		$new_row_op = "";
		$new_row_cl = "";
	}
}
function currentURL($name='', $vars='') {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= ($name==1?$_SERVER["SERVER_NAME"]:"").":".$_SERVER["SERVER_PORT"].($vars==1?$_SERVER["REQUEST_URI"]:"");
 } else {
  $pageURL .= ($name==1?$_SERVER["SERVER_NAME"]:"").($vars==1?$_SERVER["REQUEST_URI"]:"");
 }
 return $pageURL;
}
function ymax($ymax){
	if($ymax >100){
		$pow2 = strlen(intval($ymax));
		$number3 = $ymax/pow(10,$pow2);
		$ymax = $number3*100;
		$ymax = ceil($ymax)/100;
		$ymax = $ymax*pow(10,$pow2);
		return $ymax;
	}
	else{
		$ymax  = ceil(intval($ymax)/10)*10;
		return $ymax;
	}
}
function multiply(&$value, $key, $factor){
   $value = round(($value*$factor),3);
}

?>