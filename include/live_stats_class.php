<?php

class live_stats{

	public function adandon_rate($chart = 0){
		local_connect();
		$sql = "SELECT (SELECT COUNT(*) FROM cdr WHERE `dstchannel` = '' AND `lastdata` LIKE '%variables_q.php%') AS `abandoned`, "
				."(SELECT COUNT(*) FROM cdr WHERE `dstchannel` != '' AND `lastdata` LIKE '%variables_q%') AS `total` LIMIT 1";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$totals = mysqli_fetch_assoc($result);
		}
		else{
			return "0x1 Error: Failed to retrieve abandon rate from database.";
		}
		if($chart == 1){
			$chart = new chart(370,200);
			$chart->drawPIE($totals, array("Calls Abandoned", "Calls Answered"));
		}
		else{
			return $totals;
		}
	}
	
	public function average_call_length($chart = 0){
		local_connect();
		$sql = "SELECT AVG(`billsec`) FROM cdr WHERE `lastdata` LIKE '%variables_q%'";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$totals = mysqli_fetch_row($result);
		}
		else{
			return "0x1 Error: Failed to retrieve average call length from database.";
		}
		if($chart == 1){
			$chart = new chart(370,200);
			$chart->drawBAR(array("Average Call Length"=>$totals['0']), array("y_axis_label"=>"AVG Lenth","y_axis_unit"=>"sec"));
		}
		else{
			return $totals;
		}
	}
	
	public function repeat_call_distribution($chart = 0){
		local_connect();
		$sql = "SELECT `clid`, COUNT(`clid`) as `repeats` FROM cdr WHERE `lastdata` LIKE '%variables_q%' GROUP BY `clid` HAVING (COUNT(`clid`)>1)";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$totals = array();
			$callers = array();
			while($row = mysqli_fetch_assoc($result)){;
				$callers[] = $row['clid'];
				$totals[] = $row['repeats'];
			}
		}
		else{
			return "0x1 Error: Failed to retrieve repeat caller distribution from database.";
		}
		//array_walk($totals,'multiply',10);
		$n = count($totals);
		$numClasses=1;
		$k=0;
		while($n>$k){
			$k = pow(2,$numClasses);
			$numClasses++;
		}
		$numClasses % 2 ? NULL : $numClasses++;
		//$spread = ceil((($c*$numClasses)-($max-$min))/2);
		$numClasses++;
		$h = new Histogram($totals,$numClasses);
		$min = min($totals);
		$max = max($totals);
		$i = count($totals);
		foreach($h->getBins() as $key => $val){
			$data[] = $val;
			$hxaxis[] = '|'.round($key,1)." - ".round(($key+$h->delta),1).'|';
		}
		if($chart == 1){
			$chart = new chart(370,200);
			$chart->drawBAR(array("Repeat Caller Distribution"=>$data), array("y_axis_label"=>"No. of Calls","x_axis_ticks"=>$hxaxis));
		}
		else{
			return $data;
		}
	}
	
	public function hold_time_distribution($chart = 0){
		local_connect();
		$sql = "SELECT (`duration`-`billsec`) as hold_time FROM cdr WHERE `lastdata` LIKE '%variables_q%' AND (`duration`-`billsec`) > 0;";
		$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		if($result){
			$totals = array();
			$callers = array();
			while($row = mysqli_fetch_assoc($result)){;
				$totals[] = $row['hold_time'];
			}
		}
		else{
			return "0x1 Error: Failed to retrieve hold time distribution from database.";
		}
		//array_walk($totals,'multiply',10);
		$n = count($totals);
		$numClasses=1;
		$k=0;
		while($n>$k){
			$k = pow(2,$numClasses);
			$numClasses++;
		}
		$numClasses % 2 ? NULL : $numClasses++;
		//$spread = ceil((($c*$numClasses)-($max-$min))/2);
		$numClasses++;
		$h = new Histogram($totals,4);
		$min = min($totals);
		$max = max($totals);
		$i = count($totals);
		foreach($h->getBins() as $key => $val){
			$data[] = $val;
			$hxaxis[] = '|'.round($key,1)." - ".round(($key+$h->delta),1).'|';
		}
		if($chart == 1){
			$chart = new chart(370,200);
			$chart->drawBAR(array("Caller Hold-time Distribution"=>$data), array("y_axis_label"=>"No. of Callers","x_axis_ticks"=>$hxaxis,"x_axis_label"=>"Time spend on hold"));
		}
		else{
			return $data;
			//return implode(",",$totals);
		}
	}

}


?>