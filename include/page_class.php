<?php

class page{
	
	public static function get_tree($page){
		$nav = array();
		switch ($page){
			case 1:
				$nav['home'] = "Home";
			break;
		}
		return $nav;
	}
	
	public static function get_page_contents($page){
		switch ($page){
			case 1:
				return file_get_contents("html/home_dash.php");
			break;
		}
	}
	
	public static function get_page_file($page){
		switch ($page){
			case 0:
				return 'html/login.html';
			break;
			
			case 1:
				return 'html/cp.php';
			break;
		}
	}
	
	public static function get_ajax_page_file($page){
		switch ($page){
			case 0:
				return 'html/error.htm';
			break;
			case 1:
				return 'html/add_user.php';
			break;
			case 2:
				return 'html/add_role.php';
			break;
			case 3:
				return 'html/add_team.php';
			break;
			case 4:
				return 'html/user_details.php';
			break;
			case 5:
				return 'html/remove_user.php';
			break;
			case 6:
				return 'html/edit_user.php';
			break;
			case 7:
				return 'html/delete_user.php';
			break;
			case 8:
				return 'html/delete_role.php';
			break;
			case 9:
				return 'html/modify_role.php';
			break;
			case 10:
				return 'html/role_details.php';
			break;
			case 11:
				return 'html/delete_team.php';
			break;
			case 12:
				return 'html/remove_team.php';
			break;
			case 13:
				return 'html/modify_team.php';
			break;
			case 14:
				return 'html/team_details.php';
			break;
			case 15:
				return 'html/add_client.php';
			break;
			case 16:
				return 'html/modify_client.php';
			break;
			case 17:
				return 'html/delete_client.php';
			break;
			case 18:
				return 'html/client_details.php';
			break;
			case 19:
				return 'html/add_client_form.php';
			break;
			case 20:
				return 'html/form_details.php';
			break;
			case 21:
				return 'html/delete_form.php';
			break;
			case 22:
				return 'html/remove_form.php';
			break;
			case 23:
				return 'html/update_form.php';
			break;
			case 24:
				return 'html/add_queue.php';
			break;
			case 25:
				return 'html/delete_queue.php';
			break;
			case 26:
				return 'html/modify_queue.php';
			break;
			case 28:
				return 'html/queue_details.php';
			break;
			case 29:
				return 'html/view_recordings.php';
			break;
			case 30:
				return 'html/view_live_calls.php';
			break;
			case 31:
				return 'html/personal_settings.php';
			break;
			case 34:
				return 'html/assign_queue_members.php';
			break;
			case 32:
				return 'html/assign_role.php';
			break;
			case 33:
				return 'html/assign_team.php';
			break;
			case 35:
				return 'html/live_stats.php';
			break;
			case 36:
				return 'html/view_cdr.php';
			break;
			case 36.1:
				return 'html/view_cdr.php';
			break;
			case 37:
				return 'html/home_dash.php';
			break;
			case 38:
				return 'html/add_client_ext.php';
			break;

		}
	}
	
	public static function get_perm($ajxPG){
		if(is_numeric($ajxPG)){
			switch ($ajxPG){
				case 1:
				 	return 'add_user';
				break;
				case 2:
					return 'add_role';
				break;
				case 3:
					return 'add_team';
				break;
				case 4:
					return 'view_user_details';
				break;
				case 5:
					return 'remove_user';
				break;
				case 6:
					return 'modify_user';
				break;
				case 7:
					return 'delete_user';
				break;
				case 8:
					return 'delete_role';
				break;
				case 9:
					return 'modify_role';
				break;
				case 10:
					return 'modify_role';
				break;
				case 11:
					return 'delete_team';
				break;
				case 12:
					return 'remove_team';
				break;
				case 13:
					return 'modify_team';
				break;
				case 14:
					return 'modify_team';
				break;
				case 15:
					return 'add_client';
				break;
				case 16:
					return 'modify_client';
				break;
				case 17:
					return 'delete_client';
				break;
				case 18:
					return 'view_client_details';
				break;
				case 19:
					return 'modify_client';
				break;
				case 20:
					return 'modify_client';
				break;
				case 21:
					return 'modify_client';
				break;
				case 22:
					return 'modify_client';
				break;
				case 23:
					return 'modify_client';
				break;
				case 24:
					return 'add_queue';
				break;
				case 25:
					return 'delete_queue';
				break;
				case 26:
					return 'modify_queue';
				break;
				case 28:
					return 'modify_queue';
				break;
				case 29:
					return 'play_record';
				break;
				case 30:
					return 'view_all_live_calls';
				break;
				case 31:
					return 'change_pass';
				break;
				case 32:
					return 'modify_role';
				break;
				case 33:
					return 'modify_queue';
				break;
				case 34:
					return 'modify_team';
				break;
				case 35:
					return 'view_all_live_stats';
				break;
				case 36:
					return 'view_all_cdr';
				break;
				case 36.1:
					return 'view_own_cdr';
				break;
				case 37:
					return 'home';
				break;
				case 38:
					return 'modify_client';
				break;
			}
		}
		else{
			return NULL;
		}
	}
	

/*	
	if(isset($_GET['page']) && $_GET['page']=='2'){
		$page = "html/settings.html";
	}
	if(isset($_GET['page']) && $_GET['page']=='1'){
		$page = "html/log_call.html";
	}
	if(isset($_GET['page']) && $_GET['page']=='3'){
		include("include/logout.php");
	}
	if(isset($_GET['page']) && $_GET['page']=='4'){
		$page = "html/settings.html";
	}
*/
//End of class	
}

?>