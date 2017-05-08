#!/usr/bin/php -q
<?php 
require('config.php');
require('phpagi.php'); 
$agi = new AGI(); 
$unique_id = md5($agi->agi_extension); 
cdr_connect();
$sql = "SELECT `meetme_room`, FROM axfer WHERE `unique_id`='$unique_id'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
if(mysqli_num_rows($result)==1){
   $row = mysqli_fetch_array($result);
   $agi->exec('MeetMe', '$row[0],1dMq');
}
else{
    $agi->exec('Playback', 'invalid');
    $agi->exec('Playback', 'vm-goodbye');
}

((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

?>
