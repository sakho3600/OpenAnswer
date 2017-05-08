<?php
require_once('config.php');
class call_info{
	
	public function agent_channel_local($exten){
		//Currently, there's no way to link the transferred agent channel to the unique_id of the call in queue when using the Local device, as a second unique_id record is generated when the call
		//to the agent is made; so we'll just have to guess.
		local_connect();
		$sql = "SELECT `dstchannel` FROM $db.cdr WHERE `channel` LIKE '%$exten%' ORDER BY `calldate` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve agent channel");
		if (mysqli_num_rows($result) == 1) {
			$agnt_channel = mysqli_fetch_row($result);
			$agnt_channel = $agnt_channel[0];
			return $agnt_channel;
		}
		elseif (mysqli_num_rows($result) == 0){
			return "0x01";
		}
		else{
			return "0x1 Error: More than one result returned. Check the agent_channel function and make sure it wasn't tampered with.";
		}
	}
	
	public function caller_channel($unique_id){
		local_connect();
		$sql = "SELECT `in_trunk` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve callerr channel");
		if (mysqli_num_rows($result) == 1) {
			$caller_channel = mysqli_fetch_row($result);
			$caller_channel = $caller_channel[0];
			return $caller_channel;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the caller_channel function and make sure it wasn't tampered with.";
		}
	}
	
	public function caller_id($unique_id){
		local_connect();
		$sql = "SELECT `caller_id` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve caller ID");
		if (mysqli_num_rows($result) == 1) {
			$caller_id = mysqli_fetch_row($result);
			$caller_id = $caller_id[0];
			return $caller_id;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the caller_id function and make sure it wasn't tampered with.";
		}
	}
	
	public function agent_answer_time($unique_id){
		local_connect();
		$sql = "SELECT `time` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve agent answer time");
		if (mysqli_num_rows($result) == 1) {
			$agent_answer_time = mysqli_fetch_row($result);
			$agent_answer_time = $agent_answer_time[0];
			return $agent_answer_time;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the agent_answer_time function and make sure it wasn't tampered with.";
		}
	}
	
	public function call_hold_time($unique_id){
		local_connect();
		$sql = "SELECT `time` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve call hold time");
		if (mysqli_num_rows($result) == 1) {
			$agent_answer_time = mysqli_fetch_row($result);
			$agent_answer_time = $agent_answer_time[0];
			$unique_id = explode(".",$unique_id);
			$call_hold_time = $agent_answer_time-$unique_id[0];
			return $call_hold_time;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the call_hold_time function and make sure it wasn't tampered with.";
		}
	}
	
	public function number_dialed($unique_id){
		local_connect();
		$sql = "SELECT `number_dialed` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve the original extension dialed");
		if (mysqli_num_rows($result) == 1) {
			$number_dialed = mysqli_fetch_row($result);
			$number_dialed = $number_dialed[0];
			return $number_dialed;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the number_dialed function and make sure it wasn't tampered with.";
		}
	}
	
	public function unique_id($channel){
		local_connect();
		$sql = "SELECT `unique_id` FROM $db.short_cdr WHERE `in_trunk` = '$channel' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve call unique id");
		if (mysqli_num_rows($result) == 1) {
			$unique_id = mysqli_fetch_row($result);
			$unique_id = $unique_id[0];
			return $unique_id;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the unique_id function and make sure it wasn't tampered with.";
		}
	}
	
	public function connected_channel($exten, $offset1 = '+6', $offset2 = '-15'){
		//The only way to get the incoming channel from the queue is to guess the immediate time proximity. Default proximity is 21 seconds.
		local_connect();
		$sql = "SELECT `in_trunk` FROM $db.short_cdr WHERE `agent_channel` LIKE '%$exten%' AND `time` <= (UNIX_TIMESTAMP(NOW())$offset1) AND `time`>= (UNIX_TIMESTAMP(NOW())$offset2) ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve connected channel");
		if (mysqli_num_rows($result) == 1) {
			$in_channel = mysqli_fetch_row($result);
			$in_channel = $in_channel[0];
			return $in_channel;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the unique_id function and make sure it wasn't tampered with.";
		}
	}
	
	public function live_calls(){
		//Because asterisk doesn't post calls to the CDR until they are finished, we can use our short.cdr table to get a list of live calls.
		local_connect();
		$sql = "SELECT `queue`, `id`, `caller_id`, `agent_channel`, `time`, `in_trunk` FROM $db.short_cdr WHERE `in_trunk` NOT IN (SELECT DISTINCT `channel` FROM $db.cdr) AND `in_trunk` != ''";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve live calls");
		if (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			$live_calls = array();
			while($list = mysqli_fetch_row($result)){
				$live_calls[$list['0']] = array("id"=>$list['1'],"caller_id"=>$list['2'],"agent_channel"=>$list['3'],"time"=>$list['4'],"in_trunk"=>$list['5']);
			}	
			return $live_calls;
		}
	}
	
	public function live_calls_onhold(){
		local_connect();
		$sql = "SELECT `queue_name` FROM $db.queues";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve live calls");
		if (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			$queue_detail = array();
			$obj = new queue_detail();
			while($list = mysqli_fetch_row($result)){
				$queue_detail[] = $obj->queue_info($list['0']);
				//if(!is_array($queue_details)){ return "0x1 ".implode("",$queue_detail); break; }
				$obj->new_queue_detail();

			}
			return $queue_detail;
		}
	}
	
	public function call_queue($unique_id){
		local_connect();
		$sql = "SELECT `queue` FROM $db.short_cdr WHERE `unique_id`='$unique_id' ORDER BY `id` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve original queue");
		if (mysqli_num_rows($result) == 1) {
			$queue = mysqli_fetch_row($result);
			$queue = $queue[0];
			return $queue;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the call_queue function and make sure it wasn't tampered with.";
		}
	}
	
	public function duration($unique_id){
		local_connect();
		$sql = "SELECT `duration` FROM $db.cdr WHERE `uniqueid`='$unique_id' OR `accountcode`='$unique_id' ORDER BY `calldate` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve total call duration");
		if (mysqli_num_rows($result) == 1) {
			$duration = mysqli_fetch_row($result);
			$duration = $duration[0];
			return $duration;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the duration function and make sure it wasn't tampered with.";
		}
	}
	
	public function billsec($unique_id){
		local_connect();
		$sql = "SELECT `billsec` FROM $db.cdr WHERE `uniqueid`='$unique_id' OR `accountcode`='$unique_id' ORDER BY `calldate` DESC LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve actual talk time");
		if (mysqli_num_rows($result) == 1) {
			$billsec = mysqli_fetch_row($result);
			$billsec = $billsec[0];
			return $billsec;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the billsec function and make sure it wasn't tampered with.";
		}
	}

	public function callInfoAll($id){
		local_connect();
		$sql = "SELECT * FROM $db.short_cdr WHERE `id`='$id' LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Query failed. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))."Could not retrieve total call duration");
		if (mysqli_num_rows($result) == 1) {
			$info = mysqli_fetch_row($result);
			$info = $info[0];
			return $info;
		}
		elseif (mysqli_num_rows($result) == 0){
			return NULL;
		}
		else{
			return "0x1 Error: More than one result returned. Check the callInfoAll function and make sure it wasn't tampered with.";
		}
	}
	
}


?>