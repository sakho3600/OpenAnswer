<?php

class cdr_search{

	private function cdr_col($num){
		switch($num){
			case 1: return 'uniqueid'; break;
			case 2: return 'clid'; break;
			case 3: return 'src'; break;
			case 4: return 'dst'; break;
			case 5: return 'dcontext'; break;
			case 6: return 'channel'; break;
			case 7: return 'dstchannel'; break;
			case 8: return 'lastapp'; break;
			case 9: return 'lastdata'; break;
			case 10: return 'disposition'; break;
			case 11: return 'accountcode'; break;
			case 12: return 'userfield'; break;
			case 13: return 'calldate'; break;
			case 14: return 'duration'; break;
			case 15: return 'billsec'; break;
		}
	}
	
	private function orderMatch($column){
		if(is_array($column)){
			$commas = count($column)-1;
			foreach($column as $key => $val){
				if($val == 1){ $sort = "DESC"; } else{ $sort = "ASC"; }
				if($commas == 0){ $comma = ''; } else{ $comma = ','; $commas--; }
				$col = $this->cdr_col($key);
				if(!empty($col)){
					$order = $order." `$col` $sort $comma";
				}
				else{
					return NULL;
				}
			}
			return $order;
		}
		else{
			return NULL;
		}
	}
			
	
	private function stringMatch($subject, $type = '1', $strict = TRUE, $not = 0, $option = 0){
		if($strict == FALSE){
			$cond = array();			
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
		if($not = '1'){
			if(preg_match("/=/",$cond[0])){
				$cond[0] .= '!';
			}
			else{
				$cond[0] .= "NOT ";
			}
		}
		if($option == 1){
			$option = ' AND';
		}
		elseif($option == 2){
			$option = ' OR';
		}
		else{
			$option = '';
		}
	
		switch($type){
			
			case "1":
			//Case 1: used for searching for uniqueIDs. This is the best way to match records verbatim.
					$search = "`uniqueid` $cond[0]$subject$cond[1] $option ";
			break;
			
			case "2":
			//Case 2: used for searching by callerID. Because Asterisk doesn't do the best job formating callerID, we'll have to do a wildcard search;
			//this is actually the worst way to search the cdr.
				$search = "`clid` LIKE '%$subject$cond[1]%' $option ";
				return $search;
			break;
			
			case "3":
			//Case 3: used for searcing with the src. This is usually just the callerID number, but may also
			//be different if the call was transferred via SIP or IAX channels.
				$search = "`src` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "4":
			//Case 4: used for matching the destination extension. Not always useful except for IVR dropout searching, but here for future uses.
				$search = "`dst` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "5":
			//Case 5: used for matching what context the destination extension resides in the dialplan. Makes searching a little easier for those
			//who set up the same extensions in different context to do different things.
				$search = "`dcontext` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "6":
			//Case 6: used for matching the incomming or calling channel. Channel will often be tech/peer context.
			//If you need to identify inbound calls as the CDR doesn't differentiate between inbound and outbound,
			//search for dstcontext and channel for tech/inbound peer name.
				$search = "`channel` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "7":
			//Case 7: used for matching the destination channel. This can either be who took the call, or what outbound provider (trunk) was used to make
			//the call. This can be IP addresses too if you've setup your dialplan in such a way.
				$search = "`dstchannel` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "8":
			//Case 8: used for matching the last application that was executing on the channel. This is good for AGI, queues
			//and blindly locating where your caller dropped out of in the context, but is useless for anything else.
				$search = "`lastapp` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "9":
			//Case 9: used for matching the last data that was sent to the last application. Provides the details for what's passed to queues and scripts and
			//what audio files are annoying your callers.
				$search = "`lastdata` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "10":
			//Case 10: used for matching the call disposition. The dispositions are ANSWERED, NO ANSWER, FAILED, BUSY. Unfortunatly, any inbound call that asterisk presents an IVR to is
			//answered making this portion of the CDR useless for really digging into your statistics. In the near future, we'll give you an AGI script that
			//can provide a better dispositioning system. The FAILED disposition is good for seeing what scripts are failing.
				$search = "`diposition` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;
			
			case "11":
			//Case 11: used for matching the account code assigned to the ${CDR(accountcode)} variable. By default, this variable isn't used by OpenAnswer at all, but this is an open source
			//project, so at least the option is there.
				$search = "`accountcode` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;			
			
			case "12":
			//Case 12: used for matching the account code assigned to the ${CDR(userfield)} variable. By default, this variable isn't used by OpenAnswer at all, but this is an open source
			//project, so at least the option is there.
				$search = "`userfield` $cond[0]$subject$cond[1] $option ";
				return $search;
			break;			
			
			case "13":
			//Case 13: used for matching call date ranges. The $subject needs to be a non-associative array with the minimum time at index 0 and max time at index 1.
			//Times need to be standard epoch time to make searching easier and more predictable with varying timezones.
				if(is_array($subject) && $this->numericArray($subject)){
					$search = "`calldate` > FROM_UNIXTIME($subject[0]) AND `calldate` < FROM_UNIXTIME($subject[1]) $option ";
					return $search;
				}
				else{
					return "`calldate` = '".crypt(md5(time()), md5(rand(5,pow(10,100))));
				}
			break;			
			
			case "14":
			//Case 14 used for matching total call durations (total time spent in IVRs, queues, hold, etc.) in seconds. The $subject needs to be a non-associative array with the minimum time at index 0 and max time at index 1.
				if(is_array($subject) && $this->numericArray($subject)){
					$search = "`duration` > $subject[0] AND `duration` < $subject[1] $option ";
					return $search;
				}
				else{
					return "`calldate` = '".crypt(md5(time()), md5(rand(5,pow(10,100))));
				}
			break;			
			
			case "15":
			//Case 15 used for matching call billable seconds (actual time spent talking to someone). The $subject needs to be a non-associative array with the minimum time at index 0 and max time at index 1.
				if(is_array($subject) && numericArray($subject)){
					$search = "`billsec` > $subject[0] AND `billsec` < $subject[1] $option ";
					return $search;
				}
				else{
					return "`calldate` = '".crypt(md5(time()), md5(rand(5,pow(10,100))));
				}
			break;
			
			default:
				$error = "0x1 Invalid search subject";
				return $error;
			break;
		}
	}
	
	private function numericArray($array){
		if(is_array($array)){
			foreach($array as $value){
				if(is_numeric($value)){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
		}
		else{
			return FALSE;
		}
	}
	
	public function last_call($subject, $inbound='1', $type='7'){
		if(!is_array($subject)){
			if($type == '7'){
				//search by channel
				if($inbound == 1){ $var = 7; } else{ $var = 6; }
				$test = explode("/", $subject);
				if(!empty($test)){
					$controls = $this->stringMatch($subject, $var, FALSE);
					if(!preg_match("/0x1/i", $controls)){
						$controls = str_replace("LIMIT 1", "",$controls);
						$sql = "SELECT * FROM `cdr` WHERE $controls ORDER BY `calldate` DESC LIMIT 1";
					}
					else{
						return $controls;
					}
				}
				else{
					$error = "0x1 Error: Type 7 calls require channel name in tech/context format.";
				}
			}	
			else{
				if(is_numeric($type)){
					$controls = $this->stringMatch($subject, $var, FALSE);
					if(!preg_match("/0x1/i", $controls)){
						$controls = str_replace("LIMIT 1", "",$controls);
						$sql = "SELECT * FROM `cdr` WHERE $controls ORDER BY `calldate` DESC LIMIT 1";
					}
					else{
						return $controls;
					}
				}
				else{
					$error = "0x1 Error: type control is numeric only. Please refer to the API manual for proper usage of the last_call method.";
				}
			}
		}
		else{
			if( numericArray($subject) && ($type <=15 && $type >= 13)){
				$controls = $this->stringMatch($subject, $type, FALSE);
				if(!preg_match("/0x1/i", $controls)){
					$controls = str_replace("LIMIT 1", "",$controls);
					$sql = "SELECT * FROM `cdr` WHERE $controls ORDER BY `calldate` DESC LIMIT 1";
				}
				else{
						$error = $controls;
					}
				}
			else{
				$error = "0x1 Error: subject arrays and type controls are numeric only. Please refer to the API manual for proper usage of the last_call method.";
			}
			
		}
		if(empty($error)){
			local_connect();
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if($result){
				if(mysqli_num_rows($result)==1){
					return mysqli_fetch_assoc($result);
				}
				elseif(mysqli_num_rows($result)==0){
					return NULL;
				}
				else{
					return "0x1 Error: DB query has been tampered with.";
					exit();
				}
			}
			else{
				return "0x1 Error retrieving last call. ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}
		}
		else{
			return $error;
		}
	}
	
	public function get_records($subject, $orderby='1'){
		//If athe subject is an array, then we're assuming that a search by numerical values are intended. Else, we're searching for a string.
		//The type of search will give us our limitations and providing ordering rules.
		if(is_array($subject)){
			$construct = array();
			foreach($subject as $val){
				if(is_array($val)){
					if(isset($val['string']) && isset($val['index']) && isset($val['not']) && isset($val['strict'])){
						if(isset($val['option']) && is_numeric($val['option'])){ $option = $val['option']; } else{ $option = '0'; }
						if(is_array($val['string']) && isset($val['string']['min']) && isset($val['string']['max'])){
							$test = $this->stringMatch(array((int)$val['string']['min'],(int)$val['string']['max']), (int)$val['index'], $val['strict'], (int)$val['not'], $option);					
							if(!preg_match("/0x1/i", $test)){
								$construct[] = $test;
							}
							else{
								$error = $test;
							}
						}
						else{
							$test = $this->stringMatch($val['string'], $val['index'], $val['strict'], $val['not'], $option);
							if(!preg_match("/0x1/i", $test)){
								$construct[] = $test;
							}
							else{
								$error = $test;
							}
						}
					}
					else{
						$error = "0x1 Error: Not all paramaters found in search array. Please refer to the API manual for proper usage of the get_records method.";
					}
				}
				else{
					$error = "0x1 Error: Not all paramaters found in search array. Please refer to the API manual for proper usage of the get_records method.";
				}
			}
		}
		else{
			$error = "0x1 Error: Not all paramaters found in search array. Please refer to the API manual for proper usage of the get_records method.";
		}
		if(is_array($orderby)){
			foreach($orderby as $key=> $val){
				if(is_numeric($key) && is_numeric($val)){
							$order[$key] = $val;
				}
				else{
					$error = "0x1 Error: Incorrect paramaters found in sort array. Please refer to the API manual for proper usage of the get_records method.";
				}
			}
			if(empty($error)){
				$order = $this->orderMatch($order);
			}
		}
		elseif($orderby == '1'){
			$order = $this->orderMatch(array('13'=>'1'));
		}
		else{
			$error = "0x1 Error: Not all paramaters found in array. Please refer to the API manual for proper usage of the get_records method.";
		}
		if(is_array($limit) && isset($limit['min']) && isset($limit['max']) && is_int($limit['min']) && is_int($limit['max'])){ 
			if($limit['min'] == 0){
				$totR = TRUE;
			}
			$limit = "LIMIT $limit[min], $limit[max]";
		}
		else{
			$limit = "LIMIT 1";
		}
		if(empty($error) && !empty($contruct) && !empty($order)){
			if(is_array($construct)){
				foreach($construct as $val){
					$search = $search.$val;
				}
			}
			else{
				return "0x1 Error: Incorrect paramaters found in search array. Please refer to the API manual for proper usage of the get_records method.";
			}
			if(!empty($order)){ $sort = "ORDER BY $order "; } else{ $sort = ''; }
			$sql = "SELECT * FROM `cdr` WHERE $search $sort $limit";
			local_connect();
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if($result){
				if(mysqli_num_rows($result) > 0){
					if($totR == TRUE){ $_SESSION['cdr_total'] = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT FOUND_ROWS();");}
					$cdr = array();
					while($row = mysqli_fetch_assoc($result)){
						$cdr[] = $row;
					}
					return $cdr;
				}
				elseif(mysqli_num_rows($result)==0){
					return NULL;
				}
				else{
					return "0x1 Error: DB query has been tampered with.";
					exit();
				}
			}
			else{
				return "0x1 Error retrieving cdr. ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}
		}
		else{
			return $error;
		}
	}
	
	public function get_cdr_all(){
		local_connect();
		$sql = "SELECT UNIX_TIMESTAMP(`calldate`) as `time`, `clid`, `dstchannel`, `duration`, `billsec`, `disposition` FROM cdr WHERE `lastdata` LIKE '%variables_q%'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$rows[] = $row;
			}
			return $rows;
		}
		else{
			return "0x1 Error retrieving CDR. ".((is_object($Globals["___mysali_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
	}	
	
//End of class
}
?>