<?php
if(isset($_GET['clientID']) && is_numeric($_GET['clientID']) && $_GET['clientID'] != 0){
	$obj = new client();
	$info = $obj->clientInfoAll($_GET['clientID'], 11);
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
	echo "<div style=\"float:right;\">".display::get_search('clients')."</div><br /><br />";
	echo display::get_clients('table');
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_clients('table','','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No user specified. <br />Please select another user and try again."); exit();
}
?>
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Client Details</span></legend>
    <span style="float:right; font-weight:bold;" class="links"><a href="#" id="editUser"  onclick="editClient('<?=$info['id']?>')">Edit</a></span><br />
    <table width="510" border="0" cellpadding="10" cellspacing="0">
      <tr class="sample">
        <td width="225"><strong>Company Name</strong></td>
        <td colspan="2"><?=$info['company_name']?></td>
      </tr>
      <tr class="sample">
        <td><strong>Contact Name</strong></td>
        <td colspan="2"><?=$info['contact']?></td>
      </tr>
      <tr class="sample">
        <td><strong>Physical Address</strong></td>
        <td colspan="2"><?=$info['phy_address']?></td>
      </tr>
      <tr class="sample">
        <td height="32"><strong>City:</strong><?=$info['phy_city']?></td>
        <td width="70"><strong>State</strong>:
<?=$info['phy_state']?></td>
        <td width="215"><strong>Zip</strong>:
<?=$info['phy_zip']?></td>
      </tr>
      <tr class="sample">
        <td><strong>Phone</strong>:
<?=$info['phone']?></td>
        <td><strong>Fax</strong>:
<?=$info['fax']?></td>
        <td><strong>E-mail:</strong><?=$info['email']?></td>
      </tr>
      <tr class="sample">
        <td><strong>Mailing Address</strong></td>
        <td colspan="2"><?=$info['mail_address']?></td>
      </tr>
      <tr class="sample">
        <td height="32"><strong>City:</strong><?=$info['mail_city']?></td>
        <td><strong>State:</strong><?=$info['mail_state']?></td>
        <td><strong>Zip:</strong><?=$info['mail_zip']?></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
</fieldset>
<p />