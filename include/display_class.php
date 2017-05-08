<?php

class display{
	public static function show_tabs(){
		//In the future, this method will display dynamic tabs. For now, it'll just be three.
		$tabs = "
    	<ul id=\"tabnav\">
			<li class=\"currentTab\" id=\"licp\"><a href=\"/?cpg=1\" id=\"cp\">Console</a></li>
            <li class=\"tab2\" id=\"litake_calls\"><a href=\"/?cpg=2\" id=\"take_calls\">Take Calls</a></li>
			<li class=\"tab2\"><a href=\"/?logoff=1\">Log Out</a></li>
        </ul>";
		return $tabs;
	}
	
	private function array_empty($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $value) {
				if (!self::array_empty($value)) {
					return false;
				}
			}
		}
		elseif (!empty($mixed)) {
			return false;
		}
		return true;
	}
	
	private function array_preg($mixed, $preg) {
		if (is_array($mixed)) {
			foreach ($mixed as $value) {
				if (!self::array_preg($value, $preg)) {
					return false;
				}
			}
		}
		elseif (preg_match("/$preg/i", $mixed)) {
			return false;
		}
		return true;
	}

	private function desanitize_array($arr_r){
		foreach ($arr_r as &$val) is_array($val) ? self::desanitize_array($val):$val = stripslashes($val);
		unset($val);
		return $arr_r;
	}

	public static function show_sidebar($uid){
		function build_droor($title, $socks){
			$html =	"<div class=\"sideDroors\"><a href=\"#\"><span style=\"margin-left:5px;\">$title</span></a></div>\n
					\t<div class=\"inSideDroor\">";
			foreach($socks as $sock){
				$html = $html.$sock."\n";
			}
			$html = $html."</div>";
			return $html;
		}
		
		$obj = new user();
		$perms = $obj->userPerms($uid);
		if(is_array($perms)){
			if(!preg_match("/0x1/i", implode("",$perms))){
				$html = array('user'=>array(),'client'=>array(),'queue'=>array(),'record'=>array(),'live'=>array(),'cdr'=>array(),'personal'=>array());
				$html['user'][] = "<ul class=\"socks\">";
				$html['client'][] = "<ul class=\"socks\">";
				$html['queue'][] = "<ul class=\"socks\">";
				$html['record'][] = "<ul class=\"socks\">";
				$html['live'][] = "<ul class=\"socks\">";
				$html['cdr'][] = "<ul class=\"socks\">";
				$html['personal'][] = "<ul class=\"socks\">";
				$html['team'][] = "<ul class=\"socks\">";
				//Top of sock droor
				foreach($perms as $key=>$val){
					switch ($key){
						case $key == 'add_user' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"add_user\">Add User</a></li>";
						break;
						case $key == 'modify_user' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"modify_user\">Modify User</a></li>";
							$html['user'][] = "<li><a href=\"#\" id=\"assign_user_form\">Assign User Form</a></li>";
						break;
						case $key == 'view_user_details' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"view_user_details\">User Details</a></li>";
						break;
						case $key == 'remove_user' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"remove_user\">Disable User</a></li>";
						break;
						case $key == 'delete_user' && $val==1:
							$html['user'][] = "<li class=\"boldRed\"><a href=\"#\" id=\"delete_user\">Delete User</a></li>";
						break;
						case $key == 'add_role' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"add_role\">Add Role</a></li>";
						break;
						case $key == 'modify_role' && $val==1:
							$html['user'][] = "<li><a href=\"#\" id=\"modify_role\">Modify Role</a></li>";
							$html['user'][] = "<li><a href=\"#\" id=\"assign_role\">Assign Role</a></li>";
							$html['user'][] = "<li><a href=\"#\" id=\"assign_role_form\">Assign Role Form</a></li>";
						break;
						case $key == 'delete_role' && $val==1:
							$html['user'][] = "<li class=\"boldRed\"><a href=\"#\" id=\"delete_role\">Delete Role</a></li>";
						break;
						case $key == 'add_client' && $val==1:
							$html['client'][] = "<li><a href=\"#\" id=\"add_client\">Add Client</a></li>";
						break;
						case $key == 'view_client_details' && $val==1:
							$html['client'][] = "<li><a href=\"#\" id=\"view_client_details\">Client Details</a></li>";
						break;
						case $key == 'delete_client' && $val==1:
							$html['client'][] = "<li class=\"boldRed\"><a href=\"#\" id=\"delete_client\">Delete Client</a></li>";
						break;
						case $key == 'modify_client' && $val==1:
							$html['client'][] = "<li><a href=\"#\" id=\"modify_client\">Modify Client</a></li>";
							$html['client'][] = "<li><a href=\"#\" id=\"add_client_form\">Add Client Form</a></li>";
							$html['client'][] = "<li><a href=\"#\" id=\"update_client_form\">Update Client Form</a></li>";
							$html['client'][] = "<li><a href=\"#\" id=\"remove_client_form\">Remove Client Form</a></li>";
							$html['client'][] = "<li><a href=\"#\" id=\"delete_client_form\">Delete Client Form</a></li>";
							$html['client'][] = "<li><a href=\"#\" id=\"assign_ext\">Assign Extension\DID</a></li>";
						break;
						case $key == 'add_queue' && $val==1:
							$html['queue'][] = "<li><a href=\"#\" id=\"add_queue\">Add Queue</a></li>";
						break;
						case $key == 'modify_queue' && $val==1:
							$html['queue'][] = "<li><a href=\"#\" id=\"modify_queue\">Modify Queue</a></li>";
							$html['queue'][] = "<li><a href=\"#\" id=\"assign_queue\">Assign Queue Users</a></li>";
							$html['queue'][] = "<li><a href=\"#\" id=\"assign_queue_form\">Assign Queue Form</a></li>";
						break;
						case $key == 'delete_queue' && $val==1:
							$html['queue'][] = "<li class=\"boldRed\"><a href=\"#\" id=\"delete_queue\">Delete Queue</a></li>";
						break;
						case $key == 'play_record' && $val==1:
							$html['record'][] = "<li><a href=\"#\" id=\"play_record\">Review Recordings</a></li>";
						break;
						case $key == 'view_all_live_stats' && $val==1:
							$html['live'][] = "<li><a href=\"#\" id=\"all_live_stats\">View Live Stats</a></li>";
						break;
						case $key == 'view_team_live_stats' && $val==1:
							if($perms['view_all_live_stats']!=1){
								$html['live'][] = "<li><a href=\"#\" id=\"team_live_calls\">View Live Stats</a></li>";
							}
						break;
						case $key == 'view_all_live_calls' && $val==1:
							$html['live'][] = "<li><a href=\"#\" id=\"all_live_calls\">View Live Calls</a></li>";
						break;
						case $key == 'view_team_live_calls' && $val==1:
							if($perms['view_all_live_calls']!=1){
								$html['live'][] = "<li><a href=\"#\" id=\"team_live_calls\">View Live Calls</a></li>";
							}
						break;
						case $key == 'view_all_agent_status' && $val==1:
							$html['live'][] = "<li><a href=\"#\" id=\"all_agent_status\">Agent Status</a></li>";
						break;
						case $key == 'view_team_agent_status' && $val==1:
							if($perms['view_all_agent_status']!=1){
								$html['live'][] = "<li><a href=\"#\" id=\"team_agent_status\">Agent Status</a></li>";
							}
						break;
						case $key == 'view_all_cdr' && $val==1:
							$html['cdr'][] = "<li><a href=\"#\" id=\"all_cdr\">View CDR</a></li>";
						break;
						case $key == 'view_team_cdr' && $val==1:
							if($perms['view_all_cdr']!=1){
								$html['cdr'][] = "<li><a href=\"#\" id=\"team_cdr\">View CDR</a></li>";
							}
						break;
						case $key == 'view_own_cdr' && $val==1:
								$html['cdr'][] = "<li><a href=\"#\" id=\"own_cdr\">View Personal CDR</a></li>";
						break;
						case $key == 'add_team' && $val==1:
							$html['team'][] = "<li><a href=\"#\" id=\"add_team\">Add Team</a></li>";
						break;
						case $key == 'delete_team' && $val==1:
							$html['team'][] = "<li class=\"boldRed\"><a href=\"#\" id=\"delete_team\">Delete Team</a></li>";
						break;
						case $key == 'remove_team' && $val==1:
							$html['team'][] = "<li><a href=\"#\" id=\"remove_team\">Remove Team</a></li>";
						break;
						case $key == 'modify_team' && $val==1:
							$html['team'][] = "<li><a href=\"#\" id=\"modify_team\">Modify Team</a></li>";
							$html['team'][] = "<li><a href=\"#\" id=\"assign_team\">Assign Team Members</a></li>";
							$html['team'][] = "<li><a href=\"#\" id=\"assign_team_form\">Assign Team Form</a></li>";
						break;
					}
				}
				$html['personal'][] = "<li><a href=\"#\" id=\"personal_settings\">Change Password</a></li>";
				//Bottom of sock droor.
				$html['user'][] = "</ul>";
				$html['client'][] = "</ul>";
				$html['queue'][] = "</ul>";
				$html['record'][] = "</ul>";
				$html['live'][] = "</ul>";
				$html['cdr'][] = "</ul>";
				$html['personal'][] = "</ul>";
				$html['team'][] = "</ul>";
				//End of Permission Settings.
				if(!self::array_preg($html['user'],"\<li\>")){ $ahtml = $ahtml.build_droor("User Management", $html['user']); }
				if(!self::array_preg($html['team'],"\<li\>")){ $ahtml = $ahtml.build_droor("Team Management", $html['team']); }
				if(!self::array_preg($html['client'],"\<li\>")){ $ahtml = $ahtml.build_droor("Client Management", $html['client']); }
				if(!self::array_preg($html['queue'],"\<li\>")){ $ahtml = $ahtml.build_droor("Queue Management", $html['queue']); }
				if(!self::array_preg($html['record'],"\<li\>")){ $ahtml = $ahtml.build_droor("Recordings", $html['record']); }
				if(!self::array_preg($html['live'],"\<li\>")){ $ahtml = $ahtml.build_droor("Live Stats\\Calls", $html['live']); }
				if(!self::array_preg($html['cdr'],"\<li\>")){ $ahtml = $ahtml.build_droor("Call Detail Records", $html['cdr']); }
				if(!self::array_preg($html['personal'],"\<li\>")){ $ahtml = $ahtml.build_droor("Personal Settings", $html['personal']); }
				return $ahtml;
			}
			else{
				return "0x1 Error: There was a problem retrieving your panel options. <br />Please contact your administrator and reference error DC:58 $perms";
			}
		}
		else{
			return "0x1 Error: There was a problem retrieving your panel options. <br />Please contact your administrator and reference error DC:57 $perms";
		}
	}
	
	public static function show_nav($page){
		$page = page::get_tree($page);
		if(!preg_match("/0x1/i", implode("",$page))){
			foreach($page as $key => $val){
				$html = $html."<a href=\"#\" id=\"$key\">$val</a>";
			}
			return $html;
		}
		else{
			return "0x1 Error: There was a problem retrieving your panel options. <br />Please contact your administrator and reference error DC:209";
		}
	}
	
	public static function get_roles($format='', $only_assigned='', $edit='', $delete='', $fullSearch='',$byId=''){
		local_connect();
		$extra = '';
		!empty($fullSearch) ? $extra .= " `title` LIKE '%$fullSearch%'" : $extra = '';
		!empty($byId) ? $extra .= " `id` = '$byId'" : $extra .= '';
		$sql = "SELECT * FROM `user_roles` ".(!empty($extra)? "WHERE":"")." $extra";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch($format){
			case 'option':
				$html = "<option value=\"\" selected=\"selected\">None</option>\n";
				while($row = mysqli_fetch_array($result)){
					$html = $html."<option value=\"$row[id]\">$row[title]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = ">Role Name";
				$header[] = " align=\"center\">Level";
				$header[] = " align=\"center\">Total Assigned Users";
				$html = array();
				$html[] = "<div id=\"roleListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"roleList\" class=\"roleList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\"".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				if($edit==1){
					if($perms['modify_role']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['delete_role']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					if($row['id'] !=''){
						$sql2 = "SELECT COUNT(`user_id`) as total_assigned FROM `role_assignment` WHERE `role_id`='$row[id]' LIMIT 1";
						$row2 = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sql2));
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$v = array("<a href=\"#\" id=\"viewRole\" onClick=\"viewRoleDetails('$row[id]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>$v[0]$row[title]</td>\n\t<td align=\"center\">$v[0]$row[level]$v[1]</td>\n\t<td align=\"center\">$v[0]$row2[total_assigned]$v[1]</td>";
						if($edit==1){
							if($perms['modify_role']==1){
								$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editRole('$row[id]')\"/></td>\n";
							}
						}
						if($delete==1){
							if($perms['delete_user']==1){
								$html[] = "\t<td><span class=\"delete\" onclick=\"deleteRole('$row[id]', '".sanitize_data::sanitize_string($row['title'])."')\"/></td>\n";
							}
						}
						$html[] = "</tr>\n"; $i++;
					}
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_array($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_users($format='', $edit='', $remove='', $delete='', $fullSearch='', $none=0){
		local_connect();
		!empty($fullSearch) ? $sql = "CALL user_full_search('$fullSearch')" : $sql = "SELECT * FROM `users`";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				while($row = mysqli_fetch_assoc($result)){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = "Name";
				$header[] = "User Name";
				$header[] = "Ext";
				$header[] = "E-Mail";
				$header[] = "Disabled Status";
				$header[] = "Tech";
				$header[] = "Channel";
				$html = array();
				$html[] = "<div id=\"userListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"userList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				if($edit==1){
					if($perms['modify_user']==1){
						$html[] = "\t<th class=\"header\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['delete_user']==1){
						$html[] = "\t<th class=\"header\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
					if($remove==1){
						if($row['disabled']!=1){
							$a = array("<a href=\"#\" id=\"removeUser\" onClick=\"removeUser('$row[user_id]', '$row[name]')\">","</a>");
						}else{ $a = array("", ""); }
					}else{ $a = array("", ""); }
					$v = array("<a href=\"#\" id=\"viewUser\" onClick=\"viewDetails('$row[user_id]')\">","</a>");
					//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
					$html[] = "<tr $odd>\n\t<td>$v[0]$row[name]</td>\n\t<td>$v[0]$row[user_name]$v[1]</td>\n\t<td>$v[0]$row[extension]$v[1]</td>\n\t<td>$v[0]$row[email_address]$v[1]</td>\n
								\t<td style=\"text-align:center;\">$a[0]".($remove==1 ?($row['disabled']==1?"User Disabled":"Disable User") : ($row['disabled']==1? 'Yes':'No'))."$a[1]</td>\n\t<td>$v[0]$row[tech]$v[1]</td>\n\t<td>$v[0]$row[channel]$v[1]</td>\n";
					if($edit==1){
						if($perms['modify_user']==1){
							$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editUser('$row[user_id]')\"/></td>\n";
						}
					}
					if($delete==1){
						if($perms['delete_user']==1){
							$html[] = "\t<td><span class=\"delete\" onclick=\"deleteUser('$row[user_id]', '$row[name]')\"/></td>\n";
						}
					}
					$html[] = "</tr>\n"; $i++;
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function error_msg($msg, $alignment=''){
		if(is_numeric($msg)){
			switch ($msg){
				case 1:
					$text = "You do not have permission to access this page.<br />Please contact your administator for additional help.";
				break;
				default:
					$text = "There was an error processing your request.<br />Please contact your administrator if this error occurs again.";
				break;
			}
		}
		else{
			$text = $msg;
		}
		switch($alignment){
			case "L":
				$align = "align=\"left\"";
			break;
			case "R":
				$align = "align=\"right\"";
			break;
			default:
				$align = "align=\"center\"";
			break;
		}
		return "<div class=\"errorField\" $align id=\"errorMSG\"><p align=\"center\">$text</p></div>";
	}

	public static function success_msg($msg){
		if(is_numeric($msg)){
			switch ($msg){
				case 1:
					$text = "You do not have permission to access this page.<br />Please contact your administator for additional help.";
				break;
				default:
					$text = "There was an error processing your request.<br />Please contact your administrator if this error occurs again.";
				break;
			}
		}
		else{
			$text = $msg;
		}
		return "<div id=\"successContainer\"><div class=\"successField\" align=\"center\" id=\"successMSG\"><p align=\"center\">$text</p></div></div>";
	}

	public static function get_search($id){
		return "<div id=\"search\" class=\"\">
					<div>
					<label for=\"sp_searchtext\">
						<span class=\"placeholder\">Search</span>
						<div class=\"search_wrapper empty\">
							<span class=\"left\">
							<input name=\"q\" type=\"text\" class=\"searchBar\" id=\"$id\" accesskey=\"s\" value=\"\" autocomplete=\"off\"/>
							<span class=\"right\">
						  <div class=\"reset\" id=\"reset\"></div></span></span>
						</div>
					</label>
					<input type=\"hidden\" id=\"search_section\" name=\"sec\" value=\"global\"/></div>
			<img height=\"11\" width=\"11\" border=\"0\" src=\"spinner.gif\" alt=\"*\" id=\"search_spinner\" style=\"display: none;\"/>
		</div>";
	}
	
	public static function build_confirm_box($msg, $title=''){
		return "
			<div id=\"confirm\" title=\"$title\" style=\"display:none\">
				<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>$msg</p>
			</div>";
	}
	
	public static function build_alert_box($msg, $title=''){
		return "
			<div id=\"alert\" title=\"$title\" "./*class=\"ui-dialog\"*/"style=\"display:none\">
				<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>$msg</p>
			</div>";
	}

	public static function get_teams($format='', $only_assigned='', $edit='', $delete='', $remove='', $fullSearch='',$byID=''){
		local_connect();
		!empty($fullSearch) ? $extra = " `team_name` LIKE '%$fullSearch%'" : $extra = '';
		!empty($byID) ? $extra .= " `id` = '$byID%'" : $extra .= '';
		$sql = "SELECT * FROM `teams` ".(!empty($extra)? "WHERE":"")." $extra";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch($format){
			case 'option':
				$html = "<option value=\"\" selected=\"selected\">None</option>\n";
				while($row = mysqli_fetch_array($result)){
					$html = $html."<option value=\"$row[id]\">$row[team_name]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = ">Team Name";
				$header[] = ">Date Created";
				$header[] = ">Date Modified";
				$header[] = " align=\"center\">Disabled";
				$header[] = " align=\"center\">Max Members";
				$header[] = " align=\"center\">Total Members";
				$html = array();
				$html[] = "<div id=\"teamListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"teamList\" class=\"teamList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\"".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				
				if($edit==1){
					if($perms['modify_team']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['delete_team']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					if($row['id'] !=''){
						$sql2 = "SELECT COUNT(`user_id`) as total_assigned FROM `team_assignment` WHERE `team_id`='$row[id]' LIMIT 1";
						$row2 = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sql2));
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$v = array("<a href=\"#\" id=\"viewTeam\" onClick=\"viewTeamDetails('$row[id]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>$v[0]$row[team_name]</td>\n\t<td>$v[0]".date("m/d/y \a\\t h:i A",$row['date_created'])."$v[1]</td>\n\t<td>$v[0]".($row['date_modified']!=''?date("m/d/y \a\t h:i A",$row['date_modified']):"Never")."$v[1]</td>\n\t<td align=\"center\">".($remove==1 ?($row['active']==1?"Team Disabled":"<a href=\"#\" id=\"viewTeam\" onClick=\"removeTeam('$row[id]','$row[team_name]')\">Disable Team</a>") : ($row['active']==1? $v['0'].'Yes'.$v['1']:$v['0'].'No'.$v['1']))."</td>\n\t<td align=\"center\">$v[0]$row[max_members]$v[1]</td>\n\t<td align=\"center\">$v[0]$row2[total_assigned]$v[1]</td>";
						if($edit==1){
							if($perms['modify_team']==1){
								$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editTeam('$row[id]')\"/></td>\n";
							}
						}
						if($delete==1){
							if($perms['delete_team']==1){
								$html[] = "\t<td><span class=\"delete\" onclick=\"deleteTeam('$row[id]', '$row[team_name]')\"/></td>\n";
							}
						}
						$html[] = "</tr>\n"; $i++;
					}
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_array($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_team_leader($id){
		local_connect();
		$sql = "SELECT `name` FROM `users` WHERE `user_id`=(SELECT `user_id` FROM team_assignment WHERE `team_id`='$id' AND `default` = '1' LIMIT 1)";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$user = mysqli_fetch_assoc($result);
		return $user['name'];
	}
	
	public static function get_team_members($id){
		local_connect();
		$sql = "SELECT `users`.`user_id`, `name` FROM `users`,`team_assignment` WHERE `users`.`user_id`=`team_assignment`.`user_id` AND `team_assignment`.`team_id`='$id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$arry = array();
		while($row = mysqli_fetch_assoc($result)){
			$arry[] = "<span class=\"links\"><a href=\"#\" id=\"viewUser\" onClick=\"viewDetails('$row[user_id]')\">$row[name]</a></span><br />";
		}
		return implode("\n",$arry);
	}

	public static function get_clients($format='', $edit='', $delete='', $fullSearch='', $byID='', $noSelect='', $none=0){
		local_connect();
		!empty($fullSearch) ? $sql = "CALL client_full_search('$fullSearch')" : $sql = "SELECT * FROM `clients`";
		!empty($byID) ? $sql = "SELECT * FROM `clients` WHERE `id`='$byID'" : "";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				($none!=1?$html = "<option value=\"\" ".($noSelect!=1?"selected=\"selected\"":NULL).">None</option>\n":NULL);
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					$html = $html."<option value=\"$row[id]\">$row[company_name]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = "Client Number";
				$header[] = "Company Name";
				$header[] = "Contact";
				$header[] = "E-Mail";
				$header[] = "Phone";
				$header[] = "City";
				$header[] = "State";
				$html = array();
				$html[] = "<div id=\"clientListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"clientList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				if($edit==1){
					if($perms['modify_client']==1){
						$html[] = "\t<th class=\"header\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['delete_client']==1){
						$html[] = "\t<th class=\"header\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
					$v = array("<a href=\"#\" id=\"viewClient\" onClick=\"viewClientDetails('$row[id]')\">","</a>");
					//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
					$html[] = "<tr $odd>\n\t<td>$v[0]$row[client_number]</td>\n\t<td>$v[0]$row[company_name]$v[1]</td>\n\t<td>$v[0]$row[contact]$v[1]</td>\n\t<td>$v[0]$row[email]$v[1]</td>\n
								\t<td style=\"text-align:center;\">$v[0]$row[phone]$v[1]</td>\n\t<td>$v[0]$row[phy_city]$v[1]</td>\n\t<td>$v[0]$row[phy_state]$v[1]</td>\n";
					if($edit==1){
						if($perms['modify_client']==1){
							$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editClient('$row[id]')\"/></td>\n";
						}
					}
					if($delete==1){
						if($perms['delete_client']==1){
							$html[] = "\t<td><span class=\"delete\" onclick=\"deleteClient('$row[id]', '".addslashes($row['company_name'])."')\"/></td>\n";
						}
					}
					$html[] = "</tr>\n"; $i++;
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_client_forms($format='', $edit='', $remove='', $delete='', $fullSearch='', $byID=''){
		local_connect();
		!empty($fullSearch) ? $sql = "SELECT `id`, `form`, `created`, `last_modified`, `creator`, `modifier`, `common_name`, `client_id`, `active` FROM `xml_forms`, `form_assignment` WHERE `xml_forms`.id = `form_assignment`.`form_id` AND `client_id` != '' AND `common_name` LIKE '%$fullSearch%'" : 
				$sql = "SELECT `id`, `form`, `created`, `last_modified`, `creator`, `modifier`, `common_name`, `client_id`, `active` FROM `xml_forms`, `form_assignment` WHERE `xml_forms`.id = `form_assignment`.`form_id` AND `client_id` != ''";
		!empty($byID) ? $sql = "SELECT `id`, `form`, `created`, `last_modified`, `creator`, `modifier`, `common_name`, `client_id`, `active` FROM `xml_forms`, `form_assignment` WHERE `xml_forms`.id = `form_assignment`.`form_id` AND `client_id` != '' AND `id` = '$byID'" : "";
		//echo $sql;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				$html = "<option value=\"0\" ".(!empty($byID)?"":"selected=\"selected\"").">None</option>\n";
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					$html = $html."<option value=\"$row[id]\">$row[common_name]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = "Form Name";
				$header[] = "Client";
				$header[] = "Date Created";
				$header[] = "Last Modified";
				$header[] = "Disabled";
				$header[] = "Creator";
				$html = array();
				$html[] = "<div id=\"formListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"formList\" class=\"formList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				if($edit==1){
					if($perms['modify_client']==1){
						$html[] = "\t<th class=\"header\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['modify_client']==1){
						$html[] = "\t<th class=\"header\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					$client = self::get_clients('','','','',$row['client_id']);
					$client = $client['0']['company_name'];
					$userObj = new user();
					$creator = $userObj->userInfoAll($row['creator'],6);
					$creator = $creator['name'];
					if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
					$v = array("<a href=\"#\" id=\"viewForm\" onClick=\"viewFormDetails('$row[id]')\">","</a>");
					//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
					$html[] = "<tr $odd>\n\t<td>$v[0]$row[common_name]</td>\n\t<td>$v[0]".stripslashes($client)."$v[1]</td>\n\t<td>$v[0]
							".date("m/d/y \a\\t h:i A",$row['created'])."$v[1]</td>\n\t<td>$v[0]".($row['modified']!=''?date("m/d/y \a\\t h:i A",$row['modified']):"Never")."$v[1]</td>\n
								\t<td style=\"text-align:center;\">".($remove==1 ?($row['active']==1?"Form Disabled":"<a href=\"#\" id=\"viewForm\" onClick=\"removeForm('$row[id]','".addslashes($row[common_name])."')\">Disable Form</a>") : ($row['active']==1? $v['0'].'No'.$v['1']:$v['0'].'Yes'.$v['1']))."
								</td>\n\t<td style=\"text-align:center;\">$v[0]$creator$v[1]</td>\n";
					if($edit==1){
						if($perms['modify_client']==1){
							$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editForm('$row[id]')\"/></td>\n";
						}
					}
					if($delete==1){
						if($perms['modify_client']==1){
							$html[] = "\t<td><span class=\"delete\" onclick=\"deleteForm('$row[id]', '".addslashes($row['common_name'])."')\"/></td>\n";
						}
					}
					$html[] = "</tr>\n"; $i++;
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}

	public static function get_queues($format='', $only_assigned='', $edit='', $delete='', $fullSearch='',$byID=''){
		local_connect();
		!empty($fullSearch) ? $sql = "CALL queue_full_search('$fullSearch')" : $sql = "SELECT * FROM `queues`";
		!empty($byID) ? $sql = "SELECT * FROM `queues` WHERE `id`='$byID'" : "";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch($format){
			case 'option':
				$html = "<option value=\"\" selected=\"selected\">None</option>\n";
				while($row = mysqli_fetch_array($result)){
					$html = $html."<option value=\"$row[id]\">$row[queue_name]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = ">Queue Name";
				$header[] = " align=\"center\">Extension";
				$header[] = ">Strategy";
				$header[] = " align=\"center\">SLA";
				$header[] = " align=\"center\">Total Assigned Users";
				$html = array();
				$html[] = "<div id=\"queueListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"queueList\" class=\"queueList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\"".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				if($edit==1){
					if($perms['modify_queue']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Edit</th>\n";
					}
				}
				if($delete==1){
					if($perms['delete_queue']==1){
						$html[] = "\t<th class=\"header\" align=\"center\">Delete</th>\n";
					}
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					if($row['id'] !=''){
						$sql2 = "SELECT COUNT(`user_id`) as total_assigned FROM `queue_assignment` WHERE `queue_id`='$row[id]' LIMIT 1";
						$row2 = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"], $sql2));
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$v = array("<a href=\"#\" id=\"viewQueue\" onClick=\"viewQueueDetails('$row[id]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>$v[0]$row[queue_name]</td>\n\t<td align=\"center\">$v[0]$row[queue_ext]$v[1]</td>\n\t<td>$v[0]$row[strategy]</td>\n\t<td align=\"center\">$v[0]$row[sla]$v[1]</td>\n\t<td align=\"center\">$v[0]$row2[total_assigned]$v[1]</td>\n";
						if($edit==1){
							if($perms['modify_role']==1){
								$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editQueue('$row[id]')\"/></td>\n";
							}
						}
						if($delete==1){
							if($perms['delete_user']==1){
								$html[] = "\t<td><span class=\"delete\" onclick=\"deleteQueue('$row[id]', '$row[queue_name]')\"/></td>\n";
							}
						}
						$html[] = "</tr>\n"; $i++;
					}
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_array($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_recordings($byID ='', $call_unique_id='', $user_id='', $time='', $range='', $format=''){
		$obj = new record_manager();
		!empty($byID) ? $recordings = $obj->list_recordings($_SESSION['user_id'], $byID) : $recordings = $obj->list_recordings($_SESSION['user_id']);
		switch ($format){
			case 'table':
				$headers = array();
				$header[] = "Date/Time";
				$header[] = "Caller ID";
				$header[] = "User";
				$html = array();
				$html[] = "<div id=\"recordListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"recordList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				($perms['play_record']==1? $html[] = "\t<th class=\"header\">Listen</th>\n":"");
				($perms['delete_record']==1? $html[] = "\t<th class=\"header\">Delete</th>\n" :"");
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				if(is_array($recordings)){
					foreach($recordings as $var){
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$name = user::userInfoAll($var['user_issued'],6);
						$name = $name['name'];
						$v = array("<a href=\"#\" id=\"viewRecord\" onClick=\"viewRecording('$var[id]')\">","</a>");
						$vU = array("<a href=\"#\" id=\"viewRecord\" onClick=\"viewDetails('$var[user_issued]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>$v[0]".date("m/d/y \a\\t h:i A",$var['time'])."</td>\n\t<td>$v[0]".call_info::caller_id($var['call_unique_id'])."$v[1]</td>\n\t<td>$vU[0]".(!empty($name) ? $name :"N/A")."$v[1]</td>\n";
						if($perms['play_record']==1){
							$html[] = "\t<td align=\"center\"><span class=\"play\" \"/>".self::audio_player(currentURL(1)."/recordings/$var[id].mp3",85)."</td>\n";
						}
						if($perms['delete_record']==1){
							$html[] = "\t<td><span class=\"delete\" onclick=\"deleteRecording('$var[id]', '".addslashes(call_info::caller_id($var['call_unique_id']))."')\"/></td>\n";
						}
						$html[] = "</tr>\n"; $i++;
					}
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				foreach($recordings as &$var){
					$name = user::userInfoAll($var['user_issued'],6);
					$var['name']= $name['name'];
				}
				return $recordings;
			break;
		}
		local_disconnect();
	}
	
	public static function audio_player($url, $width='', $height='', $colors=''){
		empty($width)? $width = '290':$width = preg_replace("/\W\w/","",$width);
		empty($height)? $height = '24':$height = preg_replace("/\W\w/","",$height);
		if(is_array($colors)){
			//We'll change the colors in the future. For now, we'll leave this out.
		}
		return "
		<object type=\"application/x-shockwave-flash\" data=\"".currentURL(1)."/audio-player/player.swf\" id=\"".md5($url)."\" height=\"$height\" width=\"$width\">
            <param name=\"movie\" value=\"".currentURL(1)."/audio-player/player.swf\">
            <param name=\"FlashVars\" value=\"playerID=".md5($url)."&soundFile=$url\">
            <param name=\"quality\" value=\"high\">
            <param name=\"menu\" value=\"false\">
            <param name=\"wmode\" value=\"transparent\">
			<param name=\"noinfo\" valur=\"yes\">
		</object>";
	}
	
	public static function get_live_connected_calls($format=''){//Not Complete. Needs monitor, record, hangup, and transfer links.
		switch ($format){
			case 'table':
				$headers = array();
				$header[] = "Caller ID";
				$header[] = "User";
				$header[] = "Call Length";
				$header[] = "Queue";
				$html = array();
				$html[] = "<div id=\"liveListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"liveCallList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				($perms['barge_calls']==1? $html[] = "\t<th class=\"header\">Monitor</th>\n":"");
				($perms['record_other_calls']==1? $html[] = "\t<th class=\"header\">Record</th>\n" :"");
				($perms['xfer_other_call']==1? $html[] = "\t<th class=\"header\">Hang Up</th>\n" :"");
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				$calls = call_info::live_calls();
				if(is_array($calls)){
					foreach($calls as $var){
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$name = user::userInfoAll($var['agent_channel'],1);
						$aname = $name['name'];
						$v = array("<a href=\"#\" id=\"viewCallDetails\" onClick=\"viewCallDetails('$var[id]')\">","</a>");
						$vU = array("<a href=\"#\" id=\"viewRecord\" onClick=\"viewDetails('$name[user_id]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>$v[0]".$var['caller_id']."$v[1]</td>\n\t<td>$vU[0]".(!empty($aname) ? $aname :"N/A")."$v[1]</td>\n\t<td>$v[0]".(time()-$var['time'])." sec$v[1]</td>\n\t<td>".key($calls)."</td>\n";
						if($perms['barge_calls']==1){
							$html[] = "\t<td align=\"center\"><span class=\"monitor\" \"/><a onclick=\"monitorCall('$var[id]')\" href=\"#\">.</a></td>\n";
						}
						if($perms['record_other_calls']==1){
							$html[] = "\t<td><span class=\"record\" onclick=\"recordCall('$var[id]')\"/></td>\n";
						}
						if($perms['barge_calls']==1){
							$html[] = "\t<td><span class=\"delete\" onclick=\"dropCall('$var[id]')\"/></td>\n";
						}
						$html[] = "</tr>\n"; $i++;
					}
				}
				else{
					$html[] = "<tr><td>No calls are connected</td></tr>";
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				foreach($calls as &$var){
					$name = user::userInfoAll($var['agent_channel'],1);
					$var['name']= $name['name'];
				}
				return $calls;
			break;
		}
		local_disconnect();
	}
	
	public static function get_live_hold_calls($format=''){ //Not Complete. Needs monitor, record, hangup, and transfer links.
		switch ($format){
			case 'table':
				$headers = array();
				$header[] = "Caller ID";
				$header[] = "Wait Time";
				$header[] = "Queue";
				$html = array();
				$html[] = "<div id=\"liveListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"liveCallList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				($perms['barge_calls']==1? $html[] = "\t<th class=\"header\">Monitor</th>\n":"");
				($perms['record_other_calls']==1? $html[] = "\t<th class=\"header\">Record</th>\n" :"");
				($perms['xfer_other_call']==1? $html[] = "\t<th class=\"header\">Hang Up</th>\n" :"");
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				$calls = call_info::live_calls_onhold(); //print_r($calls);
				if(is_array($calls)){
					foreach($calls as $queues){
						if(is_array($queues)){ 
							$var2['queue'] = key($queues);
							//print_r($queues[key($queues)]['Callers']);
							if(isset($queues[key($queues)]['Callers']) && is_array($queues[key($queues)]['Callers'])){
								foreach($queues[key($queues)]['Callers'] as $var){
									if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
									$v = array("<a href=\"#\" id=\"viewCallDetails\" onClick=\"viewCallDetails('$var[id]')\">","</a>");
									$vU = array("<a href=\"#\" id=\"viewRecord\" onClick=\"viewDetails('$name[user_id]')\">","</a>");
									//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
									$html[] = "<tr $odd>\n\t<td>$v[0]".$var['CallerID']."$v[1]</td>\n\t<td>$v[0]".($var['Wait']>67?round(($var['Wait']/60),2)." min":$var['Wait']." sec")."$v[1]</td>\n\t<td>".$var2['queue']."</td>\n";
									if($perms['barge_calls']==1){
										$html[] = "\t<td align=\"center\"><span class=\"monitor\" \"/><a onclick=\"monitorCall('$var[id]')\" href=\"#\">.</a></td>\n";
									}
									if($perms['record_other_calls']==1){
										$html[] = "\t<td><span class=\"record\" onclick=\"recordCall('$var[id]')\"/></td>\n";
									}
									if($perms['barge_calls']==1){
										$html[] = "\t<td><span class=\"delete\" onclick=\"dropCall('$var[id]')\"/></td>\n";
									}
									$html[] = "</tr>\n"; $i++;
								}
							}
						}
					}
				}
				else{
					//echo $calls;
					if(preg_match("/0x1/i",$calls)){
						$html[] = "<tr><td>$calls</td></tr>";
					}
					else{							  
						$html[] = "<tr><td>No calls are connected</td></tr>";
					}
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				foreach($calls as &$var){
					$name = user::userInfoAll($var['agent_channel'],1);
					$var['name']= $name['name'];
				}
				return $calls;
			break;
		}
		local_disconnect();
	}
	
	public static function get_cdr($format=''){
		switch ($format){
			case 'table':
				$headers = array();
				$header[] = "Date/Time";
				$header[] = "Caller ID";
				$header[] = "User";
				$header[] = "Call Length";
				$header[] = "Talk Time";
				$html = array();
				$html[] = "<div id=\"liveListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"liveCallList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				$calls = cdr_search::get_cdr_all();
				if(is_array($calls)){
					foreach($calls as $var){
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$var['dstchannel'] = explode("-",$var['dstchannel']);
						$var['dstchannel'] = $var['dstchannel']['0'];
						$name = user::userInfoAll($var['dstchannel'],1);
						if(!is_array($name)){if(preg_match("/0x1/i",$name)){ echo display::error_msg($name); exit(); }}
						$aname = $name['name'];
						$v = array("","");
						$vU = array("<a href=\"#\" id=\"viewUser\" onClick=\"viewDetails('$name[user_id]')\">","</a>");
						//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
						$html[] = "<tr $odd>\n\t<td>".date("m/d/y \a\\t h:i A",$var['time'])."</td><td>$v[0]".$var['clid']."$v[1]</td>\n\t<td>$vU[0]".(!empty($aname) ? $aname :"N/A")."$v[1]</td>\n\t<td>$v[0]".$var['duration']." sec$v[1]</td>\n\t<td>$v[0]".$var['billsec']." sec$v[1]</td>\n";
						$html[] = "</tr>\n"; $i++;
					}
				}
				else{
					$html[] = "<tr><td>No records found</td></tr>";
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				foreach($calls as &$var){
					$name = user::userInfoAll($var['agent_channel'],1);
					$var['dstchannel']= $name['name'];
				}
				return $calls;
			break;
		}
		local_disconnect();
	}
	
	public static function get_personal_cdr($format=''){
		switch ($format){
			case 'table':
				$headers = array();
				$header[] = "Date/Time";
				$header[] = "Caller ID";
				$header[] = "Call Length";
				$header[] = "Talk Time";
				$html = array();
				$html[] = "<div id=\"liveListTable\"><table  border=\"0\" cellspacing=\"1\" id=\"liveCallList\" class=\"userList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$obj = new user();
				$perms = $obj->userPerms($_SESSION['user_id']);
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				$calls = cdr_search::get_cdr_all();
				if(is_array($calls)){
					foreach($calls as $var){
						if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
						$var['dstchannel'] = explode("-",$var['dstchannel']);
						$var['dstchannel'] = $var['dstchannel']['0'];
						$name = user::userInfoAll($var['dstchannel'],1);
						if(!is_array($name)){if(preg_match("/0x1/i",$name)){ echo display::error_msg($name); exit(); }}
						if($name['user_id'] == $_SESSION['user_id']){
							$v = array("","");
							//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
							$html[] = "<tr $odd>\n\t<td>".date("m/d/y \a\\t h:i A",$var['time'])."</td><td>$v[0]".$var['clid']."$v[1]</td>\n\t<td>$v[0]".$var['duration']." sec$v[1]</td>\n\t<td>$v[0]".$var['billsec']." sec$v[1]</td>\n";
							$html[] = "</tr>\n"; $i++;
						}
					}
				}
				else{
					$html[] = "<tr><td>No records found</td></tr>";
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				foreach($calls as &$var){
					$name = user::userInfoAll($var['dstchannel'],1);
					$var['dstchannel']= $name['name'];
				}
				return $calls;
			break;
		}
		local_disconnect();
	}
	
	public static function get_role_users($role_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `users`.* FROM `users`, `role_assignment` WHERE `users`.`user_id` = `role_assignment`.`user_id` && `role_id` = '$role_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				while($row = mysqli_fetch_assoc($result)){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_nonrole_users($role_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `role_assignment`.`user_id` FROM `role_assignment` WHERE `role_id` = '$role_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$assigned = array(); $free = array();
		while($row = mysqli_fetch_assoc($result)){
			$assigned[] = $row['user_id'];
		}		$sql = "SELECT * FROM `users`";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		while($row = mysqli_fetch_assoc($result)){
			if(!in_array($row['user_id'],$assigned)){ $free[] = $row; }
		}
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				foreach($free as $row){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				return $free;
			break;
		}
		local_disconnect();
	}
	
	public static function get_assigned_team_members($team_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `users`.* FROM `users`, `team_assignment` WHERE `users`.`user_id` = `team_assignment`.`user_id` && `team_id` = '$team_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				while($row = mysqli_fetch_assoc($result)){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_nonteam_members($team_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `team_assignment`.`user_id` FROM `team_assignment` WHERE `team_id` = '$team_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$assigned = array(); $free = array();
		while($row = mysqli_fetch_assoc($result)){
			$assigned[] = $row['user_id'];
		}		$sql = "SELECT * FROM `users`";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		while($row = mysqli_fetch_assoc($result)){
			if(!in_array($row['user_id'],$assigned)){ $free[] = $row; }
		}
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				foreach($free as $row){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				return $free;
			break;
		}
		local_disconnect();
	}
	
	public static function get_assigned_queue_members($queue_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `users`.* FROM `users`, `queue_assignment` WHERE `users`.`user_id` = `queue_assignment`.`user_id` && `queue_id` = '$queue_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				while($row = mysqli_fetch_assoc($result)){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function get_nonqueue_members($queue_id, $format='', $none=0){
		local_connect();
		$sql = "SELECT DISTINCT `queue_assignment`.`user_id` FROM `queue_assignment` WHERE `queue_id` = '$queue_id';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$assigned = array(); $free = array();
		while($row = mysqli_fetch_assoc($result)){
			$assigned[] = $row['user_id'];
		}		$sql = "SELECT * FROM `users`";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		while($row = mysqli_fetch_assoc($result)){
			if(!in_array($row['user_id'],$assigned)){ $free[] = $row; }
		}
		switch ($format){
			case 'option':
				($none==0?$html = "<option value=\"0\" selected=\"selected\">None</option>\n":NULL);
				foreach($free as $row){
					$html = $html."<option value=\"$row[user_id]\">$row[name]</option>\n";
				}
				return $html;
			break;
			default:
				return $free;
			break;
		}
		local_disconnect();
	}
	
	public static function get_client_extensions($format='', $clientID){
		if(empty($clientID)){ return NULL; }
		local_connect();
		$sql = "SELECT `ext_assignment`.`id`,`extension`,`client` as `clientID`,`company_name` as `client` FROM ext_assignment,clients WHERE `ext_assignment`.`client` = $clientID AND `clients`.`id` = $clientID";
		//echo $sql;
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		switch ($format){
			case 'option':
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					$html = $html."<option value=\"$row[id]\">$row[extension]</option>\n";
				}
				return $html;
			break;
			case 'table':
				$headers = array();
				$header[] = "Ext/Number";
				$header[] = "Remove";
				$header[] = "Edit";
				$html = array();
				$html[] = "<div id=\"extListTable\"><table border=\"0\" cellspacing=\"1\" id=\"extList\" class=\"formList\" width=\"100%\">\n<thead>\n\t<tr>\n";
				foreach($header as $val){
					$html[] = "\t<th class=\"header\">".$val."</th>\n";
				}
				$html[] = "</tr></thead>\n<tbody>"; $i=0;
				while($row = mysqli_fetch_assoc($result)){
					$row = self::desanitize_array($row);
					if($i == 1){ $odd = "class=\"odd links\""; $i = -1; } else{ $odd = "class=\"links\"";}
					$v = array("","");
					//We would ideally like to use the foreach loop to place the table data, but unfortunately the database isn't built in the order of the table headers.
					$html[] = "<tr $odd>\n\t<td>$v[0]$row[extension]</td>\n";
					$html[] = "\t<td><span class=\"delete\" onclick=\"deleteClientExt('$row[id]', '".addslashes(htmlspecialchars_decode($row['extension'],ENT_QUOTES))."','$row[clientID]')\"/></td>\n";
					$html[] = "\t<td><span class=\"edit ui-icon-pencil\" onclick=\"editExt('$row[id]','$row[clientID]')\"/></td>\n";
					$html[] = "</tr>\n"; $i++;
				}
				$html[] = "</tbody></table></div>";
				return implode("\n",$html);
			break;
			default:
				$array = array();
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			break;
		}
		local_disconnect();
	}
	
	public static function build_form($guts){
		$obj = new user();
		$perms = $obj->userPerms($_SESSION['user_id']);
		$html = array();
		$html[] = "<form action=\"#\" method=\"post\" name=\"gen_form\">";
		$html[] = $guts;
		$html[] = "<div style=\"clear:both; border-top:thin solid gray;\"></div><p>";
		if(is_array($perms)){
			$html[] = ($perms['xfer_call'] == 1 ? "<div class=\"transfer\" style=\"float:left;\">| Transfer |</div>":NULL);
			$html[] = ($perms['park_call'] == 1 ? "<div style=\"float:left;\" class=\"hold\">| Hold |</div>" : NULL);
		}
        $html[] = "<div style=\"float:left;\" class=\"hangup\">| Hang Up |</div></p>";
      	$html[] = "<p align=\"right\"><input type=\"button\" name=\"Save\" id=\"save\" value=\"  Save Message \" class=\"buttons\"/></p>";
    	$html[] = "</div>";
		$html[] = "</form>";
		$html[] = self::build_alert_box("","Hold Call");
		return implode("\n",$html);
	}

}


?>