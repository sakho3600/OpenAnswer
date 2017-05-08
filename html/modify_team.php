<?php
if(isset($_GET['teamID']) && is_numeric($_GET['teamID']) && $_GET['teamID'] != 0){
	$info = display::get_teams('','','','','','',$_GET['teamID']);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error TD:10"); exit();
		}
	}
	$info = sanitize_data::desanitize_array($info);
	if(isset($_POST['modify_team']) && $_POST['modify_team'] == 1){
		$errors = array();
		$validation = new validation();
		$rules = array();
		$rules[] = "required,name,A team name is required";
		(!empty($_POST['max_members']) ? $rules[] = "digits_only,max_members,The maximum team members must be numeric.":NULL);
		$errors = $validation->validateFields($_POST, $rules);
		if(!empty($errors)){
			$html = array();
			$html[] = "Please correct the following errors:";
			$html[] = "<ul>";
			foreach($errors as $error){
				$html[] = "<li>$error</li>";
			}
			$html[] = "</ul>";
			echo display::error_msg(implode("\n",$html),"L");
			exit();
		}
		else{
			$do = new action();
			$chance = $do->modify_team($_POST,$info['0']['id']);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("Team Modified Successfully.")."</div>";
				exit();
			}
			else{
				echo display::error_msg($chance);
				exit();
			}
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo display::build_confirm_box("Are you sure you want to <span style=\"font-weight:bold;color:red\">disable</span> the team <span id=\"tName\"></span>?<br />", "Confirm Disable");
	echo "<div style=\"float:right;\">".display::get_search('teams')."</div><br /><br />";
	echo display::get_teams('table','','1');
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_teams('table','','1','','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No team specified. <br />Please select another team and try again."); exit();
}
if(isset($_GET['rmv']) && $_GET['rmv']==1 && is_array($info)){
	$obj = new user();
	$action = $obj->delete_team($_SESSION['user_id'], $_GET['teamID']);
	if(!preg_match("/0x1/i",$action) && preg_match("/1x1/i", $action)){
		echo display::success_msg($action); 
		echo "<div style=\"float:right;\">".display::get_search('teams')."</div><br /><br />";
		echo display::get_teams('table','','1'); exit();
	}
	else{
		echo display::error_msg($action); exit();
	}
}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Modify Team</span>
    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225"><label for="name">Team Name:</label></td>
        <td colspan="2"><label for="max_members">Max Members:</label></td>
      </tr>
      <tr>
        <td><input type="text" name="name" id="name" value="<?=$info[0]['team_name']?>"/></td>
        <td colspan="2"><input type="text" name="max_members" id="max_members" value="<?=$info[0]['max_members']?>"/></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="defaultLeader">Default Team Leader</label></td>
        <td width="70" align="right"><label for="disabled">Disable Team</label></td>
        <td width="215"><input type="checkbox" name="disabled" id="disabled" <?= ($info[0]['active']==1?"checked=\"checked\"":"")?>/></td>
      </tr>
      <tr>
        <td>      
        	<select name="defaultLeader" id="defaultLeader"><?= display::get_users('option'); ?></select>
      	</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="modify_team" type="hidden" id="modify_team" value="1" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />