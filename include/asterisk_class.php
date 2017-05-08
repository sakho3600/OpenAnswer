<?php
require_once('config.php');
class asterisk{
	protected function ast_login($ast_server='', $ast_port=''){
		if(empty($ast_server)|| empty($ast_port)){
			global $ast_server, $ast_port;
		}
		global $ast_user, $ast_pass, $ast_queue, $ast_context, $ast_log, $oSocket, $buffer;
		$oSocket = fsockopen($ast_server, $ast_port, $errnum, $errdesc) or die(display::error_msg("Connection to phone system failed. Ensure that you have setup your config file correctly and your phones are working."));
		//sleep(1);
		fwrite($oSocket, "Action: login\r\n");
		fwrite($oSocket, "Username: $ast_user\r\n");
		fwrite($oSocket, "Secret: $ast_pass\r\n\r\n");
		fwrite($oSocket, "Action: Events\r\n");
		fwrite($oSocket, "EventMask: off\r\n\r\n");
		//$buffer = fgets($oSocket);
		//$buffer = '';
		stream_set_timeout($oSocket,0,300);
	}
	public function agent_login($exten, $ast_queue, $device = 'Local', $ast_context = 'default'){
		global $oSocket, $buffer;
		if(!empty($ast_context)){ $ast_context = "@".$ast_context; }
		$this->ast_login();
		fwrite($oSocket, "Action: QueueAdd\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: $device/$exten$ast_context\r\n");
		fwrite($oSocket, "Penalty: 1\r\n\r\n");
		fwrite($oSocket, "Paused: false\r\n\r\n");
		$this->ast_logout();
	}
	
	public function agent_logoff($exten, $ast_queue, $device = 'Local', $ast_context = 'default'){
		global $oSocket, $buffer;
		if(!empty($ast_context)){ $ast_context = "@".$ast_context; }
		$this->ast_login();
		fwrite($oSocket, "Action: QueueRemove\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: $device/$exten$ast_context\r\n");
		$this->ast_logout();
	}
	
	public function agent_pause($exten, $ast_queue, $device = 'Local', $ast_context = 'default', $pause=''){
		global $oSocket, $buffer;
		if(!empty($ast_context)){ $ast_context = "@".$ast_context; }
		$this->ast_login();
		fwrite($oSocket, "Action: QueuePause\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: $device/$exten$ast_context\r\n");
		if($pause == '1'){
			fwrite($oSocket, "Paused: true\r\n");
		}
		if($pause == '0'){
			fwrite($oSocket, "Paused: false\r\n");
		}
		$this->ast_logout();
	}	
	protected function ast_logout($success=0){
		global $oSocket, $buffer;
		if($success = 1){
			$streaming = TRUE;
			do{
				$buffer .= fread($oSocket,4096);
				if(substr($buffer,-4)=="\r\n\r\n"){
					$streaming=FALSE;
				}
			}
			while($streaming);
			$response = explode("\r\n",$buffer);
			foreach($response as $key => $value){
				$line = explode(":", $value);
				if($line[0] == 'Response' && trim($line[1]) == 'Success'){
					$worked = TRUE;
				}
			}
			if($worked != TRUE){
				$worked = FALSE;
			}
		}
		fwrite($oSocket, "Action: logoff\r\n\r\n");
		fclose($oSocket);
		if($success = 1){
			return $worked;
		}
	}
	
	public function park_call($caller_chnl, $agent_chnl, $timout = '180000'){
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: Park\r\n");
		fwrite($oSocket, "Channel: $caller_chnl\r\n");
		fwrite($oSocket, "Channel2: $agent_chnl\r\n");
		fwrite($oSocket, "Timeout: $timeout\r\n");
		fwrite($oSocket, "ActionID: ".md5($caller_chnl."+-*/".$exten)."\r\n");
		return $this->ast_logout(1);
	}
	
	public function transfer($caller_chnl, $exten, $context = 'default', $priority = '1'){
		(empty($context)?$context = 'default':NULL);
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: Redirect\r\n");
		fwrite($oSocket, "Channel: $caller_chnl\r\n");
		fwrite($oSocket, "Context: $context\r\n");
		fwrite($oSocket, "Exten: $exten\r\n");
		fwrite($oSocket, "Priority: $priority\r\n");
		fwrite($oSocket, "ActionID: ".md5($caller_chnl."+-*/".$exten)."\r\n");
		return $this->ast_logout(1);
	}
	
	public function record_call($caller_chnl, $file_name = '', $format = 'wav'){
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: Monitor\r\n");
		fwrite($oSocket, "Channel: $caller_chnl\r\n");
		if(!empty($file_name)){
			fwrite($oSocket, "Filename: $file_name\r\n");
		}
		fwrite($oSocket, "Format: $format\r\n");
		fwrite($oSocket, "Mix: true\r\n");
		fwrite($oSocket, "ActionID: ".md5($caller_chnl."+-*/")."\r\n");
		$this->ast_logout();
	}
	
	public function call_barge($spy_ext, $listener_ext, $call_channel, $listener_cntx = 'barge-in', $listener_id = '00000'){
		local_connect();
		$sql = "INSERT INTO call_barge (`userid`, `usr_ext`, `spy_channel`, `time`, `unique_id`) VALUES ('$listener_id', '$listener_ext', '$call_channel', '".time()."', '".md5($spy_ext.md5($call_channel))."')";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			global $oSocket, $buffer;
			$this->ast_login();
			fwrite($oSocket, "Action: originate\r\n");
			fwrite($oSocket, "Channel: $listener_ext\r\n");
			fwrite($oSocket, "WaitTime: 10000\r\n");
			fwrite($oSocket, "Exten: $spy_ext".md5($call_channel)."\r\n");
			fwrite($oSocket, "Context: $listerner_cntx\r\n");
			fwrite($oSocket, "Async: true\r\n");
			fwrite($oSocket, "Priority: 1\r\n\r\n");
			$this->ast_logout();
		}
		else{
			echo "Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "Failed to record barge in DB.";
		}
		local_disconnect;
	}
	
	public function park_call_bug($callee_chan, $caller_chan, $park_ext, $context, $priority='1', $timout = '180000', $agent_id = '00001', $queue = ''){
		local_connect();
		$sql = "INSERT INTO call_park (`user_id`, `callee_chan`, `caller_chan`, `timeout`, `orig_queue`, 'time', `unique_id`) VALUES ('$agent_id', '$callee_chan', '$caller_chan', '$timeout', '$queue', '".time()."', '".md5($park_ext.md5($caller_channel))."')";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			global $oSocket, $buffer;
			$this->ast_login();
			fwrite($oSocket, "Action: Redirect\r\n");
			fwrite($oSocket, "Channel: $caller_chan\r\n");
			fwrite($oSocket, "Context: $context\r\n");
			fwrite($oSocket, "Exten: $park_ext".md5($caller_chan)."\r\n");
			fwrite($oSocket, "Priority: $priority\r\n");
			fwrite($oSocket, "ActionID: ".md5($caller_chan."+-*/".$callee_chan)."\r\n");
			$this->ast_logout();
		}
		else{
			echo "Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "Failed to record park in DB.";
		}
		local_disconnect();
	}
	
	public function channel_detail ($channel){
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: Status\r\n");
		fwrite($oSocket, "Channel: $channel\r\n");
		fwrite($oSocket, "ActionID: ".md5(time().$channel)."\r\n\r\n");
		$streaming = TRUE;
		do{
			$buffer .= fread($oSocket,4096);
			if(substr($buffer,-4)=="\r\n\r\n"){
				$streaming=FALSE;
			}
		}
		while($streaming);
		$response = explode("\r\n",$buffer);
		$this->ast_logout();
		$channel_detail = array();
		foreach($response as $key => $value){
			$line = explode(":", $value);
			if($line[0] == 'Event' && trim($line[1]) == 'Status'){
				$rd = 1;
			}
			if($line[0] == 'Uniqueid' /*&& trim($line[1]) == 'StatusComplete'*/){
				$rd = 0;
			}
			if($rd == 1){
				$channel_detail[$line[0]] = trim($line[1]);
			}
		}
		return $channel_detail;
	}
	
	public function create_meetme( $caller_chan, $callee_chan, $context, $ext, $priority = '1', $user_id = '00001' ){
		local_connect();
		$sql = "INSERT INTO axfer (`user_id`, `caller_chan`, `callee_chan`, `meetme_room`, `time`, `unique_id`, `status`) VALUES ('$user_id', '$caller_chan', '$callee_chan', '".md5($caller_chan)."', '".time()."', '".md5($ext.md5($caller_chan))."', 'WAIT')";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$this->transfer($caller_chan, $ext.md5($caller_chan), $context, $priority );
		}
		else{
			echo "Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "Failed to record meetme room in DB.";
		}
		local_disconnect();
	}
	
	public function join_meetme( $chan, $ext, $room, $context, $priority = '1'){
		local_connect();
		$sql = "UPDATE axfer SET `third_party`='$chan' WHERE `meetme_room`='$room'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$this->transfer($chan, $ext."-".$room, $context, $priority );
		}
		else{
			echo "Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "Failed to record third-party to meetme room in DB.";
		}
		local_disconnect();
	}
	
	public function hangup( $chan, $time = '1' ){
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: AbsoluteTimeout\r\n");
		fwrite($oSocket, "Channel: $chan\r\n");
		fwrite($oSocket, "Timeout: $time\r\n");
		fwrite($oSocket, "ActionID: ".md5(time().$chan)."\r\n\r\n");
		$this->ast_logout();	
	}
	
	public function orginate_return( $channel, $ext, $tech, $caller_ID = '', $user_id = '00001', $timeout='30'){
		global $oSocket, $buffer;
		//Asterisk doesn't return the channel with id if the Originate cmd is used, so we'll have to use the DB and an AGI script. In order for this to work, the technology needs to be specified without
		//trailing slash (e.g. IAX2/trunk).
		//Note that the Channel shouldn't be an outside line, it will just return the wrong channel.
		local_connect();
		$sql = "INSERT INTO originate (`channel`, `tech`, `exten`, `timeout`, `call_id`, `unique_id`, `user_id`) VALUES ('$channel', '$tech', '$ext', '$timeout', '$caller_ID', '".md5($channel)."', '$user_id')";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$this->ast_login();
			fwrite($oSocket, "Action: Originate\r\n");
			fwrite($oSocket, "Channel: $channel\r\n");
			fwrite($oSocket, "Application: AGI\r\n");
			fwrite($oSocket, "Data: originate_return.php|$channel\r\n");
			$this->ast_logout();
			sleep(3);
			$sql = "SELECT `channel` FROM $db.originate WHERE `unique_id` = '".md5($channel)."'";
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			if(mysqli_num_rows($result)==1){
				$row = mysqli_fetch_row($result);
				$link = $this->channel_detail($row[0]);
				$link = $channel['Link'];
			}
			else{
				$link = 'UNAVAILBLE';
			}
		}
		else{
			echo "Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "Failed to record originate to DB.";
		}
		local_disconnect();
		return $link;
	}
		
	public function originate($channel, $ext, $context, $priority, $caller_ID = '', $app = '', $data = '', $user_id = '00001'){
		global $oSocket, $buffer;
		$this->ast_login();
		fwrite($oSocket, "Action: Originate\r\n");
		fwrite($oSocket, "Channel: $channel\r\n");
		if(!empty($app) && empty($ext) && empty($context) && empty($priority)){
			fwrite($oSocket, "Application: $app\r\n");
			fwrite($oSocket, "Data: $data\r\n");
		}
		else{
			fwrite($oSocket, "Context: $context\r\n");
			fwrite($oSocket, "Exten: $ext\r\n");
			fwrite($oSocket, "Priority: $priority\r\n");
			if(!empty($caller_ID)){
				fwrite($oSocket, "CallerID: $caller_ID\r\n");
			}
		}
		$this->ast_logout();
	}
	
	public  function __destruct() {
		//$this->ast_logout();
		return NULL;
	}
}

?>