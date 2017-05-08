#!/usr/bin/php -q
<?php 
require('config.php');
require('phpagi.php'); 
$agi = new AGI(); 
$unique_id = md5($agi->agi_extension); 
cdr_connect();
$sql = "SELECT `usr_ext`,`spy_channel` FROM call_barge WHERE `unique_id`='$unique_id'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
if(mysqli_num_rows($result)==1){
   $row = mysqli_fetch_array($result);
   $agi->exec('ChanSpy', '$row[spy_channel]|q');
}
else{
  $agi->exec('Playback', 'invalid_ext');
  $agi->exec('Playback', 'vm-goodbye');
}
((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

?>
