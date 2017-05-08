<?php

require_once('config.php');
class client {
	
	private function stringMatch($subject, $type = '1', $strict = TRUE, $limit= '1', $adress_type = '1'){
		if($strict == FALSE){
			
			$cond[0] = "LIKE '%";
			$cond[1] = "%'";
		}
		else{
			$cond = array();
			$cond[0] = "= '";
			$cond[1] = "'";
		}
		if($limit == '1'){
			$limit = 'LIMIT 1';
		}
		else{
			$limit = '';
		}
	
		switch($type){
			
			case "1":
			//Case 1 used for searching for client numbers
					$search = "`client_number` $cond[0]$subject$cond[1] $limit";
			break;
			
			case "2":
			//Case 2 used for searching company name
				$search = "`company_name` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "3":
			//Case 3 used for searcing for a contact
				$search = "`contact` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "4":
			//Case 4 used for matching the address use 2 for mailing address, anything else uses the physical address.
				if($address == '2'){
					$table = 'mail_address';
				}
				else{
					$table = 'phy_address';
				}
				$search = "`$table` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "5":
			//Case 5 used for matching the city and is pretty useless on its own. Use 2 for mailing city, anything else uses the physical city.
				if($address == '2'){
					$table = 'mail_city';
				}
				else{
					$table = 'phy_city';
				}
				$search = "`$table` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "6":
			//Case 5 used for matching the state and is really useless on its own. Use 2 for mailing state, anything else uses the physical state.
				if($address == '2'){
					$table = 'mail_state';
				}
				else{
					$table = 'phy_state';
				}
				$search = "`$table` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "7":
			//Case 5 used for matching the zip and is pretty useless on its own. Use 2 for mailing zip, anything else uses the physical zip.
				if($address == '2'){
					$table = 'mail_zip';
				}
				else{
					$table = 'phy_zip';
				}
				$search = "`$table` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "8":
			//Case 8 used for matching the phone number.
				$search = "`phone` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "9":
			//Case 9 used for matching the fax number.
				$search = "`fax` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "10":
			//Case 10 used for matching the email address.
				$search = "`email` $cond[0]$subject$cond[1] $limit";
				return $search;
			break;
			
			case "11":
			//Case 11 used for matching the exact ID.
				$search = "`id`  = '$subject' $limit";
				return $search;
			break;
			
			default:
				$error = "0x1 Invalid search subject";
				return $error;
			break;
		}
	}
	
	private function checkUserPerms($user){
		$var = new user();
		return $var->userPerms($user);
	}
	
	public function clientInfoAll($subject, $type='1', $strict = TRUE){
		$search = $this->stringMatch($subject, $type, $strict);		
		if(!preg_match("/0x1/i",$search)){
			//If there is any kind of error, we don't want to execute any SQL commands. We could have just checked for a global $error, but this won't work if the other methods report errors unrelated to our inquiry.
			$sql = "SELECT * FROM `clients` WHERE $search"; 
			local_connect();
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(mysqli_num_rows($result)>1){
				$clients = array();
				while($row = mysqli_fetch_assoc($result)){
					$clients[$row['id']] = $row;
					unset($clients[$row['id']][$row['id']]);
				}
				return $clients;
			}
			elseif(mysqli_num_rows($result)==1){
				return mysqli_fetch_assoc($result);
			}
			else{
				//$error = "0x1 No results found.";
				return NULL;
			}
			local_disconnect();
		}
	}
	
	public function clientCampaigns($client_number, $sort = '1', $limit = '1'){
		if($limit != '1' AND is_numeric($limit)){
			$limit = "LIMIT $limit";
		}
		elseif($limit == 0){
			$limit = '';
		}
		else{
			$limit = "LIMIT 1";
		}
		if($sort == '2'){
			$sort = 'ASC';
		}
		else{
			$sort = 'DESC';
		}
	
		local_connect();
		$sql = "SELECT `campaign` FROM `client_campaigns` WHERE `client`='$client_number' ORDER BY `campaign` $sort $limit";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			if(mysqli_num_rows($result) > 1){
					$campaigns = array();
				while($row = mysqli_fetch_assoc($result)){
					$campaigns[] = $row['0'];
				}
				return $campaign;
			}
			elseif(mysqli_num_rows($result) == 0){
				//$error = "0x2 No campaigns were found for client $client_number";
				return NULL;
			}
			else{
				$campaign = $row['0'];
				return $campaign;
			}
		}
		else{
			$error = "0x1 Error occured when querying DB: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the clientCampaigns function to ensure all information entered is correct.";
			return $error;
		}
	}
	
	public function add_client($requester_id, $new_client_info){
		/*This method is responsible for adding new clients to the DB. The requester_id is the person doing the adding (manager?). The new_client_info is an associative array with the required information.
		  Check the API manual if you're not sure what the array should look like. (Hint: it's everything that is "NOT NULL" in the DB table clients.)*/
		
		//We first have to check the permission of the person doing the adding. We don't random people adding new clients do we?
		
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['add_client']==1){
					local_connect();
					//Good the requester has permission to add users
					$sql = "INSERT INTO `clients` (`client_number`, `company_name`, `contact`, `phy_address`, `phy_city`, `phy_state`, `phy_zip`, `phone`, `fax`, `email`, `mail_address`, `mail_city`, `mail_state`, `mail_zip`) 
							VALUES ('$new_client_info[client_number]', '$new_client_info[company]', '$new_client_info[contact]', '$new_client_info[phy_address]', '$new_client_info[phy_city]',
								'$new_client_info[phy_state]', '$new_client_info[phy_zip]', '$new_client_info[phone]', '$new_client_info[fax]', '$new_client_info[email]', '$new_client_info[mail_address]', 
								'$new_client_info[mail_city]', '$new_client_info[mail_state]', '$new_client_info[mail_zip]' )";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
						if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1062'){
							$error = "0x1 A client with the client number of $new_client_info[client_number] already exist. Please change the client number and try again.";
							return $error;
						}
						else{
							$error = "0x1 Error adding client: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_client method and ensure you've provided everything correctly.";
						}
					}
					else{
						//In the future we'll log this action in the DB.
						if(!empty($error)){
								  $msg .= $error."\n";
						}
						$msg = $msg."1x1 Client added Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error adding client: You do not have sufficiant permission to add clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding client: You do not have sufficiant permission to add clients. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
	}
	
	public function add_client_campaign($requester_id, $client_number){
		/*This method is responsible for adding new campaigns for the clients to the DB. The requester_id is the person doing the adding (manager?).
		  The client_number is the number of the client we want to add. We'll first check if your client exist, then we'll add the campaign.*/
		
		//We first have to check the permission of the person doing the adding. We don't want random people adding new campaigns.
		
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms)){
			//We got a result
			if(!preg_match("/0x1/i",$perms)){
				//No errors!
				if($perms['add_client']==1){
					
					//Good the requester has permission to add clients. Now lets make sure this client exist.
					//We use a pseudo-Dewey Decimal system. By that, campaigns are just client_number(or name if modified).auto-increment number. We first have to get the
					//new campaign number.
					$next_camp = $this->clientCampaigns($client_number);
					if(!empty($next_camp)){
						if(!preg_match("/0x1/i", $next_camp)){
							$next_camp = explode(".", $next_camp);
							$next_camp = $next_camp['1']+1;
						}
						elseif(preg_match("/0x2/i", $next_camp)){
							$next_camp = '001';
						}
						else{
							$msg .= "0x1 Error adding campaign: $next_camp";
							return $msg;
						}
					}
					else{
						$error = "Something went wrong when trying to locate the next compaign. Please contact your Administration.";
						return $error;
					}
					local_connect();
					$sql = "INSERT INTO `client_campaigns` (`client_number`, `campaign`, `enabled`) VALUES ('$client_number', '$next_camp', 'YES')";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
						if(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1062'){
							$error = "0x1 A campaign with the campaign number of $next_camp already exist. Please contact your Adminsitrator to resolve this issue.";
							return $error;
						}
						elseif(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))=='1452'){
							$error = "0x1 The client you're trying to add a campaign to doesn't exist. You need to add a client before you add a campaign";
							return $error;
						}
						else{
							$error = "0x1 Error adding campaign: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_campaign method and ensure you've provided everything correctly.";
						}
					}
					else{
						//In the future we'll log this action in the DB.
						if(!empty($error)){
								  $msg .= $error."\n";
						}
						$msg .= "1x1 Campaign added Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error adding campagin: You do not have sufficiant permission to add clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding campaign: You do not have sufficiant permission to add clients. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
	}
	
	public function remove_client_campaign($requester_id, $client_campaign){
		/* We don't necessarily delete clients. After all, the client table is only for
		   information purposes; however, we do disable client campaigns (like sub-accounts
		   for each client). A disabled campaign can mean phone calls for the client will fail, so be careful.
		   This method is responsible for disabling campaigns. The requester_id is the person 
		   doing the disabling (manager?). The client_campaign is the client we want to disable.*/
		
		//We first have to check the permission of the requester. We don't need random people disabling clients' campaigns.
		
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms)){
			//We got a result
			if(!preg_match("/0x1/i",$perms)){
				//No errors!
				if($perms['remove_client']==1){
					//Good the requester has permission to remove clients and thus campaigns
					$client = explode(".", $client_campaign);
					$client = $client['0'];
					$sql = "UPDATE `client_campaigns` SET `disbaled`='YES' WHERE `client_number`='$client' AND `campaign`='$client_campaign' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error removing campaign: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the remove_client_campaign method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Campaign removed Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error removing campaign: You do not have sufficiant permission to remove clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error removing campaign: You do not have sufficiant permission to remove clients. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function modify_client($requester_id, $client_number, $new_client_info){
		/*This method is responsible for modify existing client info in the DB. The requester_id is the person doing the modifying (manager?). The client_number is the
		  user we want to modify. The new_client_info is an associative array with the required information. Check the API manual if you're not sure what the array 
		  should look like. (Hint: it's everything that is "NOT NULL" in the DB table clients.)*/
		
		//We first have to check the permission of the person doing the adding.
		
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_client']==1){
					local_connect();
					//Good the requester has permission to modify campaigns
						$sql = "REPLACE INTO `clients` (`client_number`, `company_name`, `contact`, `phy_address`, `phy_city`, `phy_state`, `phy_zip`, `phone`, `fax`, `email`, `mail_address`, 
														`mail_city`, `mail_state`, `mail_zip`) 
								VALUES ('$client_number', '$new_client_info[company]', '$new_client_info[contact]', '$new_client_info[phy_address]', '$new_client_info[phy_city]',
										'$new_client_info[phy_state]', '$new_client_info[phy_zip]', '$new_client_info[phone]', '$new_client_info[fax]', '$new_client_info[email]', '$new_client_info[mail_address]', 
										'$new_client_info[mail_city]', '$new_client_info[mail_state]', '$new_client_info[mail_zip]' )";
						$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if(!$result){
							$error = "0x1 Error modifying client: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_client method and ensure you've provided everything correctly.";
						}
						else{
							//In the future we'll log this action in the DB.
							$msg .= "1x1 Client Modified Successfully.\n";
							return $msg;
						}
				}
				else{
					$error = "0x1 Error modifying client: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error modifying client: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
	}
	
	public function delete_client($requester_id, $client_number='', $client_id=''){
		/*This method is responsible for permanantly deleting clients. The requester_id is the person doing the deleting (manager?). The client_number is the client we want to delete.
		  Note: This method doesn't just delete the client from the clients table, but from all tables tied to the client (campaigns, numbers, queues etc.) with the exception of the CDR. 
		  Use with caution*/
		
		//We first have to check the permission of the requester. We definately don't want random people permanantly deleting clients.
		
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms) && (!empty($client_number) || !empty($client_id))){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['delete_client']==1){
					//Good the requester has permission to delete users
					(!empty($client_number)? $where = "`client_number`='$client_number'" : "");
					(!empty($client_id)? $where = "`id`='$client_id'" : "");
					$sql = "DELETE FROM `clients` WHERE $where LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting client: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the delete_client method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Client Deleted Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error deleting client: You do not have sufficiant permission to permantly delete clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		elseif(preg_match("/0x1/i",$perms)){
				return "0x1 There was an error with your request: $perms";
		}
		else{
			$error = "0x1 Error deleting client: You do not have sufficiant permission to permantly delete clients. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function remove_form($requester_id, $form_id){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_client']==1){
					//Good the requester has permission to delete users
					$sql = "UPDATE `form_assignment` SET `active`='1' WHERE `form_id`='$form_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error disabling form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the remove_form method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Form Disabled Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error disabling form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		elseif(preg_match("/0x1/i",$perms)){
				return "0x1 There was an error with your request: $perms";
		}
		else{
			$error = "0x1 Error disabling form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function delete_form($requester_id, $form_id){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_client']==1){
					//Good the requester has permission to delete users
					$sql = "DELETE FROM `xml_forms` WHERE `id`='$form_id' LIMIT 1";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error = "0x1 Error deleting form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the remove_form method and ensure you've provided everything correctly.";
					}
					else{
						//In the future we'll log this action in the DB.
						$msg = "1x1 Form Deleted Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error deleting form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		elseif(preg_match("/0x1/i",$perms)){
				return "0x1 There was an error with your request: $perms";
		}
		else{
			$error = "0x1 Error deleting form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
			return $error;
		}
	}
	
	public function add_form($requester_id, $new_form){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_client']==1){
					local_connect();
					//Good the requester has permission to add users
					$sql = "INSERT INTO `xml_forms` (`form`, `created`, `creator`, `modifier`, `common_name`) 
							VALUES ('$new_form[client_form_txt]', '".time()."', '$requester_id', '$requester_id', '$new_form[form_name]')";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error .= "0x1 Error adding client form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_form method and ensure you've provided everything correctly.";
					}
					else{
						$form_id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
						if(!empty($form_id)){
							$sql = "INSERT INTO `form_assignment` (`form_id`, `client_id`, `active`) VALUES('$form_id', '$new_form[client]', '$new_form[active]');";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if(!$result){
								$error = "0x1 Error modifying form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the add_form method and ensure you've provided everything correctly.";
								$sql = "DELETE FROM `xml_forms` WHERE `id` = '$form_id';";
								$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							}
							else{
								//In the future we'll log this action in the DB.
								$msg .= "1x1 Form Assignment Successfully.\n";
							}
						}
						else{
							$error .= "0x1 Error: Could not set form assignment to client. Assignment will need to be set manually. Contact your administrator or support desk for additional help.";
						}
						//In the future we'll log this action in the DB.
						if(!empty($error)){
								  $msg .= $error."\n";
						}
						$msg = $msg."1x1 Client form added Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error adding client form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding client form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}
	
	public function modify_form($requester_id, $form_id, $new_form){
		$perms = $this->checkUserPerms($requester_id);
		if(!empty($perms) && is_array($perms)){
			//We got a result
			if(!preg_match("/0x1/i",implode("",$perms))){
				//No errors!
				if($perms['modify_client']==1){
					local_connect();
					//Good the requester has permission to add users
					$sql = "UPDATE `xml_forms` SET `form`='$new_form[client_form_txt]', `last_modified`='".time()."', `modifier`='$requester_id', `common_name`='$new_form[form_name]' WHERE `id` = '$form_id';";
					$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if(!$result){
							$error .= "0x1 Error adding client form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_form method and ensure you've provided everything correctly.";
					}
					else{
						if(!empty($form_id)){
							$sql = "REPLACE INTO `form_assignment` (`form_id`, `client_id`, `active`) VALUES('$form_id', '$new_form[client]', '$new_form[active]');";
							$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if(!$result){
								$error = "0x1 Error modifying form: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))." Check the modify_form method and ensure you've provided everything correctly.";
							}
							else{
								//In the future we'll log this action in the DB.
								$msg .= "1x1 Form Assignment Successfully.\n";
							}
						}
						else{
							$error .= "0x1 Error: Could not set form assignment to client. Assignment will need to be set manually. Contact your administrator or support desk for additional help.";
						}
						//In the future we'll log this action in the DB.
						if(!empty($error)){
								  $msg .= $error."\n";
						}
						$msg = $msg."1x1 Client Form Modified Successfully.";
						return $msg;
					}
				}
				else{
					$error = "0x1 Error adding client form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
					return $error;
				}
			}
			else{
				$error = "0x1 There was an error with your request: $perms";
				return $error;
			}
		}
		else{
			$error = "0x1 Error adding client form: You do not have sufficiant permission to modify clients. Please consult with your Administrator for additional help.";
			return $error;
		}
		local_disconnect();
		if(!empty($error)){
			return $error;
		}
	}



	
//End of the Class	
}

?>