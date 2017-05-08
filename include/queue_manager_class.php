<?php

class queue_manager{
	
	private function checkUserPerms($user){
		$var = new user();
		return $var->userPerms($user);
	}
	
	public function add_queue($requester_id, $name, $strategy, $sla = '0', $queue_ext = '0000'){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			if(!preg_match("/0x1/i", implode("",$perms))){
				if($perms['add_queue']==1){ $perms = 1; } else{ $perms = 0; }
				if($perms==1){
					local_connect();
					$sql = "INSERT INTO `queues` (`queue_name`, `queue_ext`, `strategy`, `sla`) VALUES ('$name','$queue_ext','$strategy','$sla')";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
						if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1062'){
							$error .= "0x1 A queue with the queue name of $name already exist. Please change the queue name and try again.";
						}
						else{
							$error .= "0x1 Error adding queue: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
						}
					}
					else{
						$msg = "1x1 Queue added successfully.";
						return $msg;
					}
					local_disconnect();
				}
				else{
					$error .= "0x1 You do not have permission to add queus. Please contact your administrator for additional assistance.";
				}
			}
			else{
				$error .= "0x1 There was an error checking the user permissions. $perms";
			}
		}
		else{
			$error .= "0x1 You do not have permission to add queues. Please contact your administrator for additional assistance.";
		}
		if(!empty($error)){
			return $error;
		}
	}
	
	public function delete_queue($requester_id, $queue_id){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			if(!preg_match("/0x1/i", implode("",$perms))){
				if($perms['delete_queue']==1){ $perms = 1; } else{ $perms = 0; }
				if($perms==1){
					local_connect();
					$sql = "DELETE FROM `queues` WHERE `id` = '$queue_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting queue: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
					}
					else{
						$msg = "1x1 Queue deleted successfully.";
						return $msg;
					}
					local_disconnect();
				}
				else{
					$error = "0x1 You do not have permission to delete queues. Please contact your administrator for additional assistance.";
				}
			}
			else{
				$error = "0x1 There was an error checking the user permissions. $perms";
				return $error;
			}
		}
		elseif(preg_match("/0x1/i",$perms)){
				return "0x1 There was an error with your request: $perms";
		}
		else{
			$error = "0x1 You do not have permission to delete queues. Please contact your administrator for additional assistance.";
			return $error;
		}	
	}
		
	public function modify_queue($requester_id, $name, $strategy, $id, $sla = '0', $queue_ext = '0000'){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			if(!preg_match("/0x1/i", implode("",$perms))){
				if($perms['modify_queue']==1){ $perms = 1; } else{ $perms = 0; }
				if($perms==1){
					local_connect();
					$sql = "REPLACE INTO `queues` (`id`, `queue_name`, `queue_ext`, `strategy`, `sla`) VALUES ('$id', '$name','$queue_ext', '$strategy','$sla')";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
						$error .= "0x1 Error modifying queue: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_queue method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg .= "1x1 Queue Modified Successfully.\n";
						return $msg;
					}
					local_disconnect();
				}
				else{
					$error .= "0x1 You do not have permission to modify queus. Please contact your administrator for additional assistance.";
				}
			}
			else{
				$error .= "0x1 There was an error checking the user permissions. $perms";
			}
		}
		else{
			$error .= "0x1 You do not have permission to modify queues. Please contact your administrator for additional assistance.";
		}
		if(!empty($error)){
			return $error;
		}
	}
	
	public function queue_info($type, $search){
		switch($type){
			case "1":
			  $where = "`id` = '$search'";	
			break;
			
			case "2":
			    $where = "`queue_name` = '$search'";
			break;
			
			case "3":
				$where = "`queue_ext` = '$search'";
			break;
			
			case "4":
				$where = "`strategy` = '$search'";
			break;
			
			case "5":
				if(is_array($search)){ if(!is_numeric($search['0']) || !is_numeric($search['1'])){ $error = "0x1 Error: SLA range must be numeric."; break; }
					else{ $where = "`sla` > '$search[0]' AND `sla` < '$search[1]'"; break; }
				}
				else{
					if(!is_numeric($search)){ $error = "0x1 Error: SLA must be numeric."; break; }
					else{ $where = "`sla` = '$search'"; break;}
				}
		}
		if(empty($error)){
			local_connect();
			$sql = "SELECT * FROM `queues` WHERE $where";
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(!$result){
				$error = "0x1 Error retrieving queue information: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the queue_info method and ensure you've provided everything correctly.";
				return $error;
			}
			else{
				if(mysqli_num_rows($result) > 1){
					while($rows = mysqli_fetch_assoc($result)){
						$queues[] = $rows;
					}
					return $queues;
				}
				elseif(mysqli_num_rows($result) == 0){
					return NULL;
				}
				else{
					return mysqli_fetch_assoc($result);
				}
			}
			local_disconnect();
		}
		else{
			return $error;
		}
	}
	
	public function queue_assign_agent($agent_id, $queue_id){
		if(empty($agent_id) || empty($queue_id)){
			return "0x1 Error: Both Agent ID and Queue ID are required to assign agents to queues.";
		}
		elseif(!is_numeric($agent_id) || !is_numeric($queue_id)){
			return "0x1 Error: Both Agent ID and Queue ID must be numeric.";
		}
		else{
			local_connect();
			$sql = "INSERT INTO `queue_assignment` WHERE `queue_id` = '$queue_id' AND `user_id` = '$user_id' LIMIT 1";
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(!$result){
				if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1062'){
					$error = "0x1 The agent $agent_id you are trying to assign to queue $queue_id has already been assigned to this queue.";
					return $error;
				}
				elseif(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1452'){
					if(preg_match("/user\_id/i",((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)))){
						return "0x1 The agent, $agent_id, you have selected doesn't exist in the database";}
					elseif(preg_match("/\`queues\`\(\`id\`\)/i",((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)))){
						return "0x1 The queue, $queue_id, you have selected doesn't exist in the database";
					}
					else{
						return "0x1 The agent and queue you are tying to assign does not exist.";
					}
				}
				else{
					return "0x1 Error adding agent $agent_id to queue $queue_id ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
				}
			}
			else{
				return "1x1 Agent $agent_id assigned to queue $queue_id successfully.";
			}
			local_diconnect();
		}
	}
	
	public function queue_remove_agent($agent_id, $queue_id){
		if(empty($agent_id) || empty($queue_id)){
			return "0x1 Error: Both Agent ID and Queue ID are required to unassign agents from queues.";
		}
		elseif(!is_numeric($agent_id) || !is_numeric($queue_id)){
			return "0x1 Error: Both Agent ID and Queue ID must be numeric.";
		}
		else{
			local_connect();
			$sql = "DELETE FROM `queue_assignment` WHERE `queue_id` = '$queue_id' AND `user_id` = '$user_id' LIMIT 1";
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(!$result){
					return "0x1 Error removing agent $agent_id from queue $queue_id ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
			}
			else{
				if(mysqli_affected_rows($GLOBALS["___mysqli_ston"])==0){
					return "0x1 Warning: Agent assignment does not exist";
				}
				else{
					return "1x1 Agent $agent_id assignment to queue $queue_id has been removed successfully.";
				}
			}
			local_disconnect();
		}		
	}
	
	public function view_queue_assignments($agent_id = '', $queue_id=''){
		if(empty($agent_id) && empty($queue_id)){
			$sql = "SELECT * FROM `queue_assignment` ORDER BY `queue_id` ASC";
		}
		if(!empty($agent_id) && empty($queue_id)){
			if(!is_numeric($agent_id)){
				$error = "0x1 Error: Agent ID must be numeric.";
			}			
			$sql = "SELECT * FROM `queue_assignment` WHERE `user_id` = '$agent_id' ORDER BY `queue_id` ASC";
		}
		if(empty($agent_id) && !empty($queue_id)){
			if(!is_numeric($queue_id)){
				$error = "0x1 Error: Queue ID must be numeric.";
			}						
			$sql = "SELECT * FROM `queue_assignment` WHERE `queue_id` = '$queue_id' ORDER `user_id` ASC";
		}
		else{
			if(!is_numeric($agent_id) || !is_numeric($queue_id)){
				$error = "0x1 Error: Both Agent ID and Queue ID must be numeric.";
			}			
			$sql = "SELECT * FROM `queue_assignment` WHERE `queue_id` = '$queue_id' AND `user_id` = '$user_id'";
		}
		if(empty($error)){
			local_connect();
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(!$result){
				return "0x1 Error retrieving agent-queue assignments. ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check your DB connection settings.";
			}
			else{
				if(mysqli_num_rows($result) > 1){
					$assignments = array();
					while($rows = mysqli_fetch_assoc($result)){
						if(!is_array($assignments[$rows['queue_id']])){ $assignments[$rows['queue_id']] = array(); }
						$assignments[$rows['queue_id']] = $rows['user_id'];
					}
				}
				elseif(mysqli_num_rows($result) < 1){
					return FALSE;
				}
				else{
					return mysqli_fetch_assoc($result);
				}
			}
		}
		else{
			return $error;
		}
	}
	
	public function assign_queue($requester_id, $queue_id, $users){
		$obj = new user();
		$perms = $obj->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_queue']==1){
					if(is_array($users)){
						local_connect();
						$sql = "DELETE FROM `queue_assignment` WHERE `queue_id` = '$queue_id';";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						foreach($users as $var){
								//Good the requester has permission to add roles
								$sql = "INSERT INTO `queue_assignment` (`queue_id`, `user_id`) 
										VALUES ('$queue_id', '$var')";
								$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
								if(!$result){
										$error .= "0x1 Error assigning queue: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the assign_queue method and ensure you've provided everything correctly.";
								}
								else{
									//In the future we'll log this action in the DB.
									if(!empty($error)){
											  $msg .= $error."\n";
									}
									else{
										$msg .= "1x1 Users Added To Queue Successfully.";
									}
								}
						}
					}
					elseif($users == 'none'){
						$sql = "DELETE FROM `queue_assignment` WHERE `queue_id` = '$queue_id';";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					}
				}
				else{
					$error = "0x1 Error assigning queue members: You do not have sufficiant permission to assign users to queues. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error assigning queue members: You do not have sufficiant permission to assing users to queues. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
		if(!empty($msg)){
			return $msg;
		}
	}

		
}


?>