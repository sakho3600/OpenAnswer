<?php
session_start();
require('include/functions.php');
require('include/constants.php');
require('include/what_if.php');
function validate_xml($xml){
	$doc = new DOMDocument();
	if($doc->parseError->errorCode==0){
		return TRUE;
	}
	else{
		return FALSE;
	}
}
if(is_array($_POST)){
	$requester_id = $_SESSION['user_id'];
	$client_id = $_POST['clientID'];
	$xml = "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
	$xml = $xml."<form submitter='$requester_id' client='$client_id'>\n";
	foreach($_POST as $key=> $val){
		$xml = $xml."\t<$key>$val</$key>\n";
	}
	$xml = $xml."</form>";
	if(validate_xml($xml)==TRUE){
		local_connect();
		$sql = "INSERT INTO `submitted_forms` (`user_id`, `client_id`, `form_data`) VALUES ('$requester_id', '$client_id', '".addslashes($xml)."')";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			echo display::success_msg("1x1 Form processed successfully.");
		}
		else{
			echo display::error_msg("0x1 Error: Failed to save submission. ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB settings and try again.");
		}
	}
	else{
		echo display::error_msg("0x1 Error: Could not process form. Please contact your system administator for further help.");
	}
	local_disconnect();
}
else{
	echo display::error_msg("0x1 Error: Invalid submission. Please contact your system administrator for additional help.");
}
?>
