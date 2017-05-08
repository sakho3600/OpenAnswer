<?php
if(isset($_GET['formID']) && is_numeric($_GET['formID']) && $_GET['formID'] != 0){
	$info = display::get_client_forms('','','','','',$_GET['formID']);
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
	echo display::build_confirm_box("Are you sure you want to remove the form <span id=\"fName\"></span>?<br />", "Confirm Disable");
	echo "<div style=\"float:right;\">".display::get_search('forms')."</div><br /><br />";
	echo display::get_client_forms('table','','1');
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_client_forms('table','','1','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No form specified. <br />Please select another user and try again."); exit();
}
if(isset($_GET['rmv']) && $_GET['rmv']==1 && is_array($info)){
	$obj = new client();
	$action = $obj->remove_form($_SESSION['user_id'], $_GET['formID']);
	if(!preg_match("/0x1/i",$action) && preg_match("/1x1/i", $action)){
		echo display::success_msg(str_replace("1x1","",$action)); 
		echo display::build_confirm_box("Are you sure you want to remove the form <span id=\"fName\"></span>?<br />", "Confirm Disable");
		echo "<div style=\"float:right;\">".display::get_search('forms')."</div><br /><br />";
		echo display::get_client_forms('table','',1); exit();
	}
	else{
		echo display::error_msg($action); exit();
	}
}
?>