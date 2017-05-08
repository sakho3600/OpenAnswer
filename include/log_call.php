<?php

$data = array();

if(isset($_POST['Submit'])){
	client_connect();
	$data = $_POST;
	$message = "You have received a call from $data[name]:\r\n$data[message]\r\nAdditional Info:\nAddress: $data[address]\r\n$data[phone]\r\nThank You,\r\nOT Answer Team";
	$sql = "INSERT INTO `messages` (`campaign`,`agent`,`date`,`message`,`read`,`deleted`,`unique_id`) VALUES('$_SESSION[assiged_camp]','$_SESSION[agent]','".time()."','$message','0','0','$data[unique_id]')";
	mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	local_disconnect();
	$data = array();
	$err = "Message recorded";
}
	
$sql = "SELECT company_name, contact, address, city, state, zip, bus_des, phrase, hoop, number_forward, pub_fax, pub_email FROM $db.client_settings WHERE campaign = $_SESSION[assigned_camp]";
//echo $sql;
local_connect();
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Failed to retrieve Company Info"); 
$row = mysqli_fetch_array($result);
//print_r($row);
$company = $row['company_name'];
$contact = $row['contact'];
$caddress = $row['address']."<br />".$row['city'].", ".$row['state']." ".$row['zip'];
$cdesc = $row['bus_des'];
$phrase = $row['phrase'];
$choop = $row['hoop'];
$cphone = $row['number_forward'];
$cfax = $row['pub_fax'];
$cemail = $row['pub_email'];
((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
?>