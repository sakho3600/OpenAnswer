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
	$info = sanitize_data::desanitize_array($info);
	if(isset($_POST['modify_client']) && $_POST['modify_client'] == 1){
		$errors = array();
		$validation = new validation();
		$rules = array();
		$rules[] = "required,contact,A contact person is required";
		$rules[] = "required,phy_address,A physical street address is required.";
		$rules[] = "required,phy_city,The City of the physical address is required.";
		$rules[] = "required,phy_state,The state of the physical address is required.";
		$rules[] = "required,phy_zip,The ZIP code of the physical address is required.";
		$rules[] = "required,phone,A phone number is required.";
		$rules[] = "digits_only,phone,The phone number entered is invalid.";
		$rules[] = "length>=7,phone,The phone number must be at least five (5) digits.";
		(!empty($_POST['email'])?$rules[] = "valid_email,email,The email address entered is invalid.":NULL);
		if(!empty($_POST['fax'])){$rules[] = "required,fax,The fax number is required."; $rules[] = "digits_only,fax,The fax number entered is invalid."; $rules[] = "length>=7,fax,The fax number must be at least five (5) digits.";}
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
			$chance = $do->modify_client($info['client_number'],$_POST);
			if($chance === TRUE){
				echo "<div id=\"success\">".display::success_msg("Client Updated Successfully.")."</div>";
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
	echo "<div style=\"float:right;\">".display::get_search('clients')."</div><br /><br />";
	echo display::get_clients('table','1');
	exit();
}
elseif(isset($_GET['list']) && $_GET['list']==1 && isset($_GET['q'])){
	 echo display::get_clients('table','1','',$_GET['q']); 
	 exit();
}

else{
	echo display::error_msg("No client specified. <br />Please select another client and try again."); exit();
}
?><div id="msg"></div>
<form id="modify_client_form" name="modify_client_form" method="post" action="#">
  <fieldset class="formContainer">
    <legend><span style="font-weight:bold;">Modify Client</span></legend>
    <table width="510" border="0" cellpadding="0" cellspacing="0">
      <tr class="sample">
        <td>Company Name</td>
        <td colspan="2"><input name="company" type="text" id="company" value="<?=$info['company_name']?>" /></td>
      </tr>
      <tr class="sample">
        <td>Contact Name</td>
        <td colspan="2"><input name="contact" type="text" id="contact" value="<?=$info['contact']?>" /></td>
      </tr>
       <div id="phy_address_cont">
       <tr class="sample">     
        <td>Physical Address</td>
        <td colspan="2"><input name="phy_address" type="text" id="phy_address" value="<?=$info['phy_address']?>" /></td>
      </tr>
      <tr class="sample">
        <td height="32">City
          <input name="phy_city" type="text" id="phy_city" value="<?=$info['phy_city']?>" /></td>
        <td>State
          <select name="phy_state" id="phy_state">
          	<option value="<?=$info['phy_state']?>" selected="selected"><?=$info['phy_state']?></option>
            <option value="AL">ALABAMA</option>
            <option value="AK">ALASKA</option>
            <option value="AZ">ARIZONA</option>
            <option value="AR">ARKANSAS</option>
            <option value="CA">CALIFORNIA</option>
            <option value="CO">COLORADO</option>
            <option value="CT">CONNECTICUT</option>
            <option value="DC">DISTRICT OF COLUMBIA</option>
            <option value="DE">DELAWARE</option>
            <option value="FL">FLORIDA</option>
            <option value="GA">GEORGIA</option>
            <option value="GU">GUAM</option>
            <option value="HI">HAWAII</option>
            <option value="ID">IDAHO</option>
            <option value="IL">ILLINOIS</option>
            <option value="IN">INDIANA</option>
            <option value="IA">IOWA</option>
            <option value="KS">KANSAS</option>
            <option value="KY">KENTUCKY</option>
            <option value="LA">LOUISIANA</option>
            <option value="ME">MAINE</option>
            <option value="MD">MARYLAND</option>
            <option value="MA">MASSACHUSETTS</option>
            <option value="MI">MICHIGAN</option>
            <option value="MN">MINNESOTA</option>
            <option value="MS">MISSISSIPPI</option>
            <option value="MO">MISSOURI</option>
            <option value="MT">MONTANA</option>
            <option value="NE">NEBRASKA</option>
            <option value="NV">NEVADA</option>
            <option value="NH">NEW HAMPSHIRE</option>
            <option value="NJ">NEW JERSEY</option>
            <option value="NM">NEW MEXICO</option>
            <option value="NY">NEW YORK</option>
            <option value="NC">NORTH CAROLINA</option>
            <option value="ND">NORTH DAKOTA</option>
            <option value="OH">OHIO</option>
            <option value="OK">OKLAHOMA</option>
            <option value="OR">OREGON</option>
            <option value="PA">PENNSYLVANIA</option>
            <option value="RI">RHODE ISLAND</option>
            <option value="SC">SOUTH CAROLINA</option>
            <option value="SD">SOUTH DAKOTA</option>
            <option value="TN">TENNESSEE</option>
            <option value="TX">TEXAS</option>
            <option value="UT">UTAH</option>
            <option value="VT">VERMONT</option>
            <option value="VA">VIRGINIA</option>
            <option value="WA">WASHINGTON</option>
            <option value="WV">WEST VIRGINIA</option>
            <option value="WI">WISCONSIN</option>
            <option value="WY">WYOMING</option>
            <option value="APO/FPO">APO/FPO</option>
          </select></td>
        <td>Zip
          <input name="phy_zip" type="text" id="phy_zip" value="<?=$info['phy_zip']?>" size="10" /></td>
      </tr></div>
      <tr class="sample">
        <td>Phone
          <input name="phone" type="text" id="phone" value="<?=$info['phone']?>" /></td>
        <td>Fax
          <input name="fax" type="text" id="fax" value="<?=$info['fax']?>" /></td>
        <td>E-mail
          <input name="email" type="text" id="email" value="<?=$info['email']?>" /></td>
      </tr>
	  <tr ><td colspan="2" style="border-top:thin solid gray; border-bottom:thin solid gray"><label for="phy_mail_same">Check here if the mailing address is the same as the physical address.</label></td><td style="border-top:thin solid gray; border-bottom:thin solid gray"><input name="phy_mail_same" type="checkbox" id="phy_mail_same" value="1" /></td></tr>
      <div id="mail_address_cont">
      <tr class="sample">
        <td>Mailing Address</td>
        <td colspan="2"><input name="mail_address" type="text" id="mail_address" value="<?=$info['mail_address']?>" /></td>
      </tr>
      <tr class="sample">
        <td height="32">City
          <input name="mail_city" type="text" id="mail_city" value="<?=$info['mail_city']?>" /></td>
        <td>State
          <select name="mail_state" id="mail_state">
          	<option value="<?=$info['mail_state']?>" selected="selected"><?=$info['mail_state']?></option>
            <option value="AL">ALABAMA</option>
            <option value="AK">ALASKA</option>
            <option value="AZ">ARIZONA</option>
            <option value="AR">ARKANSAS</option>
            <option value="CA">CALIFORNIA</option>
            <option value="CO">COLORADO</option>
            <option value="CT">CONNECTICUT</option>
            <option value="DC">DISTRICT OF COLUMBIA</option>
            <option value="DE">DELAWARE</option>
            <option value="FL">FLORIDA</option>
            <option value="GA">GEORGIA</option>
            <option value="GU">GUAM</option>
            <option value="HI">HAWAII</option>
            <option value="ID">IDAHO</option>
            <option value="IL">ILLINOIS</option>
            <option value="IN">INDIANA</option>
            <option value="IA">IOWA</option>
            <option value="KS">KANSAS</option>
            <option value="KY">KENTUCKY</option>
            <option value="LA">LOUISIANA</option>
            <option value="ME">MAINE</option>
            <option value="MD">MARYLAND</option>
            <option value="MA">MASSACHUSETTS</option>
            <option value="MI">MICHIGAN</option>
            <option value="MN">MINNESOTA</option>
            <option value="MS">MISSISSIPPI</option>
            <option value="MO">MISSOURI</option>
            <option value="MT">MONTANA</option>
            <option value="NE">NEBRASKA</option>
            <option value="NV">NEVADA</option>
            <option value="NH">NEW HAMPSHIRE</option>
            <option value="NJ">NEW JERSEY</option>
            <option value="NM">NEW MEXICO</option>
            <option value="NY">NEW YORK</option>
            <option value="NC">NORTH CAROLINA</option>
            <option value="ND">NORTH DAKOTA</option>
            <option value="OH">OHIO</option>
            <option value="OK">OKLAHOMA</option>
            <option value="OR">OREGON</option>
            <option value="PA">PENNSYLVANIA</option>
            <option value="RI">RHODE ISLAND</option>
            <option value="SC">SOUTH CAROLINA</option>
            <option value="SD">SOUTH DAKOTA</option>
            <option value="TN">TENNESSEE</option>
            <option value="TX">TEXAS</option>
            <option value="UT">UTAH</option>
            <option value="VT">VERMONT</option>
            <option value="VA">VIRGINIA</option>
            <option value="WA">WASHINGTON</option>
            <option value="WV">WEST VIRGINIA</option>
            <option value="WI">WISCONSIN</option>
            <option value="WY">WYOMING</option>
            <option value="APO/FPO">APO/FPO</option>
          </select></td>
        <td>Zip
          <input name="mail_zip" type="text" id="mail_zip" value="<?=$info['mail_zip']?>" size="10" /></td>
      </tr></div>
      <tr>
        <td colspan="3"><input name="modify_client" type="hidden" id="modify_client" value="1" /></td>
      </tr>
      <tr>
        <td width="225">&nbsp;</td>
        <td width="70"><input class="buttons" type="button" name="save" id="save" value="  Save  " /></td>
        <td width="215"><input class="buttons" type="button" name="cancel" id="cancel" value="Cancel" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </fieldset>
</form>
<p />