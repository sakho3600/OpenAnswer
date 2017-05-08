<?php

class record_manager{
	
private function checkUserPerms($user){
		$var = new user();
		return $var->userPerms($user);
}
	
public function record_call($requester_id, $channel, $call_unique_id, $file_name='', $other_user=''){
	$perms = $this->checkUserPerms($requester_id);
	if(!empty($perms)){
		if(!preg_match("/0x1/i", $perms)){
			if(!empty($other_user)){ if($perms['record_other_calls']==1){ $perms = 1; } else{ $perms = 0; } }
			else{ if($perms['record_calls']==1){ $perms = 1; } else{ $perms = 0; } }
			if($perms==1){
				if(empty($file_name)){ $file_name = md5($requester_id.$call_unique_id); }
				local_connect();
				$sql = "INSERT INTO `recorded_calls` (`call_unique_id`, ``user_issued, ``time, `file_name`)
						VALUES ('$call_unique_id', '$requester_id', '".time()."', '$file_name') LIMIT 1";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if($result){
					$record = new asterisk();
					$record->record_call($channel, $file_name);
				}
				else{
					$error = "0x1 Error sending call to record queue. ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
					return $error;
				}
			}
			else{
				$error = "0x1 You do not have permission to record calls. Please contact your administrator for additional assistance.";
				return $error;
			}
		}
		else{
			$error = "0x1 There was an error checking the user permissions. $perms";
			return $error;
		}
	}
	else{
		$error = "0x1 You do not have permission to record calls. Please contact your administrator for additional assistance.";
		return $error;
	}
}

public function list_recordings($requester_id, $id ='', $call_unique_id='', $user_id='', $time='', $range=''){
	//Method is for searching the DB for recorded calls, then returning the results as an associated array.
	$perms = $this->checkUserPerms($requester_id);
	if(!empty($perms) && is_array($perms)){
		if(!preg_match("/0x1/i", implode("",$perms))){
			if($perms['play_record']==1){
				$and = 0;
				if(!empty($id)){ $id = "`id`='$id'"; $and++; }
				if(!empty($call_unique_id)){ if($and>0){ $a = " AND "; } $call_unique_id = "$a`call_unique_id`='$call_unique_id'"; $and++; }
				if(is_array($time)){ if($and>0){ $a = " AND "; } $time = "$a`time`>(int)'$time[0]' AND `time`<(int)'$time[1]'"; $and++; }
				if($and > 0){ $where = "WHERE $id $call_unique_id $time"; }
				if(is_array($range) && $range[0]==0){
					$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `call_unique_id`, `user_issued`, `time` FROM `recorded_calls` $where LIMIT 0, $range[1]";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					$_SESSION['rec_total'] = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT FOUND_ROWS();");
				}
				else{
					$sql = "SELECT `id`, `call_unique_id`, `user_issued`, `time` FROM `recorded_calls` $where";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				}
				if($result){
					if(mysqli_num_rows($result) > 0){
						$recordings = array();
						while($row = mysqli_fetch_array($result)){
							$recordings[] = $row;
						}
						return $recordings;
					}
					else{
						return NULL;
					}
				}
				else{
					$error = "0x1 Error retrieving recorded calls :".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the DB settings and ensure proper configuration.";
					return $error;
				}
			}
			else{
				$error = "0x1 You do not have permission to review recorded calls. Please contact your administrator for additional assistance.";
				return $error;
			}
		}
		else{
			$error = "0x1 There was an error checking the user permissions. $perms";
			return $error;
		}
	}
	else{
		$error = "0x1 You do have permission to review recorded calls. Please contact your administrator for additional assistance.";
		return $error;
	}
}

public function delete_recording($requester_id, $record_id){
	$perms = $this->checkUserPerms($requester_id);
	if(!empty($perms) && is_array($perms)){
		if(!preg_match("/0x1/i", implode("",$perms))){
			if($perms['delete_record']==1){
				$sql = "DELETE FROM `recorded_calls` WHERE `id`='$record_id' LIMIT 1";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if(!$result){
						$error = "0x1 Error deleting recording: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the delete_recording method and ensure you've provided everything correctly.";
						return $error;
				}
				else{
					//In the future we'll log this action in the DB.
					$msg = "1x1 Recording Deleted Successfully.";
					return $msg;
				}
			}
			else{
				$error = "0x1 You do not have permission to delete recordings. Please contact your administrator for additional assistance.";
				return $error;
			}
		}
		else{
			$error = "0x1 There was an error checking the user permissions. $perms";
			return $error;
		}
	}
	else{ return "0x1 ou do not have permission to delete recordings. Please contact your administrator for additional assistance."; }
}

public function get_recording($requester_id, $record_id){
	if(is_object($this)){
		$perms = $this->checkUserPerms($requester_id);
	}
	else{
		$perms = self::checkUserPerms($requester_id);
	}
	//fix array
	if(!empty($perms) && is_array($perms)){
		if(!preg_match("/0x1/i", implode("",$perms))){
			if($perms['play_record']==1){
				$sql = "SELECT `filename`, OCTET_LENGTH(`blob`) AS `size`, `blob`, `url`FROM `recorded_calls` WHERE `id`='$record_id' LIMIT 1";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if(!$result){
					$error = 1;	
				}
				else{
					$file = mysqli_fetch_assoc($result);
				}
			}
			else{
				$error = 2;
			}
		}
		else{
			$error = 1;
		}
	}
	else{ $error = 2; }
	
	if(!empty($error)){
		switch ($error){
			case 1:
				$file['filename'] = 'error.mp3';
				$file['loc'] = ROOT_DIR.'/audio/error.mp3';
				$file['size'] = filesize($file['loc']);
				$file['error'] = 1;
			break;
			
			case 2:
				$file['filename'] = 'no_perms_rec.mp3';
				$file['loc'] = ROOT_DIR.'/audio/no_perms_rec.mp3';
				$file['size'] = filesize($file['loc']);;
				$file['error'] = 1;				
			break;
		}
	}
	return $file;	
}



//End of class
}
?>