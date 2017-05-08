<?php
if(isset($_GET['recordID']) && is_numeric($_GET['recordID']) && $_GET['recordID'] != 0){
	(isset($_GET['play'])?$_GET['recordID'] = str_replace(".mp3","", $_GET['recordID']): NULL);
	$recordID = $_GET['recordID'];
	$obj = new record_manager();
	$file = $obj->get_recording($_SESSION['user_id'],$_GET['recordID']);
	if(!is_array($file)){
		if(preg_match("/0x1/i", $file)){
			echo display::error_msg($file); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error VR:10"); exit();
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1){
	echo "<script language=\"JavaScript\" src=\"/audio-player/audio-player.js\"></script>";
	echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the recording for <span id=\"rName\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
	echo display::get_recordings('','','','','','table');
	exit();
}

else{
	echo display::error_msg("No recording specified. <br />Please select another recording and try again."); exit();
}
if(isset($_GET['dlt']) && $_GET['dlt']==1 && is_object($obj)){
	$action = $obj->delete_recording($_SESSION['user_id'], $_GET['recordID']);
	if(!preg_match("/0x1/i",$action) && preg_match("/1x1/i", $action)){
		echo "<script language=\"JavaScript\" src=\"/audio-player/audio-player.js\"></script>";
		echo display::success_msg(str_replace("1x1","",$action)); 
		echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the recording for <span id=\"rName\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
		echo display::get_recordings('','','','','','table');
	}
	else{
		echo display::error_msg($action); exit();
	}
}

if(isset($_GET['play']) && $_GET['play']==1 && is_object($obj)){
	header("Content-Type: audio/mpeg");
    header('Content-Length: '.$file['size']);
	header('Content-Disposition: filename="'.$file['filename'].'"');
    header('X-Pad: avoid browser bug');
	header('Content-Transfer-Encoding: binary');
    header('Cache-Control: no-cache');
	if(!empty($file['error'])){
		readfile($file['loc']);
	}
	else{
		echo $file['blob'];
	}
}
if(isset($_GET['details']) && $_GET['details']==1){
	$rec = display::get_recordings($recordID);
	//print_r($rec);
	echo "<script language=\"JavaScript\" src=\"/audio-player/audio-player.js\"></script>";
?>
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;"> Recording Details</span></legend>
    <?php $perms = new user(); $perms = $perms->userPerms($_SESSION['user_id']);
	if($perms['delete_record']==1){ ?>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editUser"  onclick="deleteRecording('<?=$rec['0']['id']."', '".call_info::caller_id($rec['0']['call_unique_id'])?>')">Delete</a></span><br />
    <?php } ?>
    <table width="510" border="0" cellpadding="5" cellspacing="0">
      <tr class="sample">
        <td width="225"><label for="client"><strong>Record Date</strong></label></td>
        <td width="285"><?=date("m/d/y \a\\t h:i A",$rec['0']['time'])?></td>
      </tr>
      <tr class="sample">
        <td><label for="form_name"><strong>Caller ID</strong></label></td>
        <td><?=call_info::caller_id($rec['0']['call_unique_id'])?></td>
      </tr>
      <tr class="sample">
        <td><label for="client_form_txt"><strong>Original Ext</strong></label></td>
        <td><?=call_info::number_dialed($rec['0']['call_unique_id'])?></td>
      </tr>
      <tr class="sample">
        <td colspan="2"></label></td>
      </tr>
      <tr>
        <td><strong>Call Queue</strong></td>
        <td><?=call_info::call_queue($rec['0']['call_unique_id'])?></td>
      </tr>
      <tr>
        <td><strong>Agent on Call</strong></td>
        <td><?=$rec['0']['name']?></td>
      </tr>
      <tr>
        <td><strong>Total Call Time</strong></td>
        <td><? $tt = call_info::duration($rec['0']['call_unique_id']); echo ($tt>68?round($tt/60,2)." min":$tt." sec") ?></td>
      </tr>
      <tr>
        <td><strong>Actual Talk Time</strong></td>
        <td><? $bt = call_info::billsec($rec['0']['call_unique_id']); echo ($bt>68?round($bt/60,2)." min": $bt." sec") ?></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Play Recording</strong></td>
      </tr>
      <tr>
        <td colspan="2"><?=display::audio_player(currentURL(1)."/recordings/".$rec['0']['id'].".mp3")?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset><br /><br />
<?php } 	echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">PERMANENTLY DELETE</span> the recording for <span id=\"rName\"></span>?<br /><span style=\"font-weight:bold;\">THIS ACTION CANNOT BE UN-DONE!</span>", "Confirm Deletion");
?>