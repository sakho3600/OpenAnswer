<?php

if(isset($_POST)){
	$_POST = sanitize_data::sanitize_array($_POST);
}
if(isset($_GET)){
	$_GET = sanitize_data::sanitize_array($_GET);
}
//require('include/functions.php');
if(isset($_POST['lgn'])){ 
	if(isset($_POST['user_name']) && isset($_POST['pword'])){
		if($uInfo = action::login($_POST['user_name'], md5($_POST['pword']))){
			if($uInfo['disabled'] != 1){
				$_SESSION['login'] = true;
				$_SESSION['user_id'] = $uInfo['user_id'];
				$_SESSION['name'] = $uInfo['name'];
				$_SESSION['extension'] = $uInfo['channel'];
				$_SESSION['channel'] = $uInfo['tech'];
				$_SESSION['time'] = time();
				$sid = session_id();
				set_user_details();
			}
			else{
				$msg = 'Your login has been disabled. Please contact your supervisor for additional help.';
			}
		}
		else{
			$msg = 'Please input your correct user name and password. If you do not know your user name or have forgotten it or your password, please contact your supervisor as soon as possible.';
		}
	}	
}

if(isset($_GET['phone']) AND $_GET['phone']==1){
		$phone = $_SESSION['extention'];
}
if(isset($_GET['phone']) AND $_GET['phone']==2){
		$phone = $_SESSION['forward_num'];
}

if(isset($_GET['logoff']) AND $_GET['logoff'] ==1){
	$_SESSION['active'] === TRUE ? action::logout_of_queues():NULL;
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	session_unset();
	session_destroy();
	header("Location: index.php");
}
if(isset($_GET['ajax']) && $_GET['ajax']=='1'){
	if(!isset($_SESSION['key'])){
		if(!isset($_SESSION['login']) || $_SESSION['login'] != "True"){
			header("Location: ".currentURL(1)."/index.php");
		}
	}
	$obj = new user();
	$perms = $obj->userPerms($_SESSION['user_id']);
	if(!isset($_GET['takeCalls'])){
		if($perms[page::get_perm(AJAX_PAGE)]==1){
			include(page::get_ajax_page_file(AJAX_PAGE));
			exit();
		}
		else{
			echo display::error_msg(1);
			exit();
		}
	}
	else{
		if(isset($_GET['getForm'])){
			$obj = new get_form;
			$guts = html_entity_decode($obj->get_forms($_SESSION['channel'], $_SESSION['extension']),ENT_QUOTES);
			echo (!empty($guts) ? display::build_form($guts):NULL);
			exit();
		}
		if(isset($_GET['hangUp'])){
			action::hangUpCall($_SESSION['connectedCall']['callerChan']);
			exit();
		}
		if(isset($_GET['pause'])){
			action::pauseAgent(1,$_SESSION['user_id'],$_SESSION['extension'],$_SESSION['channel']);
			exit();
		}
		if(isset($_GET['unpause'])){
			action::pauseAgent(0,$_SESSION['user_id'],$_SESSION['extension'],$_SESSION['channel']);
			exit();
		}
		if(isset($_GET['transfer'])){
			include("transfer.php");
			exit();
		}
		if(isset($_GET['holdCall'])){
			if($_GET['holdCall'] == 1){
				echo action::parkCall($_SESSION['connectedCall']['callerChan']);
				exit();
			}
			if($_GET['holdCall'] == 2){
				action::unParkCall($_SESSION['connectedCall']['callerChan'],$_SESSION['extension']);
				exit();
			}
		}
	}
}

/*if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
	echo "The browser you are using is not supported by this version of Open Answer.<br />
			If you have to use Internet Explorer, then please upgrade to Internet Explorer 8 or use 
			the Internet Explorer 6/7 version of Open Answer.<br /> Otherwise, choose one of the following 
			browsers to use:
			<br /><div style=\"border: black thin solid;\"><table><tbody><tr><td>Firefox</td><td>Google Chrome</td></tr>
			<tr><td>Apple Safari</td><td>Internet Explorer 8</td></tr></tbody></table></div>";
	exit();
}*/
?>