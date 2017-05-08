#!/usr/bin/php -q
<?php 
require('config.php');
require('phpagi.php'); 
$agi = new AGI(); 
$unique_id = md5($agi->agi_extension); 
cdr_connect();
$sql = "SELECT `meetme_room`, FROM axfer WHERE `unique_id`='$unique_id'";
$result = mysql_query($sql);
if(mysql_num_rows($result)==1){
   $row = mysql_fetch_array($result);
   $agi->exec('MeetMe', '$row[0],1dMq');
}
else{
    $agi->exec('Playback', 'invalid');
    $agi->exec('Playback', 'vm-goodbye');
}

mysql_close();

?>
