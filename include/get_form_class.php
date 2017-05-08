<?php


class get_form{
	
	public function get_forms($channel,$ext){
		local_connect();
		$sql = "SELECT * FROM `short_cdr` WHERE `agent_channel` LIKE '%$channel/$ext%' AND `time` <= UNIX_TIMESTAMP() AND `time`>= (UNIX_TIMESTAMP(NOW())-15)";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result)==1){
				$row = mysqli_fetch_assoc($result);
				$form = $this->find_form($_SESSION['user_id'], $row['number_dialed'], $row['queue']);
				if(!empty($form) && !preg_match("/0x1/i",$form)){
					$_SESSION['connectedCall'] = array('callerChan' => $row['in_trunk']);
					return $form;
				}
				else{
					return "Cannot locate form";
				}
			}
			else{
				return NULL;
			}
		}
		else{
			return "0x1 Error checking for calls: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
	}

	protected function find_form($uid, $ext, $queue){
		//First we search for a form assigned to the client, assuming that the client has been assigned an extension.
		$sql = "SELECT `form` FROM `xml_forms` WHERE `id` = (SELECT `form_id` FROM `form_assignment` WHERE `active` = 1 
															 AND `client_id` = (SElECT `client` FROM `ext_assignment` WHERE `extension` = '$ext' LIMIT 1)
															 LIMIT 1)";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result)==1){
				$row = mysqli_fetch_assoc($result);
				if(!empty($row['form'])){
					return $row['form'];
				}
				else{
					return NULL;
				}
			}
			else{
				//Now we have to check to see if the queue has been assigned a form.
				$sql = "SELECT `form` FROM `xml_forms` WHERE `id` = (SELECT `form_id` FROM `form_assignment_queue` WHERE `active` = 1 
																	 AND `queue_id` = (SElECT `id` FROM `queues` WHERE `queue_name` = '$queue' LIMIT 1)
																	 LIMIT 1)";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if($result){
					if(mysqli_num_rows($result)==1){
						$row = mysqli_fetch_assoc($result);
						if(!empty($row['form'])){
							return $row['form'];
						}
						else{
							return NULL;
						}
					}
					else{
						//Now we have to check to see if the Team has been assigned a form. Not very effective if a user is assigned to multiple teams because the server picks the first one.
						$sql = "SELECT `form` FROM `xml_forms` WHERE `id` = (SELECT `form_id` FROM `form_assignment_team` WHERE `active` = 1 
																			 AND `team_id` = (SElECT `id` FROM `teams` WHERE `id` = 
																							  (SELECT `team_id` FROM `team_assignment` WHERE `user_id` = $uid LIMIT 1) LIMIT 1)
																			 LIMIT 1)";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if($result){
							if(mysqli_num_rows($result)==1){
								$row = mysqli_fetch_assoc($result);
								if(!empty($row['form'])){
									return $row['form'];
								}
								else{
									return NULL;
								}
							}
							else{
								//fsfd
								//Now we have to check to see if the user has been assigned a form.
								$sql = "SELECT `form` FROM `xml_forms` WHERE `id` = (SELECT `form_id` FROM `form_assignment_user` WHERE `active` = 1 
																					 AND `user_id` = $uid LIMIT 1)";
								$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
								if($result){
									if(mysqli_num_rows($result)==1){
										$row = mysqli_fetch_assoc($result);
										if(!empty($row['form'])){
											return $row['form'];
										}
										else{
											return NULL;
										}
									}
									else{
										//Nothing else to do now but return nothing.
										return NULL;
									}
								}
							}
						}
					}
				}
			}
		}
		else{
			return "0x1 Error locating form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
		
	}	
}


?>