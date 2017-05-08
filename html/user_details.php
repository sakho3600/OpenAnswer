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
}
elseif(isset($_GET['list']) && $_GET['list']==1 && !isset($_GET['q'])){
	echo "<div style=\"float:right;\">".display::get_search('users')."</div><br /><br />";
	echo display::get_users('table'); exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_users('table','','','',$_GET['q']); 
	 exit();
}
else{
	echo display::error_msg("No user specified. <br />Please select another user and try again."); exit();
}
if(!empty($msg)){  } ?>
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">User Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editUser"  onclick="editUser('<?=$info['user_id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr>
      	<td colspan="3"><strong>Last Login</strong>: <? echo ($info['last_login']!="" ? date('r',$info['last_login']) : "Never");?></td>
      </tr>
      <tr>
      	<td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="225"><label for="name"><strong>Name</strong>:</label></td>
        <td colspan="2"><label for="email"><strong>E-mail Address</strong>:</label></td>
      </tr>
      <tr>
        <td><?=$info['name'];?></td>
        <td colspan="2"><?=$info['email_address'];?></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="userName"><strong>User Name</strong></label></td>
        <td colspan="2"><label for="password"><strong>Password</strong></label></td>
      </tr>
      <tr>
        <td><?=$info['user_name'];?></td>
        <td colspan="2">Encrypted</td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="ext"><strong>Extension</strong></label></td>
        <td width="98"><label for="tech"><strong>Technology</strong></label></td>
        <td width="187"><label for="channel"><strong>Channel</strong></label></td>
      </tr>
      <tr>
        <td><?=$info['extension'];?></td>
        <td><?=$info['tech'];?></td>
        <td><?=$info['channel'];?></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><label for="defaultRole"><strong>Default Role</strong></label></td>
        <td align="left"><strong>
          <label for="disabled">Disable User</label>
        </strong></td>
        <td align="center"><? echo ($info['disabled']==1 ? "Yes" : "No");?></td>
      </tr>
      <tr>
        <td><?=$info['role'];?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
<p />