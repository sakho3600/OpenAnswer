<?php

require_once('config.php');
class user {
	
	private function stringMatch($subject, $type = '1', $strict = TRUE){
		if($strict == FALSE){
			
			$cond[0] = "LIKE '%";
			$cond[1] = "%'";
		}
		else{
			$cond = array();
			$cond[0] = "= '";
			$cond[1] = "'";
		}
	
		switch($type){
			
			case "1":
			//Case 1 used for searching with channels
				if(preg_match("/\//", $subject)){
					$subject = explode("/", $subject);
					if(preg_match("/\-/", $subject[1])){
						$fluff = explode("-", $subject[1]);
						$subject[1] = $fluff[0];
						$fluff = NULL;
					}
					$search = "`tech` $cond[0]$subject[0]$cond[1] AND `channel` $cond[0]$subject[1]$cond[1] LIMIT 1";
					return $search;
				}
				else{
					$error = "0x1 Channel not specified. Channel must be in technology/channel form. $subject";
					return $error;
				}
			break;
			
			case "2":
			//Case 2 used for searching with extensions. Note: extensions can be different from channels
				$search = "`extension $cond[0]$subject$cond[1] LIMIT 1";
				return $search;
			break;
			
			case "3":
			//Case 3 used for searcing with agent id
				$search = "`user_name` $cond[0]$subject$cond[1] LIMIT 1";
				return $search;
			break;
			
			case "4":
			//Case 4 used for matching the md5 encrypted password. Must be accompanied by userID or agentID; use array($user_ID, $pass). Note: password matching is always strict search for secrity purposes.
				if(is_array($subect)){
					$search = "(`user_id` = $subject[0] OR `user_name` = '$subject[0]') AND `pass` = '".md5($subject[1])."' LIMIT 1";
					return $search;
				}
				else{
					$error = "0x1 Must be accompanied by userID or agentID; use array(user_ID, pass).";
					return $error;
				}
			break;
			
			case "5":
			//Case 5 used for searcing with e-mail address
				$search = "`email_address` $cond[0]$subject$cond[1] LIMIT 1";
				return $search;
			break;
			
			case "6":
			//Case 6 used for searching with plain user_id; always strict search.
				$search = "`user_id` = '$subject' LIMIT 1";
				return $search;
			break;
			
			default:
				$error = "0x1 Invalid search subject";
				return $error;
			break;
		}
	}
	
	public function userInfoAll($subject, $type='1', $strict = TRUE){
	  if(!empty($subject)){
		if(preg_match("/\@/i", $subject)){ $subject = explode("@", $subject); $subject = $subject['0']; }
		if(is_object($this)){
			$search = $this->stringMatch($subject, $type, $strict);		
		}
		else{
			$search = self::stringMatch($subject, $type, $strict);
		}
		if(!preg_match("/0x1/i",$search)){
			//If there is any kind of error, we don't want to execute any SQL commands. We could have just checked for a global $error, but this won't work if the other methods report errors later.
			$sql = "SELECT `user_id`, `user_name`, `name`, `extension`, `email_address`, `date_created`, `last_login`, `disabled`, `tech`, `channel` FROM $db.users WHERE $search"; 
			local_connect();
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if($result){
				if(mysqli_num_rows($result)==1){
					$row = mysqli_fetch_assoc($result);
					return $row;
				}
				elseif(mysqli_num_rows($result)==0){
					return NULL;
				}
				else{
					$error = "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the userInfoAll method for the correct usage.";
					return $error;
				}
			}
			else{
				return "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the userInfoAll method for the correct usage.";
			}
			local_disconnect();
		}
		else{
			$error = "0x1 Error with match $search";
			return $error;
		}
	  }
	}
	
	public function userRole($user_id, $limit='1'){
		//The user roles are a way of assigning specific permissions to a group(s) of users. This method simply returns that role and the associated information assigned to the user.
		//Note that in normal usage, the single, highest-level role will be used for permission purposes.
		if(is_numeric($limit)){
			if($limit == 1){
				$limit = "LIMIT 1";
			}
			else{
				$limit = 'LIMIT '.$limit;
			}
		}
		else{
			return "0x1 Error: Invalid limit passed to userRole method.";
		}
		local_connect();
		$sql = "SELECT user_roles.`id`, `title`, `level` FROM $db.user_roles, $db.role_assignment WHERE user_roles.`id`=role_assignment.`role_id` AND role_assignment.`user_id`='$user_id' ORDER BY `level` DESC $limit";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) > 0){
				if($limit > 1){
					$roles = array();
					while($role = mysqli_fetch_assoc($result)){
						$roles[] = $role;
					}
					return $roles;
				}
				else{
					$role = mysqli_fetch_assoc($result);
					return $role;
				}
			}
			else{
				return NULL;
			}
		}
		else{
			$error = "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the userRole method for the correct usage.";
			return $error;
		}
		local_disconnect();
	}
	
	public function userPerms($user_id){
		//This method is for retrieving a single permission set to use for...well...permissions. The database is structure to retrieve permissions based on roles (group permissions)
		//or permissions assigned to a specific user. Note: Permissions asssigned to a specific user will always override role permissions.
		
		//First we test if the user has specific permissions assigned.
		$sql = "SELECT * FROM user_perms WHERE `user_id`=$user_id LIMIT 1";
		local_connect();
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result)==1){
				//No need to check the roles anymore.
				$perms = mysqli_fetch_assoc($result);
				$perms['change_pass'] = 1;
				return $perms;
			}
			else{
				//Checking for roles now.
				$role = $this->userRole($user_id);
				if(is_array($role)){
					if(!preg_match("/0x1/i",implode("",$role))){
						//Good, No errors! So far so good. Now we need to make sure we got something.
						if(empty($role)){
							//Damn! Lazy admins didn't assign this user to a role. Lets create one.
							$sql = "SHOW COLUMNS FROM `role_perm`";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							$perms = array();
							if($result){
								while($col = mysqli_fetch_assoc($result)){
									$perms[$col['Field']] = '0';
								}
								//Remove the index id, we don't need this info.
								unset($perms['role_id']);
								$perms['change_pass'] = 1;
								return $perms;
							}
							else{
								$error = "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the database connection settings in config.php";
								return $error;
							}
						}
						else{
							//Good Admin, you know how to make your job easier.
							$sql = "SELECT * FROM $db.role_perm WHERE `role_id`='$role[id]' LIMIT 1";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if($result){
								$perms = mysqli_fetch_assoc($result);
								unset($perms['id']);
								$perms['change_pass'] = 1;
								$perms['home'] = 1;
								return $perms;
							}
							else{
								$error = "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the database connection settings in config.php";
								return $error;
							}
						}
					}
					else{
						$error = "0x1 Error: The role search returned an error when searching for the userID $user_id: $role";
						return $error;
					}
				}
				elseif(preg_match("/0x1/i",$role)){
						$error = "0x1 Error: The role search returned an error when searching for the userID $user_id: $role";
					return $error;
				}
				else{
					return "0x1 Error: The role search returned an error when searching for the userID $user_id";
				}
			}
		}
		else{
			$error = "0x1 Error with result: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the database connection settings in config.php";
			return $error;
		}
		local_disconnect();
	}
	
	public function add_user($requester_id, $new_user_info, $new_role_id='', $new_user_perms=''){
		/*This method is responsible for adding new users to the DB. The requester_id is the person doing the adding (manager?). The new_user_info is an associative array with the required information.
		  Check the API manual if you're not sure what the array should look like. (Hint: it's everything that is "NOT NULL" in the DB table users.) The new_role_id makes assigning new users a default role so you wont
		  have to do it later. The new_user_perms is an associative array for creating user permissions so you won't have to do it later. Check the API manual if you're not sure what the array should
		  look like. */
		
		//We first have to check the permission of the person doing the adding. We don't random people adding new users do we?
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['add_user']==1){
					local_connect();
					//Good the requester has permission to add users
					$sql = "INSERT INTO `users` (`user_name`, `pass`, `name`, `extension`, `email_address`, `date_created`, `last_login`, `disabled`, `tech`, `channel`) 
							VALUES ('$new_user_info[user_name]', '$new_user_info[pass]', '$new_user_info[name]', '$new_user_info[extension]',
								'$new_user_info[email_address]', '".time()."', '', '$new_user_info[disabled]', '$new_user_info[tech]', '$new_user_info[channel]')";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
						if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1062'){
							$error = "0x1 A user with the agentID of $new_user_info[user_name] already exist. Please change the agentID and try again.";
							return $error;
						}
						else{
							$error = "0x1 Error adding user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_user method and ensure you've provided everything correctly.";
						}
					}
					else{
						$user_id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
						if(!empty($new_role_id)){
							//If the requester wants to add another role to this user.
							$sql = "INSERT INTO `role_assignment` (`user_id`, `role_id`) VALUES ('$user_id', '$new_role_id')";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if(!$result){
								if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1452'){
									$error = "0x1 The userID or the roleID, or both are invalid. Please ensure that the userID and the roleID exist before attempting to assign a new role to the user.";
								}
								else{
									$error = "0x1 Error adding role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_user method and ensure you've provided the correct information.";
								}
							}
							else{
								//In the future we'll log this action in the DB.
								$msg .= "1x1 User Role Added Successfully.\n";
							}
						}
						if(is_array($new_user_perms)){
							//If the requester wants to change the user perms
							$sql = "REPLACE INTO `user_perms` (`user_id`, `add_user`, `remove_user`, `modify_user`, `delete_user`, `add_client`, `remove_client`, `modify_client`, `delete_client`, 
															   `xfer_call`, `xfer_other_call`, `barge_calls`, `record_calls`, `record_other_calls`, `park_call`, `park_other_calls`,`play_record`, `delete_record`) 
									VALUES ('$user_id', '$new_user_perms[add_user]', '$new_user_perms[remove_user]', '$new_user_perms[modify_user]', '$new_user_perms[delet_user]', '$new_user_perms[add_client]',
										'$new_user_perms[remove_client]', '$new_user_perms[modify_client]', '$new_user_perms[delete_client]', '$new_user_perms[xfer_call]', '$new_user_perms[xfer_other_call]',
										'$new_user_perms[barge_calls]', '$new_user_perms[record_calls]', '$new_user_perms[record_other_calls]', '$new_user_perms[park_call]', '$new_user_perms[park_other_calls]',
										'$new_user_perms[play_record]', '$new_user_perms[delete_record]')";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if(!$result){
								$error = "0x1 Error modifying user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_user method and ensure you've provided everything correctly.";
							}
							else{
								//In the future we'll log this action in the DB.
								$msg .= "1x1 User Permissions Set Successfully.\n";
							}
						}
						//In the future we'll log this action in the DB.
						if(!empty($error)){
								  $msg .= $error."\n";
						}
						$msg .= "1x1 User added Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error adding user: You do not have sufficiant permission to add users. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding user: You do not have sufficiant permission to add users. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
	}
	
	public function remove_user($requester_id, $user_id){
		if($requester_id==$user_id){
			return "0x1 Error: Cannot disable yourself.";
		}
		/*This method is responsible for disabling users. The requester_id is the person doing the disabling (manager?). The $user_id is the person we want to disable.*/
		
		//We first have to check the permission of the requester. We don't random people disabling users do we?
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['remove_user']==1){
					//Good the requester has permission to remove users
					$sql = "UPDATE `users` SET `disabled`='1' WHERE `user_id`='$user_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error removing user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the remove_user method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 User disabled Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error removing user: You do not have sufficiant permission to disable users. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error disabling user: You do not have sufficiant permission to disable users. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function modify_user($requester_id, $user_id, $new_user_info='', $new_role_id='', $new_user_perms=''){
		/*This method is responsible for modify existing users in the DB. The requester_id is the person doing the modifying (manager?). The user_id is the
		  user we want to modify. The new_user_info is an associative array with the required information. Check the API manual if you're not sure what the array 
		  should look like. (Hint: it's everything that is "NOT NULL" in the DB table users.) The new_role_id makes re-assigning existing users to a default role easier so 
		  you wont have to do it later. The new_user_perms is an associative array for creating user permissions so you won't have to do it later. Check the API manual 
		  if you're not sure what the array should look like. Note that this function is used to change the user info, the permissions, or both. If nothing is
		  changed, then an error will be thrown.*/
		
		//We first have to check the permission of the person doing the adding. We don't random people adding new users do we?
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_user']==1){
					local_connect();
					//Good the requester has permission to add users
					if(is_array($new_user_info)){
						//If the requester wants to change the user info
						$sql = "REPLACE INTO `users` (`user_id`, `user_name`, ".(!empty($new_user_info['pass'])?"`pass`,":NULL)." `name`, `extension`, `email_address`, `date_created`, `last_login`, `disabled`, `tech`, `channel`) 
								VALUES ('$user_id', '$new_user_info[user_name]', ".(!empty($new_user_info['pass'])?"'$new_user_info[pass]',":NULL)." '$new_user_info[name]', '$new_user_info[extension]',
									'$new_user_info[email_address]', '".time()."', '', '$new_user_info[disabled]', '$new_user_info[tech]', '$new_user_info[channel]')";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
							$error .= "0x1 Error modifying user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_user method and ensure you've provided everything correctly.";
						}
						else{
							//In the future we'll log this action in the DB.
							$msg .= "1x1 User Modified Successfully.\n";
							return $msg;
						}
					}
					if(!empty($new_role_id)){
						//If the requester wants to add another role to this user.
						$sql = "INSERT INTO `role_assignment` (`user_id`, `role_id`) VALUES ('$user_id', '$new_role_id')";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
							if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1452'){
								$error .= "0x1 The userID or the roleID, or both are invalid. Please ensure that the userID and the roleID exist before attempting to assign a new role to the user.";
							}
							else{
								$error .= "0x1 Error adding role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_user method and ensure you've provided the correct information.";
							}
						}
						else{
							//In the future we'll log this action in the DB.
							$msg .= "1x1 User Role Added Successfully.\n";
							return $msg;
						}
					}
					if(is_array($new_user_perms)){
						//If the requester wants to change the user perms
						$sql = "REPLACE INTO `user_perms` (`user_id`, `add_user`, `remove_user`, `modify_user`, `delete_user`, `add_client`, `remove_client`, `modify_client`, `delete_client`, 
														   `xfer_call`, `xfer_other_call`, `barge_calls`, `record_calls`, `record_other_calls`, `park_call`, `park_other_calls`, `play_record`, `delete_record`) 
								VALUES ('$user_id', '$new_user_perms[add_user]', '$new_user_perms[remove_user]', '$new_user_perms[modify_user]', '$new_user_perms[delet_user]', '$new_user_perms[add_client]',
									'$new_user_perms[remove_client]', '$new_user_perms[modify_client]', '$new_user_perms[delete_client]', '$new_user_perms[xfer_call]', '$new_user_perms[xfer_other_call]',
									'$new_user_perms[barge_calls]', '$new_user_perms[record_calls]', '$new_user_perms[record_other_calls]', '$new_user_perms[park_call]', '$new_user_perms[park_other_calls]', 
									'$new_user_perms[play_record]', '$new_user_perms[delete_record]')";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
							$error .= "0x1 Error modifying user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_user method and ensure you've provided everything correctly.";
						}
						else{
							//In the future we'll log this action in the DB.
							$msg .= "1x1 User Permissions Set Successfully.";
							return $msg;
						}
					}
				}
				else{
					$error .= "0x1 Error modifying user: You do not have sufficiant permission to modify users. Please consult with your Administrator for additional help.";
				}
			}
			else{
				$error .= "0x1 There was an error with your request: $perms";
			}
		}
		else{
			$error .= "0x1 Error modifying user: You do not have sufficiant permission to modify users. Please consult with your Administrator for additional help.";
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}
	
	public function delete_user($requester_id, $user_id){
		if($requester_id == $user_id){
			return "0x1 Error: You cannot delete yourself";
		}
		/*This method is responsible for permanantly deleting users. The requester_id is the person doing the deleting (manager?). The $user_id is the person we want to delete.
		  Note: This method doesn't just delete the user from the user table, but from all tables tied to the user (permissions, etc.) with the exception of the CDR. Use with caution*/
		
		//We first have to check the permission of the requester. We don't random people disabling users do we?
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['delete_user']==1){
					//Good the requester has permission to delete users
					$sql = "DELETE FROM `users` WHERE `user_id`='$user_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the delete_user method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 User Deleted Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error deleting user: You do not have sufficiant permission to permantly delete users. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error deleting user: You do not have sufficiant permission to permantly delete users. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function delete_role($requester_id, $role_id){
		$role = $this->userRole($requester_id);
		if(is_array($role) && isset($role['id'])){
			if($role_id == $role['id']){
				return "0x1 Error: You cannot delete a role that you are assigned to.";
			}
		}
		/*This method is responsible for permanantly deleting users. The requester_id is the person doing the deleting (manager?). The $user_id is the person we want to delete.
		  Note: This method doesn't just delete the user from the user table, but from all tables tied to the user (permissions, etc.) with the exception of the CDR. Use with caution*/
		
		//We first have to check the permission of the requester. We don't random people disabling users do we?
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['delete_role']==1){
					//Good the requester has permission to delete users
					$sql = "DELETE FROM `user_roles` WHERE `id`='$role_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the delete_role method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Role Deleted Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error deleting role: You do not have sufficiant permission to permantly delete roles. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error deleting role: You do not have sufficiant permission to permantly delete roles. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	public function delete_team($requester_id, $team_id){
		/*This method is responsible for permanantly deleting teams. The requester_id is the person doing the deleting (manager?). The team_id is the team we want to delete.
		  Note: This method doesn't just delete the team from the teans table, but from all tables tied to the team (permissions, etc.) with the exception of the CDR. Use with caution*/
		
		//We first have to check the permission of the requester. We don't random people disabling teams.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['delete_team']==1){
					//Good the requester has permission to delete users
					$sql = "DELETE FROM `teams` WHERE `id`='$team_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the delete_team method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Team Deleted Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error deleting team: You do not have sufficiant permission to permantly delete teams. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error deleting team: You do not have sufficiant permission to permantly delete teams. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function remove_team($requester_id, $team_id){
		/*This method is responsible for permanantly deleting teams. The requester_id is the person doing the deleting (manager?). The team_id is the team we want to delete.
		  Note: This method doesn't just delete the team from the teans table, but from all tables tied to the team (permissions, etc.) with the exception of the CDR. Use with caution*/
		
		//We first have to check the permission of the requester. We don't random people disabling teams.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_team']==1){
					//Good the requester has permission to delete users
					$sql = "UPDATE `teams` SET `active`='1' WHERE `id`='$team_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error disabling team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the remove_team method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Team Disabled Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error disabling team: You do not have sufficiant permission to permantly disable teams. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error disabling team: You do not have sufficiant permission to permantly disable teams. Please consult with your Administrator for additional help.";
			return $error;
		}
	}

	public function add_role($requester_id, $new_role_perms=''){
		/*This method is responsible for adding new roles to the DB. The requester_id is the person doing the adding (manager?). The new_role_perms is an associative array for 
		creating user/role permissions so you won't have to do it later. Check the API manual if you're not sure what the array should look like. */
		
		//We first have to check the permission of the person doing the adding. We don't random people adding new roles.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['add_role']==1){
					if(is_array($new_role_perms)){
						local_connect();
						//Good the requester has permission to add roles
						$sql = "INSERT INTO `user_roles` (`id`, `title`, `level`) 
								VALUES ('', '$new_role_perms[title]', '$new_role_perms[level]')";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
								$error = "0x1 Error adding role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_role method and ensure you've provided everything correctly.";
						}
						else{
							$role_id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
							if(!empty($role_id)){
									$sql = "REPLACE INTO `role_perm` (`role_id`, `add_user`, `remove_user`, `modify_user`, `delete_user`, `add_client`, `remove_client`, `modify_client`, `delete_client`, 
																	   `xfer_call`, `xfer_other_call`, `barge_calls`, `record_calls`, `record_other_calls`, `park_call`, `park_other_calls`,`play_record`, `delete_record`,
																	   `add_queue`, `delete_queue`, `modify_queue`, `view_all_live_calls`, `view_team_live_calls`, `view_all_cdr`, `view_team_cdr`, `view_own_cdr`, `add_team`,
																	   `delete_team`, `remove_team`, `modify_team`, `view_user_details`, `add_role`, `delete_role`, `modify_role`, `view_client_details`, `view_all_agent_status`,
																	   `view_all_live_stats`, `view_team_live_stats`, `view_team_agent_status`) 
											VALUES ('$role_id', '$new_role_perms[add_user]', '$new_role_perms[remove_user]', '$new_role_perms[modify_user]', '$new_role_perms[delet_user]', '$new_role_perms[add_client]',
												'$new_role_perms[remove_client]', '$new_role_perms[modify_client]', '$new_role_perms[delete_client]', '$new_role_perms[xfer_call]', '$new_role_perms[xfer_other_call]',
												'$new_role_perms[barge_calls]', '$new_role_perms[record_calls]', '$new_role_perms[record_other_calls]', '$new_role_perms[park_call]', '$new_role_perms[park_other_calls]',
												'$new_role_perms[play_record]', '$new_role_perms[delete_record]','$new_role_perms[add_queue]', '$new_role_perms[delete_queue]', '$new_role_perms[modify_queue]', 
												'$new_role_perms[view_all_live_calls]', '$new_role_perms[view_team_live_calls]', '$new_role_perms[view_all_cdr]', '$new_role_perms[view_team_cdr]', '$new_role_perms[view_own_cdr]', 
												'$new_role_perms[add_team]', '$new_role_perms[delete_team]', '$new_role_perms[remove_team]', '$new_role_perms[modify_team]', '$new_role_perms[view_user_details]', 
												'$new_role_perms[add_role]', '$new_role_perms[delete_role]', '$new_role_perms[modify_role]', '$new_role_perms[view_client_details]', '$new_role_perms[view_all_agent_status]',
												'$new_role_perms[view_all_live_stats]', '$new_role_perms[view_team_live_stats]', '$new_role_perms[view_team_agent_status]')";
									$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
									if(!$result){
										$error = "0x1 Error modifying role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_role method and ensure you've provided everything correctly.";
										$sql = "DELETE FROM `user_roles` WHERE `id` = '$role_id';";
										$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
									}
									else{
										//In the future we'll log this action in the DB.
										$msg .= "1x1 Role Permissions Set Successfully.\n";
									}
							}
							else{
								$error .= "0x1 Error: Could not add role permissions. Permissions will need to be set manually. Contact your administrator or support desk for additional help.";
							}
							//In the future we'll log this action in the DB.
							if(!empty($error)){
									  $msg .= $error."\n";
							}
							$msg .= "1x1 Role added Successfully.";
							return $msg;
						}
					}
				}
				else{
					$error = "0x1 Error adding role: You do not have sufficiant permission to add roles. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding roles: You do not have sufficiant permission to add roles. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}

	public function modify_role($requester_id, $role_id, $new_role_perms=''){
		/*This method is responsible for modifying roles to the DB. The requester_id is the person doing the adding (manager?). The new_role_perms is an associative array for 
		creating user/role permissions so you won't have to do it later. Check the API manual if you're not sure what the array should look like. */
		
		//We first have to check the permission of the person doing the adding. We don't random people modifying roles.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_role']==1){
					if(is_array($new_role_perms)){
						local_connect();
						//Good the requester has permission to modify roles
						$sql = "UPDATE `user_roles` SET `title`='$new_role_perms[title]', `level`='$new_role_perms[level]'
								WHERE `id` = '$role_id' LIMIT 1";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
								$error = "0x1 Error adding role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_role method and ensure you've provided everything correctly.";
						}
						else{
							if(!empty($role_id)){
									$sql = "REPLACE INTO `role_perm` (`role_id`, `add_user`, `remove_user`, `modify_user`, `delete_user`, `add_client`, `remove_client`, `modify_client`, `delete_client`, 
																	   `xfer_call`, `xfer_other_call`, `barge_calls`, `record_calls`, `record_other_calls`, `park_call`, `park_other_calls`,`play_record`, `delete_record`,
																	   `add_queue`, `delete_queue`, `modify_queue`, `view_all_live_calls`, `view_team_live_calls`, `view_all_cdr`, `view_team_cdr`, `view_own_cdr`, `add_team`,
																	   `delete_team`, `remove_team`, `modify_team`, `view_user_details`, `add_role`, `delete_role`, `modify_role`, `view_client_details`, `view_all_agent_status`,
																	   `view_all_live_stats`, `view_team_live_stats`, `view_team_agent_status`) 
											VALUES ('$role_id', '$new_role_perms[add_user]', '$new_role_perms[remove_user]', '$new_role_perms[modify_user]', '$new_role_perms[delete_user]', '$new_role_perms[add_client]',
												'$new_role_perms[remove_client]', '$new_role_perms[modify_client]', '$new_role_perms[delete_client]', '$new_role_perms[xfer_call]', '$new_role_perms[xfer_other_call]',
												'$new_role_perms[barge_calls]', '$new_role_perms[record_calls]', '$new_role_perms[record_other_calls]', '$new_role_perms[park_call]', '$new_role_perms[park_other_calls]',
												'$new_role_perms[play_record]', '$new_role_perms[delete_record]','$new_role_perms[add_queue]', '$new_role_perms[delete_queue]', '$new_role_perms[modify_queue]', 
												'$new_role_perms[view_all_live_calls]', '$new_role_perms[view_team_live_calls]', '$new_role_perms[view_all_cdr]', '$new_role_perms[view_team_cdr]', '$new_role_perms[view_own_cdr]', 
												'$new_role_perms[add_team]', '$new_role_perms[delete_team]', '$new_role_perms[remove_team]', '$new_role_perms[modify_team]', '$new_role_perms[view_user_details]', 
												'$new_role_perms[add_role]', '$new_role_perms[delete_role]', '$new_role_perms[modify_role]', '$new_role_perms[view_client_details]', '$new_role_perms[view_all_agent_status]',
												'$new_role_perms[view_all_live_stats]', '$new_role_perms[view_team_live_stats]', '$new_role_perms[view_team_agent_status]')";
									$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
									if(!$result){
										$error = "0x1 Error modifying role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_role method and ensure you've provided everything correctly.";
									}
									else{
										//In the future we'll log this action in the DB.
										$msg .= "1x1 Role Permissions Set Successfully.\n";
									}
							}
							else{
								$error .= "0x1 Error: Could not add role permissions. Permissions will need to be set manually. Contact your administrator or support desk for additional help.";
							}
							//In the future we'll log this action in the DB.
							if(!empty($error)){
									  $msg .= $error."\n";
							}
							$msg .= "1x1 Role Modified Successfully.";
							return $msg;
						}
					}
				}
				else{
					$error = "0x1 Error adding role: You do not have sufficiant permission to add roles. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding roles: You do not have sufficiant permission to add roles. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}
	
	public function assign_role($requester_id, $role_id, $users){
		/*This method is responsible for assigning users to roles to the DB. The requester_id is the person doing the adding (manager?). The role_id is the ID number of the role we're trying
		to assign our users to. The users is an array of the users to be assigned.
		Check the API manual if you're not sure what the array should look like. */
		
		//We first have to check the permission of the person doing the adding. We don't random people adding new roles.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_role']==1){
					if(is_array($users)){
						local_connect();
						$sql = "DELETE FROM `role_assignment` WHERE `role_id` = '$role_id';";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						foreach($users as $var){
								//Good the requester has permission to add roles
								$sql = "INSERT INTO `role_assignment` (`user_id`, `role_id`) 
										VALUES ('$var', '$role_id')";
								$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
								if(!$result){
										$error .= "0x1 Error adding role: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the assign_role method and ensure you've provided everything correctly.";
								}
								else{
									//In the future we'll log this action in the DB.
									if(!empty($error)){
											  $msg .= $error."\n";
									}
									else{
										$msg .= "1x1 Role added Successfully.";
									}
								}
						}
						
					}
					elseif($users == 'none'){
						$sql = "DELETE FROM `role_assignment` WHERE `role_id` = '$role_id';";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					}
				}
				else{
					$error = "0x1 Error assigning role: You do not have sufficiant permission to assign users to roles. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error assigning roles: You do not have sufficiant permission to assing users to roles. Please consult with your Administrator for additional help.";
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
	
	public function assign_team($requester_id, $team_id, $users){
		/*This method is responsible for assigning users to teams to the DB. The requester_id is the person doing the adding (manager?). The team_id is the ID number of the team we're trying
		to assign our users to. The users is an array of the users to be assigned.
		Check the API manual if you're not sure what the array should look like. */
		
		//We first have to check the permission of the person doing the adding.
		
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_team']==1){
					if(is_array($users)){
						$sql = "SELECT `max_members` FROM `teams` WHERE `id` = $team_id;";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if($result){
							$max = mysqli_fetch_row($result);
							$max = $max['0'];
							if(count($users) > $max && $max != 0){
								return "0x1 Error: Only $max users can be assigned to this team.";
							}
							else{
								local_connect();
								$sql = "DELETE FROM `team_assignment` WHERE `team_id` = '$team_id';";
								$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
								foreach($users as $var){
										//Good the requester has permission to add roles
										$sql = "INSERT INTO `team_assignment` (`user_id`, `team_id`) 
												VALUES ('$var', '$team_id')";
										$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										if(!$result){
												$error .= "0x1 Error assigning team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the assign_team method and ensure you've provided everything correctly.";
										}
										else{
											//In the future we'll log this action in the DB.
											if(!empty($error)){
													  $msg .= $error."\n";
											}
											else{
												$msg .= "1x1 Team added Successfully.";
											}
										}
								}
							}
						}
						else{
							$error .= "0x1 Error assigning team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the assign_team method and ensure you've provided everything correctly.";
						}
						
					}
					elseif($users == 'none'){
						$sql = "DELETE FROM `team_assignment` WHERE `team_id` = '$team_id';";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					}
				}
				else{
					$error = "0x1 Error assigning team members: You do not have sufficiant permission to assign users to teams. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error assigning team members: You do not have sufficiant permission to assing users to teams. Please consult with your Administrator for additional help.";
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
	
	public function add_team($requester_id, $team_par=''){
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['add_team']==1){
					//Good the requester has permission to add roles
					if(is_array($team_par)){
						local_connect();
						$sql = "INSERT INTO `teams` (`team_name`, `date_created`, `active`, `max_members`) 
								VALUES ('$team_par[name]', '".time()."', '$team_par[disabled]', '$team_par[max_members]')";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
								$error = "0x1 Error adding team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_team method and ensure you've provided everything correctly.";
						}
						else{
							if(!empty($team_par['defaultLeader'])){
								$team_id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
								if(!empty($team_id)){
										$sql = "INSERT INTO `team_assignment` (`user_id`, `team_id`, `leader`, `default`)
												VALUES ('$team_par[defaultLeader]', $team_id, 1, 1)";
										$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										if(!$result){
											$error = "0x1 Error modifying team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_team method and ensure you've provided everything correctly.";
										}
										else{
											//In the future we'll log this action in the DB.
											$msg .= "1x1 Team Leader Set Successfully.\n";
										}
								}
								else{
									$error .= "0x1 Error: Could not add default team leader. Leader(s) will need to be set manually. Contact your administrator or support desk for additional help.";
								}
							}
							//In the future we'll log this action in the DB.
							if(!empty($error)){
									  $msg .= $error."\n";
							}
							$msg .= "1x1 Team added Successfully.";
							return $msg;
						}
					}
				}
				else{
					$error = "0x1 Error adding team: You do not have sufficiant permission to add teams. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding teams: You do not have sufficiant permission to add teams. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}
	
	public function modify_team($requester_id, $team_id, $team_par){
		$perms = $this->userPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_team']==1){
					//Good the requester has permission to add roles
					if(is_array($team_par)){
						local_connect();
						$sql = "UPDATE `teams` SET `team_name`='$team_par[name]', `last_modified`='".time()."', `active`='$team_par[disabled]', `max_members`='$team_par[max_members]' WHERE `id`=$team_id LIMIT 1";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
								$error = "0x1 Error adding team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_team method and ensure you've provided everything correctly.";
						}
						else{
							if(!empty($team_par['defaultLeader'])){
								if(!empty($team_id)){
										$sql = "UPDATE `team_assignment` SET `default` = NULL, `leader`= NULL WHERE `team_id`='$team_id' AND `default`='1';";
										$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										$sql2 = "REPLACE INTO `team_assignment` (`user_id`, `team_id`,`leader`, `default`) VALUES($team_par[defaultLeader], $team_id, 1, 1)";
										$result2 = mysqli_query($GLOBALS["___mysqli_ston"], $sql2);
										if(!$result || !$result2){
											$error = "0x1 Error modifying team: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_team method and ensure you've provided everything correctly.";
										}
										else{
											//In the future we'll log this action in the DB.
											$msg .= "1x1 Team Leader Set Successfully.\n";
										}
								}
								else{
									$error .= "0x1 Error: Could not update default team leader. Leader(s) will need to be set manually. Contact your administrator or support desk for additional help.";
								}
							}
							//In the future we'll log this action in the DB.
							if(!empty($error)){
									  $msg .= $error."\n";
							}
							$msg .= "1x1 Team Modified Successfully.";
							return $msg;
						}
					}
				}
				else{
					$error = "0x1 Error modifying team: You do not have sufficiant permission to modify teams. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error modifying teams: You do not have sufficiant permission to modify teams. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}
	
	public function change_password($requester_id, $new_user_info){
		local_connect();
		$sql = "SElECT `user_id` FROM `users` WHERE `user_id` = $requester_id AND `pass` = '$new_user_info[curPword]'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) == 1){
						if(is_array($new_user_info)){
							//If the requester wants to change the user info
							$sql = "UPDATE `users` SET `pass` = '$new_user_info[password]' WHERE `user_id` = $requester_id LIMIT 1";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if(!$result){
								$error .= "0x1 Error modifying user: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the change_password method and ensure you've provided everything correctly.";
							}
							else{
								//In the future we'll log this action in the DB.
								$msg .= "1x1 User Password Changed Successfully.\n";
								return $msg;
							}
						}
			}
			else{
				$error .= "0x1 Error: Current password does not match password on file. Please input the correct current password.";
			}
		}
		else{
			$error .= "0x1 Error modifying user current password: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the change_password method and ensure you've provided everything correctly.";
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}



//End of the Class	
}

?>