<?php

if(isset($_GET['uniqueID']) && is_numeric($_GET['uniqueID']) && $_GET['uniqueID'] != 0){
	(isset($_GET['play'])?$_GET['uniqueID'] = str_replace(".mp3","", $_GET['uniqueID']): NULL);
	$uniqueID = $_GET['uniqueID'];
	$obj = new cdr_search();
	$record = $obj->get_records(array("string"=>$_GET['uniqueID'], "index"=>1, "not"=>0, "strict"=>TRUE));
	if(!is_array($record)){
		if(preg_match("/0x1/i", $record)){
			echo display::error_msg($file); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error VR:10"); exit();
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1){
	if(isset($_GET['tm']) && $_GET['tm']==1){
		echo display::get_team_cdr("table");
	}
	elseif(isset($_GET['prsn']) && $_GET['prsn']==1){
		echo display::get_personal_cdr("table");
	}
	else{
		echo display::get_cdr("table");
	}
	exit();
}

else{
	echo display::error_msg("No call specified. <br />Please select another call and try again."); exit();
}
if(isset($_GET['details']) && $_GET['details']==1){}
?>
