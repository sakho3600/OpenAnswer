<?php

class queue_detail extends asterisk{
	
	public function queue_info($queue = ''){
		$this->ast_login();
		global $oSocket, $buffer, $queue_detail;
		fwrite($oSocket, "Action: QueueStatus\r\n");
		if(!empty($queue)){
			fwrite($oSocket, "Queue: $queue\r\n");
		}
		fwrite($oSocket, "ActionID: ".md5(time())."\r\n\r\n");
		//Code below borrowed from the asterisk-php-api project here: http://code.google.com/p/asterisk-php-api/
		$streaming = TRUE;
		do{
			$buffer .= fread($oSocket,4096);
			if(substr($buffer,-4)=="\r\n\r\n"){
				$streaming=FALSE;
			}
		}
		while($streaming);
		//End of borrowed code
		
		//if(strpos($buffer, "Queue")){ echo "0x1 Failed to retrieve response. Please try again."; }
		$response = explode("\r\n",$buffer);
		$this->ast_logout();
		foreach($response as $key => $value){
			$line = explode(":", $value);
			switch($line[0]){
				case "Event":
					switch(trim($line[1])){
						case "QueueParams":
						$nxt_que =1;
						break;
						
						case "QueueMember":
						$nxt_member = 1;
						break;
						
						case "QueueEntry":
						$nxt_call = 1;
						break;
					}
				break;
			}
			if($nxt_que == 1){
				switch($line[0]){
					case 'Queue':
						if(!is_array($queue_detail)){
							$queue_detail = array();
						}
						$queue_detail[trim($line[1])] = array();
						$current_que = trim($line[1]);
					break;
					
					case 'ActionID':
						$nxt_que = NULL;
					break;
					
					default:
						$queue_detail[$current_que][$line[0]] = trim($line[1]);
					break;
				}
			}
			if($nxt_member == 1){
				switch($line[0]){
					case 'Queue':
						if(trim($line[1])==$current_que){
							$queue_detail[$current_que]['Members'] = array();
							$working_queue = $current_que;
						}
						else{
							if(!is_array($queue_detail[trim($line[1])])){
								$queue_detail[trim($line[1])] = array();
								$queue_detail[trim($line[1])]['Members'] = array();
							}
							else{
								$queue_detail[trim($line[1])]['Members'] = array();
							}
							$working_queue = trim($line[1]);
						}
					break;
					
					case 'Name':
						NULL;
					break;
					
					case 'Location':
						$queue_detail[$working_queue]['Members'][trim($line[1])] = array();
						$agent = trim($line[1]);
					break;
										
					case 'ActionID':
						$nxt_member = NULL;
					break;
					
					default:
						$queue_detail[$working_queue]['Members'][$agent][$line[0]] = trim($line[1]);
					break;
				}
			}
			if($nxt_call == 1){
				switch($line[0]){
					case 'Queue':
						if(trim($line[1])==$current_que){
							if(!is_array($queue_detail[$current_que]['Callers'])){
								$queue_detail[$current_que]['Callers'] = array();
							}
							$working_queue = $current_que;
						}
						else{
							if(!is_array($queue_detail[$line[1]])){
								$queue_detail[trim($line[1])] = array();
								$queue_detail[trim($line[1])]['Callers'] = array();
							}
							else{
								if(!is_array($queue_detail[trim($line[1])]['Callers'])){
									$queue_detail[trim($line[1])]['Callers'] = array();
								}
							}
							$working_queue = trim($line[1]);
						}
					break;

					case 'Position':
						$queue_detail[$working_queue]['Callers'][trim($line[1])] = array();
						$position = trim($line[1]);
					break;
					
					case 'ActionID':
						$nxt_call = NULL;
					break;
					
					default:
						$queue_detail[$working_queue]['Callers'][$position][$line[0]] = trim($line[1]);
						unset($queue_detail[$working_queue]['Callers'][NULL]);
					break;
				}
			}
		}
		if(is_array($queue_detail)){
			unset($queue_detail[NULL]);
		}/*
		else{
			$queue_detail = $this->queue_info($queue);
		}*/
		return $queue_detail;
	}
	
	public function new_queue_detail(){
		unset($GLOBALS['queue_detail']);
		
	}
	
	private function remove_null_arrays($arr){
		foreach($arr as $key=>&$val){
			if($key != ""){
				if(is_array($val)){
					$val = self::remove_null_arrays($val);
				}
			}
			else{
				unset($arr[key($arr)]);
			}
			echo gettype($key)." ".key($arr)."<br />";
		}
	}
	
				
}

?>