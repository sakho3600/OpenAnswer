<?php
//error_reporting(0);
if(isset($_GET['chart'])){
	
	if($_GET['chart'] == 1){
		echo live_stats::adandon_rate(1);
	}
	
	if($_GET['chart'] == 2){
		echo live_stats::average_call_length(1);
	}
	
	if($_GET['chart'] == 3){
		print_r( live_stats::repeat_call_distribution(1));
	}

	if($_GET['chart'] == 4){
		print_r( live_stats::hold_time_distribution(1));
	}
	
}

?>