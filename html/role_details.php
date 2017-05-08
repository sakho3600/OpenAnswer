<?php
if(isset($_GET['roleID']) && is_numeric($_GET['roleID']) && $_GET['roleID'] != 0){
	$info = display::get_roles('','','','','',$_GET['roleID']);
	local_connect();
	$perms = mysqli_fetch_assoc(mysqli_query($GLOBALS["___mysqli_ston"],"SELECT * FROM `role_perm` WHERE `role_id` = '".$info['0']['id']."' LIMIT 1"));
	local_disconnect();
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error RD:10"); exit();
		}
	}
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo "<div style=\"float:right;\">".display::get_search('roles')."</div><br /><br />";
	echo display::get_roles('table','',1);
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_roles('table','',1,'',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No role specified. <br />Please select another role and try again."); exit();
}
?>
<form id="add_user" name="add_user" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Role Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editRole"  onclick="editRole('<?=$info['0']['id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="20" cellspacing="0" id="permTable">
      <tr>
        <td width="225"><label for="roleName">Role Name:
          <?=$info[0]['title']?>
        </label></td>
        <td colspan="2"><label for="level">Level:
          <?=$info[0]['level']?>
        </label></td>
      </tr>
      <tr>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>User</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="addUsers">Add Users</label></td><td width="25"><input type="checkbox" disabled="disabled" name="addUsers" id="addUsers" <?=($perms['add_user']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="deleteUsers">Delete Users</label></td><td><input type="checkbox" disabled="disabled" name="deleteUsers" id="deleteUsers" <?=($perms['delete_user']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="removeUsers">Remove Users</label></td><td> <input type="checkbox" disabled="disabled" name="removeUsers" id="removeUsers" <?=($perms['remove_user']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="modUsers">Modify Users</label></td><td><input type="checkbox" disabled="disabled" name="modUsers" id="modUsers" <?=($perms['modify_user']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewUsers">View User Details</label></td><td><input type="checkbox" disabled="disabled" name="viewUsers" id="viewUsers" <?=($perms['view_user_details']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="addRoles">Add Roles</label></td><td><input type="checkbox" disabled="disabled" name="addRoles" id="addRoles" <?=($perms['add_role']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="deleteRoles">Delete Roles</label></td><td><input type="checkbox" disabled="disabled" name="deleteRoles" id="deleteRoles" <?=($perms['delete_role']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="modRoles">Modify Roles</label></td><td><input type="checkbox" disabled="disabled" name="modRoles" id="modRoles" <?=($perms['modify_role']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Clients</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="addClients">Add Clients</label></td><td width="25"><input type="checkbox" disabled="disabled" name="addClients" id="addClients" <?=($perms['add_client']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="deleteClients">Delete Clients</label></td><td><input type="checkbox" disabled="disabled" name="deleteClients" id="deleteClients" <?=($perms['delete_client']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="removeClients">Remove Clients</label></td><td> <input type="checkbox" disabled="disabled" name="removeClients" id="removeClients" <?=($perms['remove_client']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="modClients">Modify Clients</label></td><td><input type="checkbox" disabled="disabled" name="modClients" id="modClients" <?=($perms['modify_client']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewClients">View Client Details</label></td><td><input type="checkbox" disabled="disabled" name="viewClients" id="viewClients" <?=($perms['view_client_details']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Call Management</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="parkCalls">Park Calls</label></td><td width="25"><input type="checkbox" disabled="disabled" name="parkCalls" id="parkCalls" <?=($perms['park_call']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="parkOthers">Park Other's Calls</label></td><td><input type="checkbox" disabled="disabled" name="parkOthers" id="parkOthers" <?=($perms['park_other_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="recCalls">Record Calls</label></td><td> <input type="checkbox" disabled="disabled" name="recCalls" id="recCalls" <?=($perms['record_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="recOthers">Record Other's Calls</label></td><td><input type="checkbox" disabled="disabled" name="recOthers" id="recOthers" <?=($perms['record_other_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="xferCalls">Transfer Calls</label></td><td><input type="checkbox" disabled="disabled" name="xferCalls" id="xferCalls" <?=($perms['xfer_call']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="xferOthers">Transfer Other's Calls</label></td><td><input type="checkbox" disabled="disabled" name="xferOthers" id="xferOthers" <?=($perms['xfer_other_call']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="monCalls">Monitor Calls</label></td><td><input type="checkbox" disabled="disabled" name="monCalls" id="monCalls" <?=($perms['barge_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
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
                <tr><td width="84"><label for="playRecs">Review Recordings</label></td><td width="25"><input type="checkbox" disabled="disabled" name="playRecs" id="playRecs" <?=($perms['play_record']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="delRecs">Delete Recordings</label></td><td><input type="checkbox" disabled="disabled" name="delRecs" id="delRecs" <?=($perms['delete_record']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Teams</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="addTeams">Add Teams</label></td><td width="25"><input type="checkbox" disabled="disabled" name="addTeams" id="addTeams" <?=($perms['add_team']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="deleteTeams">Delete Teams</label></td><td><input type="checkbox" disabled="disabled" name="deleteTeams" id="deleteTeams" <?=($perms['delete_team']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="removeTeams">Remove Teams</label></td><td> <input type="checkbox" disabled="disabled" name="removeTeams" id="removeTeams" <?=($perms['remove_team']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="modTeams">Modify Teams</label></td><td><input type="checkbox" disabled="disabled" name="modTeams" id="modTeams" <?=($perms['modify_team']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Queue Management</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="addQueues">Add Queues</label></td><td width="25"><input type="checkbox" disabled="disabled" name="addQueues" id="addQueues" <?=($perms['add_queue']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="delQueues">Delete Queues</label></td><td><input type="checkbox" disabled="disabled" name="delQueues" id="delQueues" <?=($perms['delete_queue']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="modQueues">Modify Queues</label></td><td> <input type="checkbox" disabled="disabled" name="modQueues" id="modQueues" <?=($perms['modify_queue']==1?"checked=\"checked\"":"")?>/></td></tr>
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
                <tr><td width="84"><label for="viewAllLiveCalls">View All Live Calls</label></td><td width="25"><input type="checkbox" disabled="disabled" name="viewAllLiveCalls" id="viewAllLiveCalls" <?=($perms['view_all_live_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewTeamLiveCalls">View Team Live Calls</label></td><td><input type="checkbox" disabled="disabled" name="viewTeamLiveCalls" id="viewTeamLiveCalls" <?=($perms['view_team_live_calls']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewAllAgentStatus">View All Agent Status</label></td><td><input type="checkbox" disabled="disabled" name="viewAllAgentStatus" id="viewAllAgentStatus" <?=($perms['view_all_agent_status']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewTeamAgentStatus">View Team Agent Status</label></td><td><input type="checkbox" disabled="disabled" name="viewTeamAgentStatus" id="viewTeamAgentStatus" <?=($perms['view_team_agent_status']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>Live Stats</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="viewAllStats">View All Stats</label></td><td width="25"><input type="checkbox" disabled="disabled" name="viewAllStats" id="viewAllStats" <?=($perms['view_all_live_stats']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewTeamStats">View Team Stats</label></td><td><input type="checkbox" disabled="disabled" name="viewTeamStats" id="viewTeamStats" <?=($perms['view_team_live_stats']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset class="checkBoxFieldset">
              <legend>CDR</legend>
              <table width="150"><tbody>
                <tr><td width="84"><label for="viewAllCDR">View All CDR</label></td><td width="25"><input type="checkbox" disabled="disabled" name="viewAllCDR" id="viewAllCDR" <?=($perms['view_all_cdr']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewTeamCDR">View Team CDR</label></td><td><input type="checkbox" disabled="disabled" name="viewTeamCDR" id="viewTeamCDR" <?=($perms['view_team_cdr']==1?"checked=\"checked\"":"")?>/></td></tr>
                <tr><td><label for="viewOwnCDR">View Own CDR</label></td><td> <input type="checkbox" disabled="disabled" name="viewOwnCDR" id="viewOwnCDR" <?=($perms['view_own_cdr']==1?"checked=\"checked\"":"")?>/></td></tr>
                </tbody></table>
            </fieldset>
        </td>
      </tr>
      <!-- Row Four -->
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />