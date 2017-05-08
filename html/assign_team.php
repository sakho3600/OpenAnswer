<?php
if(isset($_POST['assign_team']) && $_POST['assign_team'] == 1){
	$errors = array();
	$validation = new validation();
	$rules = array();
	$rules[] = "required,team,Please select a team to assign members to.";
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
		$chance = $do->assign_team($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("Users Assigned Successfully.")."</div>";
			exit();
		}
		else{
			echo display::error_msg($chance);
			exit();
		}
	}
}
if(isset($_GET['optgrp']) && $_GET['optgrp'] == 1){
	if(isset($_GET['assgn']) && is_numeric($_GET['assgn'])){
		if(isset($_GET['teamID']) && is_numeric($_GET['teamID'])){
			echo display::get_assigned_team_members($_GET['teamID'],'option',1);
			exit();
		}
		else{
			echo "<option value=\"\">Invalid team selected</option>";
			exit();
		}
	}
	if(isset($_GET['unassgn']) && is_numeric($_GET['unassgn'])){
		if(isset($_GET['teamID']) && is_numeric($_GET['teamID'])){
			echo display::get_nonteam_members($_GET['teamID'],'option',1);  
			exit();
		}
		else{
			echo "<option value=\"\">Invalid team selected</option>";
			exit();
		}
	}
}
?><div id="msg"></div>
<form id="assign_teams" name="assign_teams" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Assign Team Members</span>    </legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td colspan="3"></label></td>
      </tr>
      <tr>
        <td colspan="3"><select name="team" id="team" onchange="getTeamMembers(this.value)"><?= display::get_teams('option'); ?></select></td>
      </tr>
      <tr>
        <td width="225" rowspan="2"><label for="list1"></label>
          <select name="list1" size="10" multiple="multiple" id="list1" style="width:250px;"></select></td>
        <td width="60">&nbsp;</td>
        <td width="225" rowspan="2"><select name="list2[]" size="10" multiple="multiple" id="list2" style="width:250px"></select></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><label for="add"></label>
        <input type="button" name="add" id="add" value="-&gt;" />
        <br />
        <input type="button" name="remove" id="remove" value="&lt;-" />
        <br />
        <br />
        <input type="button" name="add_all" id="add_all" value="&gt;&gt;" />
        <br />
        <input type="button" name="remove_all" id="remove_all" value="&lt;&lt;" /></td>
      </tr>
      <tr>
        <td><input name="assign_team" type="hidden" id="assign_team" value="1" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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