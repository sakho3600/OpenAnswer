<?php
if(isset($_POST['add_team']) && $_POST['add_team'] == 1){
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
		$chance = $do->add_team($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("Team Added Successfully.")."</div>";
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
    <legend><span style="font-weight:bold;">Add Team</span>
    </legend><table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225"><label for="name">Team Name:</label></td>
        <td colspan="2"><label for="max_members">Max Members:</label></td>
      </tr>
      <tr>
        <td><input type="text" name="name" id="name" /></td>
        <td colspan="2"><input type="text" name="max_members" id="max_members" /></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="defaultLeader">Default Team Leader</label></td>
        <td width="70" align="right"><label for="disabled">Disable Team</label></td>
        <td width="215"><input name="disabled" type="checkbox" id="disabled" value="1" /></td>
      </tr>
      <tr>
        <td>      
        	<select name="defaultLeader" id="defaultLeader"><?= display::get_users('option'); ?></select>
      	</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="add_team" type="hidden" id="add_team" value="1" /></td>
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