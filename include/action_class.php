<?php

class action{

	public static function login($username, $pass){
		local_connect();
		$sql = "SELECT `user_id`, `name`, `extension`, `channel`, `tech`, `disabled` FROM `users` WHERE `user_name` = '$username' AND `pass` = '$pass'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);
			$sql = "UPDATE `users` SET `last_login`='".time()."' WHERE `user_id`='$row[user_id]' LIMIT 1";
			mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			return $row;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_client($post){
		if(is_array($post)){
			$post['client_number'] = mb_substr(preg_replace("/\D/i","",md5((!empty($post['company'])?$post['company']:$post['contact']))),0,8);
			$obj = new client();
			$result = $obj->add_client($_SESSION['user_id'], $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function modify_client($client_number, $post){
		if(is_array($post)){
			$obj = new client();
			$result = $obj->modify_client($_SESSION['user_id'], $client_number, $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function add_user($post){
		if(is_array($post)){
			$roleID = '';
			$post['pass'] = md5($post['pass']);
			if(!empty($_POST['defaultRole'])){ $roleID = $_POST['defaultRole']; }
			$obj = new user();
			$result = $obj->add_user($_SESSION['user_id'], $post, $roleID);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function modify_user($post){
		if(is_array($post)){
			$roleID = '';
			if(!empty($post['pass'])){ $post['pass'] = md5($post['pass']); }
			if(!empty($_POST['defaultRole'])){ $roleID = $_POST['defaultRole']; }
			$obj = new user();
			$result = $obj->modify_user($_SESSION['user_id'], $post['userID'], $post, $roleID);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function add_role($post){
		if(is_array($post)){
			$obj = new user();
			$result = $obj->add_role($_SESSION['user_id'], $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}

	public function modify_role($post, $role_id){
		if(is_array($post)){
			$obj = new user();
			$result = $obj->modify_role($_SESSION['user_id'], $role_id, $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}

	public function assign_role($post){
		if(is_array($post)){
			(is_array($post['list2']) ?	$users = $post['list2'] : $users = 'none');
			$obj = new user();
			$result = $obj->assign_role($_SESSION['user_id'], $post['role'], $users);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function assign_team($post){
		if(is_array($post)){
			(is_array($post['list2']) ?	$users = $post['list2'] : $users = 'none');
			$obj = new user();
			$result = $obj->assign_team($_SESSION['user_id'], $post['team'], $users);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function add_team($post){
		if(is_array($post)){
			$obj = new user();
			$result = $obj->add_team($_SESSION['user_id'], $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function modify_team($post, $team_id){
		if(is_array($post)){
			$obj = new user();
			$result = $obj->modify_team($_SESSION['user_id'], $team_id, $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function add_form($post){
		if(is_array($post)){
			$obj = new client();
			$result = $obj->add_form($_SESSION['user_id'], $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}

	public function modify_form($post, $form_id){
		if(is_array($post)){
			$obj = new client();
			$result = $obj->modify_form($_SESSION['user_id'], $form_id, $post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function add_queue($post){
		if(is_array($post)){
			$obj = new queue_manager();
			$result = $obj->add_queue($_SESSION['user_id'], $post['queue_name'], $post['strategy'], $post['sla'], $post['queue_ext']);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function modify_queue($post, $queue_id){
		if(is_array($post)){
			$obj = new queue_manager();
			$result = $obj->modify_queue($_SESSION['user_id'], $post['queue_name'], $post['strategy'], $queue_id, $post['sla'], $post['queue_ext']);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function assign_queue($post){
		if(is_array($post)){
			(is_array($post['list2']) ?	$users = $post['list2'] : $users = 'none');
			$obj = new queue_manager();
			$result = $obj->assign_queue($_SESSION['user_id'], $post['queue'], $users);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}

	public function change_settings($post){
		if(is_array($post)){
			if(!empty($post['curPword'])){ $post['curPword'] = md5($post['curPword']); }
			if(!empty($post['password'])){ $post['password'] = md5($post['password']); }
			$obj = new user();
			$result = $obj->change_password($_SESSION['user_id'],$post);
			if(!preg_match("/0x1/i",$result)){
				return TRUE;
			}
			else{
				return $result;
			}
		}
		else{
			return "0x1 Error: Invalid form submission. The Administrator has been contacted.";
		}
	}
	
	public function delete_ext($client_id, $extID){
		$obj = new user();
		$perms = $obj->userPerms($_SESSION['user_id']);
		if(is_array($perms)){
			if($perms['modify_client'] == 1){
				$sql = "DELETE FROM ext_assignment WHERE `id` = $extID AND `client` = $client_id LIMIT 1";
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if($result){
					return "1x1 Extension deleted Successfully.";
				}
				else{
					return "0x1 Error deleting extension: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				}
			}
			else{
				return "0x1 Error: You do not have permission to modify clients. Please consult with your Administrator for additional help.";
			}
		}
		if(preg_match("/0x1/i",$perms)){
			return $perms;
		}
		else{
			return "0x1 Error: There was a problem with your request, you may not have sufficient permission to modify clients. Please consult your Administrator for additinoal help.: $perms";
		}
	}
	
	public function add_ext($post){
		if(isset($post['clientID']) && is_numeric($post['clientID']) && isset($post['extension']) && !empty($post['extension'])){
			$obj = new user();
			$perms = $obj->userPerms($_SESSION['user_id']);
			if(is_array($perms)){
				if($perms['modify_client'] == 1){
					$sql = "INSERT INTO ext_assignment (`client`, `extension`) VALUES($post[clientID],'$post[extension]');";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if($result){
						return TRUE;
					}
					else{
						return "0x1 Error adding extension: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					}
				}
				else{
					return "0x1 Error: You do not have permission to modify clients. Please consult with your Administrator for additional help.";
				}
			}
			if(preg_match("/0x1/i",$perms)){
				return $perms;
			}
			else{
				return "0x1 Error: There was a problem with your request, you may not have sufficient permission to modify clients. Please consult your Administrator for additinoal help.: $perms";
			}
		}
		else{
			return "0x1 Error: Invalid data provided.";
		}
	}
	
	public function login_to_queues($uid,$ext,$chan){
		local_connect();
		$sql = "SELECT `queue_name` FROM `queues`, `queue_assignment` WHERE `queues`.`id` = `queue_assignment`.`queue_id` AND `queue_assignment`.`user_id` = '$uid'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) > 0){
				$obj = new asterisk;
				while($row = mysqli_fetch_assoc($result)){
					$obj->agent_login($ext, $row['queue_name'], $chan, '');
				}
				return TRUE;
			}
			else{
				echo "User not assigned to any queues. Calls will not be routed to this user.";
			}
		}
		else{
			echo "0x1 Error accessing assigned queues: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
		local_disconnect();
	}
	
	public function logout_of_queues($uid,$ext,$chan){
		local_connect();
		$sql = "SELECT `queue_name` FROM `queues`, `queue_assignment` WHERE `queues`.`id` = `queue_assignment`.`queue_id` AND `queue_assignment`.`user_id` = '$uid'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) > 0){
				$obj = new asterisk();
				while($row = mysqli_fetch_row($result)){
					$obj->agent_logoff($ext, $row['0'], $chan, '');
				}
				return FALSE;
			}
		}
		else{
			echo "0x1 Error accessing assigned queues: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
		local_disconnect();
	}
	
	public function hangUpCall($chan){
		$obj = new asterisk();
		$obj->hangup($chan);		
	}
	public function pauseAgent($pause,$uid,$ext,$chan){
		local_connect();
		$sql = "SELECT `queue_name` FROM `queues`, `queue_assignment` WHERE `queues`.`id` = `queue_assignment`.`queue_id` AND `queue_assignment`.`user_id` = '$uid'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) > 0){
				$obj = new asterisk();
				while($row = mysqli_fetch_row($result)){
					$obj->agent_pause($ext, $row['0'], $chan, '',$pause);
				}
			}
		}
		else{
			echo "0x1 Error accessing assigned queues: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
		local_disconnect();
	}
	public function transfer_call($ext, $chan){
		(preg_match("/^9/",$ext)?$context = 'numberplan-custom-1':$context='');
		$obj = new asterisk();
		$obj->transfer($chan,$ext,$context);
		return TRUE;
	}
	public function parkCall($chan,$retry=0){
		if($retry < 4 ){
			$obj = new asterisk();
			$try = 5;
			while($try >= 1){
				$call_detail = $obj->channel_detail($chan);
				if(!empty($call_detail['Link'])){
					$try = 0;
				}
				else{
					$try--;
				}
			}
			if($try < 1 && !empty($call_detail['Link'])){
				$success = $obj->park_call($chan,$call_detail['Link']);
				if($success === TRUE){
					return "Your call is now on hold";
				}
				else{
					self::parkCall($chan,$retry+1);
					//return "Failed to hold call. Please try again.";
				}
			}
			else{
				self::parkCall($chan,$retry+1);
				//return "Failed to hold call. Please try again.";
			}
		}
		else{
			return "Failed to hold call. Please try again.";
		}
	}
	public function unParkCall($chan,$return){
		$obj = new asterisk();
		$success = $obj->transfer($chan,$return);
		if($success != TRUE){
			self::unParkCall($chan,$return);
		}
	}



// End of Class
}



?>