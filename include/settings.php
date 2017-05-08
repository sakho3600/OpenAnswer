<?php
require("config.php");
if(isset($_GET['phone']) AND $_GET['phone']==1){
		$phone = $_SESSION['extention'];
}
if(isset($_GET['phone']) AND $_GET['phone']==2){
		$phone = $_SESSION['forward_num'];
}

if(isset($_GET['logon']) AND $_GET['logon'] ==1){
		$oSocket = fsockopen($ast_server, $ast_port, $errnum, $errdesc) or die("Connection to host failed");
		sleep(1);
		fwrite($oSocket, "Action: login\r\n");
		fwrite($oSocket, "Username: $ast_user\r\n");
		fwrite($oSocket, "Secret: $ast_pass\r\n\r\n");
		$buffer= fread($oSocket,1024);
		//fwrite($oSocket, "Events: off\r\n\r\n");
		fwrite($oSocket, "Action: QueueAdd\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: Local/$phone@$ast_context\r\n");
		fwrite($oSocket, "Penalty: 1\r\n\r\n");
		fwrite($oSocket, "Paused: false\r\n\r\n");
		fwrite($oSocket, "Action: Logoff\r\n\r\n");
		$buffer .= fread($oSocket, 1024);
		fclose($oSocket);
}
if(isset($_GET['logoff']) AND $_GET['logoff'] ==1){
		$oSocket = fsockopen($ast_server, 5038, $errnum, $errdesc) or die("Connection to host failed");
		sleep(1);
		fwrite($oSocket, "Action: login\r\n");
		fwrite($oSocket, "Username: $ast_user\r\n");
		fwrite($oSocket, "Secret: $ast_pass\r\n\r\n");
		$buffer= fread($oSocket,1024);
		fwrite($oSocket, "Events: off\r\n\r\n");
		fwrite($oSocket, "Action: QueueRemove\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: Local/$_SESSION[forward_num]@default\r\n");
		fwrite($oSocket, "\r\n");
		fwrite($oSocket, "Action: QueueRemove\r\n");
		fwrite($oSocket, "Queue: $ast_queue\r\n");
		fwrite($oSocket, "Interface: Local/$_SESSION[extention]@$ast_context\r\n");
		fwrite($oSocket, "Action: Logoff\r\n\r\n");
		$buffer .= fread($oSocket, 1024);
		fclose($oSocket);
}
?>