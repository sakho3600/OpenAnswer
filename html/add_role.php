<?php
if(isset($_POST['add_role_form']) && $_POST['add_role_form'] == 1){
	$errors = array();
	$validation = new validation();
	$rules = array();
	$rules[] = "required,title,A role name is required";
	$rules[] = "required,level,A default level is required.";
	$rules[] = "digits_only,level,The level must be numeric.";
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
		$chance = $do->add_role($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("Role Added Successfully.")."</div>";
			exit();
		}
		else{
			echo display::error_msg($chance);
			exit();
		}
	}
}
?><div id="msg"></div>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Add Role</span></legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0" id="permTable">
      <tr>
        <td width="225"><label for="title">Role Name:</label></td>
        <td colspan="2"><label for="level">Level:</label></td>
      </tr>
      <tr>
        <td><input type="text" name="title" id="title" /></td>
        <td><input type="text" name="level" id="level" /></td>
        <td align="center"><label>
          <input type="button" name="checkAll" id="checkAll" value="Check All" />
        </label></td>
      </tr>
      <tr>
        <td colspan="3"><input name="add_role_form" type="hidden" id="add_role_form" value="1" /></td>
      </tr>
      <tr>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>User</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="add_user">Add Users</label></td><td width="25"><input name="add_user" type="checkbox" id="add_user" value="1" /></td></tr>
                <tr><td><label for="delete_user">Delete Users</label></td><td><input type="checkbox" name="delete_user" id="delete_user" value="1" /></td></tr>
                <tr><td><label for="remove_user">Remove Users</label></td><td> <input type="checkbox" name="remove_user" id="remove_user" value="1" /></td></tr>
                <tr><td><label for="modify_user">Modify Users</label></td><td><input type="checkbox" name="modify_user" id="modify_user" value="1" /></td></tr>
                <tr><td><label for="view_user_details">View User Details</label></td><td><input type="checkbox" name="view_user_details" id="view_user_details" value="1" /></td></tr>
                <tr><td><label for="add_role">Add Roles</label></td><td><input type="checkbox" name="add_role" id="add_role" value="1" /></td></tr>
                <tr><td><label for="delete_role">Delete Roles</label></td><td><input type="checkbox" name="delete_role" id="delete_role" value="1" /></td></tr>
                <tr><td><label for="modify_role">Modify Roles</label></td><td><input type="checkbox" name="modify_role" id="modify_role" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Clients</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="add_client">Add Clients</label></td><td width="25"><input type="checkbox" name="add_client" id="add_client" value="1" /></td></tr>
                <tr><td><label for="delete_client">Delete Clients</label></td><td><input type="checkbox" name="delete_client" id="delete_client" value="1" /></td></tr>
                <tr><td><label for="remove_client">Remove Clients</label></td><td> <input type="checkbox" name="remove_client" id="remove_client" value="1" /></td></tr>
                <tr><td><label for="modify_client">Modify Clients</label></td><td><input type="checkbox" name="modify_client" id="modify_client" value="1" /></td></tr>
                <tr><td><label for="view_client_details">View Client Details</label></td><td><input type="checkbox" name="view_client_details" id="view_client_details" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Call Management</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="park_call">Park Calls</label></td><td width="25"><input type="checkbox" name="park_call" id="park_call" value="1" /></td></tr>
                <tr><td><label for="park_other_calls">Park Other's Calls</label></td><td><input type="checkbox" name="park_other_calls" id="park_other_calls" value="1" /></td></tr>
                <tr><td><label for="record_calls">Record Calls</label></td><td> <input name="record_calls" type="checkbox" id="record_calls" value="1" /></td></tr>
                <tr><td><label for="record_other_calls">Record Other's Calls</label></td><td><input type="checkbox" name="record_other_calls" id="record_other_calls"value="1"  /></td></tr>
                <tr><td><label for="xfer_call">Transfer Calls</label></td><td><input type="checkbox" name="xfer_call" id="xfer_call" value="1" /></td></tr>
                <tr><td><label for="xfer_other_call">Transfer Other's Calls</label></td><td><input type="checkbox" name="xfer_other_call" id="xfer_other_call" value="1" /></td></tr>
                <tr><td><label for="barge_calls">Monitor Calls</label></td><td><input type="checkbox" name="barge_calls" id="barge_calls" value="1" /></td></tr>
                </tbody></table>
            </fieldset><br />
        </td>
      </tr>
      <tr>
      <!-- Row Two -->        
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Recordings</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="play_record">Review Recordings</label></td><td width="25"><input type="checkbox" name="play_record" id="play_record" value="1" /></td></tr>
                <tr><td><label for="delete_record">Delete Recordings</label></td><td><input type="checkbox" name="delete_record" id="delete_record" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Teams</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="add_team">Add Teams</label></td><td width="25"><input type="checkbox" name="add_team" id="add_team" value="1" /></td></tr>
                <tr><td><label for="delete_team">Delete Teams</label></td><td><input type="checkbox" name="delete_team" id="delete_team" value="1" /></td></tr>
                <tr><td><label for="remove_team">Remove Teams</label></td><td> <input type="checkbox" name="remove_team" id="remove_team" value="1" /></td></tr>
                <tr><td><label for="modify_team">Modify Teams</label></td><td><input type="checkbox" name="modify_team" id="modify_team" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Queue Management</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="add_queue">Add Queues</label></td><td width="25"><input type="checkbox" name="add_queue" id="add_queue" value="1" /></td></tr>
                <tr><td><label for="delete_queue">Delete Queues</label></td><td><input type="checkbox" name="delete_queue" id="delete_queue" value="1" /></td></tr>
                <tr><td><label for="modify_queue">Modify Queues</label></td><td> <input type="checkbox" name="modify_queue" id="modify_queue" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
      </tr><br />
      <!-- Row Three -->
      <tr>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Live Calls/Status</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="view_all_live_calls">View All Live Calls</label></td><td width="25"><input type="checkbox" name="view_all_live_calls" id="view_all_live_calls" value="1" /></td></tr>
                <tr><td><label for="view_team_live_calls">View Team Live Calls</label></td><td><input type="checkbox" name="view_team_live_calls" id="view_team_live_calls" value="1" /></td></tr>
                <tr><td><label for="view_all_agent_status">View All Agent Status</label></td><td><input type="checkbox" name="view_all_agent_status" id="view_all_agent_status" value="1" /></td></tr>
                <tr><td><label for="view_team_agent_status">View Team Agent Status</label></td><td><input type="checkbox" name="view_team_agent_status" id="view_team_agent_status" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Live Stats</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="view_all_live_stats">View All Stats</label></td><td width="25"><input type="checkbox" name="view_all_live_stats" id="view_all_live_stats" value="1" /></td></tr>
                <tr><td><label for="view_team_agent_status">View Team Stats</label></td><td><input type="checkbox" name="view_team_live_stats" id="view_team_live_stats" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>CDR</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="view_all_cdr">View All CDR</label></td><td width="25"><input type="checkbox" name="view_all_cdr" id="view_all_cdr" value="1" /></td></tr>
                <tr><td><label for="view_team_cdr">View Team CDR</label></td><td><input type="checkbox" name="view_team_cdr" id="view_team_cdr" value="1" /></td></tr>
                <tr><td><label for="view_own_cdr">View Own CDR</label></td><td> <input type="checkbox" name="view_own_cdr" id="view_own_cdr" value="1" /></td></tr>
                </tbody></table>
            </fieldset>
        </td>
      </tr>
      <!-- Row Four -->
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