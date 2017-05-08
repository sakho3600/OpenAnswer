<?php
if(isset($_GET['userID']) && is_numeric($_GET['userID']) && $_GET['userID'] != 0){
	$obj = new user();
	$info = $obj->userInfoAll($_GET['userID'], 6);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error UD:10"); exit();
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the user <span id=\"uName\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
	echo "<div style=\"float:right;\">".display::get_search('users')."</div><br /><br />";
	echo display::get_users('table','','',1);
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_users('table','','',1,$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No user specified. <br />Please select another user and try again."); exit();
}
if(isset($_GET['dlt']) && $_GET['dlt']==1 && is_array($info)){
	$action = $obj->delete_user($_SESSION['user_id'], $_GET['userID']);
	if(!preg_match("/0x1/i",$action) && preg_match("/1x1/i", $action)){
		echo display::success_msg(str_replace("1x1","",$action));
		echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the user <span id=\"uName\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
		echo "<div style=\"float:right;\">".display::get_search('users')."</div><br /><br />";
		echo display::get_users('table','','',1); exit();
	}
	else{
		echo display::error_msg($action); exit();
	}
}
?>