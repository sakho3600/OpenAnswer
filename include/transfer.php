<?php
$user = new user();
$perms = $user->userPerms($_SESSION['user_id']);
if(is_array($perms)){
	if($perms['xfer_call'] == 1 || $perms['xfer_other_call'] == 1){
		
		if(isset($_POST['transfer_call']) && $_POST['transfer_call'] == 1){
			$errors = array();
			$validation = new validation();
			$rules = array();
			$rules[] = "required,extension,A phone number or extension is required.";
			$rules[] = "digits_only,extension,The phone number entered is invalid.";
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
				$chance = $do->transfer_call($_POST['extension'],$_SESSION['connectedCall']['callerChan']);
				if($chance === TRUE){
					echo "<div id=\"success\">".display::success_msg("Call Transferred Successfully.")."</div><br /><a href=\"javascript:closeSuccess()\">Close Window</a>";
					exit();
				}
				else{
					echo display::error_msg($chance);
					exit();
				}
			}
		}
	}
	else{
		echo "You do not have permission to transfer calls.";
	}
}
else{
	"An error occured with your request $perms";
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfer Call</title>
        <script type="text/javascript" src="scripts/jquery.js"></script>
        <script type="text/javascript" src="scripts/jquery-ui.js"></script>
		<script type="text/javascript" src="scripts/transfer.js"></script>
		<?
        echo get_scripts::return_css('');
        ?>
</head>
    <body style="background-color:#FFF">
    <div style="margin:0 auto; width:500px;">
    <div id="msg"></div>
    <form id="form1" method="post" action="">
      <table width="361" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><h2>Transfer Call To:</h2></td>
        </tr>
        <tr>
          <td width="183" valign="bottom">&nbsp;</td>
          <td width="178" valign="bottom">&nbsp;</td>
        </tr>
        <tr>
          <td valign="bottom"><strong>Phone Number/Extension</strong></td>
          <td valign="bottom"><input type="text" name="extension" id="extension" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="transfer_call" type="hidden" id="transfer_call" value="1" /></td>
        </tr>
        <tr>
          <td><input type="button" name="cancel" id="cancel" value="  Cancel  " /></td>
          <td><input type="button" name="transfer" id="transfer" value="  Transfer  " /></td>
        </tr>
      </table>
    </form>
    </div>
    </body>
</html>