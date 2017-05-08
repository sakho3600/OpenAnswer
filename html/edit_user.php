<?php
if(isset($_GET['userID']) && is_numeric($_GET['userID']) && $_GET['userID'] != 0){
	$obj = new user();
	$info = $obj->userInfoAll($_GET['userID'], 6);
	if(!is_array($info)){
		if(preg_match("/0x1/i", $info)){
			echo display::error_msg($info); exit();
		}
		else{
			echo display::error_msg("Unknown Error Occured. Please contact your administrator and reference error UD:10"); exit();
		}
	}
	if(isset($_POST['modify_user']) && $_POST['modify_user'] == 1){
		$errors = array();
		$validation = new validation();
		$rules = array();
		$rules[] = "required,name,The User's name is required.";
		$rules[] = "required,user_name,A user name for login is required.";
		$rules[] = "required,extension,The user's phone extension is required.";
		$rules[] = "required,tech,Please select the type of phone the user will be utilizing.";
		$rules[] = "required,channel,Please input the channel name of the user.";
		$rules[] = "required,email_address,The email address is required.";
		$rules[] = "valid_email,email,The email address entered is invalid.";
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
			$_POST['userID'] = $info['user_id'];
			if($info['disabled'] == 1 && !isset($_POST['disabled'])){ $_POST['disabled'] = 0;}
			$do = new action();
			$chance = $do->modify_user($_POST);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("User Modified Successfully.")."</div>";
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
	echo "<div style=\"float:right;\">".display::get_search('users')."</div><br /><br />";
	echo display::get_users('table',1); exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_users('table',1,'','',$_GET['q']); 
	 exit();
}
else{
	echo display::error_msg("No user specified. <br />Please select another user and try again."); exit();
}
?><div id="msg"></div>
<form id="modify_user_form" name="modify_user_form" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Add User</span></legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225"><label for="name">Name:</label></td>
        <td colspan="2"><label for="email_address">E-mail Address:</label></td>
      </tr>
      <tr>
        <td><input type="text" name="name" id="name" value="<?=$info['name'];?>"/></td>
        <td colspan="2"><input type="text" name="email_address" id="email_address" value="<?=$info['email_address'];?>"/></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="user_name">User Name</label></td>
        <td colspan="2"><label for="pass">Password</label></td>
      </tr>
      <tr>
        <td><input type="text" name="user_name" id="user_name" value="<?=$info['user_name'];?>"/></td>
        <td colspan="2"><input type="text" name="pass" id="pass" /></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="extension">Extension</label></td>
        <td width="70"><label for="tech">Technology</label></td>
        <td width="215"><label for="channel">Channel</label></td>
      </tr>
      <tr>
        <td><input type="text" name="extension" id="extension" value="<?=$info['extension'];?>"/></td>
        <td>
          <select name="tech" id="tech">
            <option value="SIP" <? echo ($info['tech']=='SIP'? "selected=\"selected\"": "")?>>SIP</option>
            <option value="IAX2" <? echo ($info['tech']=='IAX2'? "selected=\"selected\"":"")?>>IAX2</option>
            <option value="ZAP" <? echo ($info['tech']=='ZAP'? "selected=\"selected\"":"")?>>ZAP</option>
            <option value="LOCAL" <? echo ($info['tech']=='Local'? "selected=\"selected\"":"")?>>Local</option>
          </select>
     	</td>
        <td><input type="text" name="channel" id="channel" value="<?=$info['channel'];?>"/></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="defaultRole">Default Role</label></td>
        <td align="right"><label for="disabled">Disable User</label></td>
        <td><input name="disabled" type="checkbox" id="disabled" value="1" <? echo ($info['disabled']==1? "checked=\"checked\"":"")?>/></td>
      </tr>
      <tr>
        <td>      
        	<select name="defaultRole" id="defaultRole"><?= display::get_roles('option'); ?></select>
      	</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="modify_user" type="hidden" id="modify_user" value="1" /></td>
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