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
}
else{
	echo display::error_msg("No team specified. <br />Please select another team and try again."); exit();
}
?>
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Team Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editTeam"  onclick="editTeam('<?=$info['0']['id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225"><label for="name"><strong>Team Name:</strong></label>          <?=$info[0]['team_name']?></td>
        <td colspan="2"><label for="max_members"><strong>Max Members</strong>: </label><?=$info[0]['max_members']?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="defaultLeader"><strong>Default Team Leader</strong></label></td>
        <td width="122"><label for="disabled"><strong>Team Disable</strong></label></td>
        <td width="163"><?= ($info[0]['active']==1?"Yes":"No")?></td>
      </tr>
      <tr>
        <td><?= display::get_team_leader($info['0']['id']); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><strong><br />
        Team Members:</strong></td>
      </tr>
      <tr>
        <td colspan="3"><?= display::get_team_members($info['0']['id']); ?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
<p />