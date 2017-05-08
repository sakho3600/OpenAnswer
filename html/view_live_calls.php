<?php
if(isset($_GET['cdrID']) && is_numeric($_GET['cdrID']) && $_GET['cdrID'] != 0){
	$cdrID = $_GET['cdrID'];
	$obj = new call_info();
	$file = $obj->callInfoAll($cdrID);
	if(!is_array($file)){
		if(preg_match("/0x1/i", $file)){
			echo display::error_msg($file); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error VLC:10"); exit();
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1){
	echo "<div id=\"#connectedCalls\"><span style=\"font-weight:bold; font-size: 1.2em;\">Connected Live Calls</span><br />";
		echo display::get_live_connected_calls('table');
	echo "</div><br /><br />";
	echo "<div id=\"#onHoldCalls\"><span style=\"font-weight:bold; font-size: 1.2em;\">On Hold Live Calls</span><br />";
		echo display::get_live_hold_calls('table');
	echo "</div>";
	exit();
}

else{
	echo display::error_msg("No recording specified. <br />Please select another recording and try again."); exit();
}
?>