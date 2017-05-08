<?php
if(isset($_POST['change_settings']) && $_POST['change_settings'] == 1){
	$errors = array();
	$validation = new validation();
	$rules = array();
	$rules[] = "required,curPword,Your current password is required.";
	$rules[] = "required,password,A new password is required.";
	$rules[] = "length>=5,password,Your new password must be at least five (5) characters long";
	$rules[] = "required,confirm_password,Please confirm your new password.";
	$rules[] = "same_as,password,confirm_password,Your new password and password confirmation do not match. Ensure you have inputed "
	."the same password in the new password field and the confirm password field.";
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
		$chance = $do->change_settings($_POST);
		if($chance === TRUE){
			echo "<div id=\"success\">".display::success_msg("User Settings Updated Successfully.")."</div>";
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
    <legend><span style="font-weight:bold;">Change Password</span>
    </legend><table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225"><label for="curPword">Current Password</label></td>
        <td colspan="2"><label for="password">New Password</label></td>
      </tr>
      <tr>
        <td><input type="password" name="curPword" id="curPword" /></td>
        <td colspan="2"><input type="password" name="password" id="password" /></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><label for="confirm_password">Confirm New Password</label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><input type="password" name="confirm_password" id="confirm_password" /></td>
      </tr>
      <tr>
        <td colspan="3"><input name="change_settings" type="hidden" id="change_settings" value="1" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td width="215"><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />