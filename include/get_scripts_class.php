<?php
/*<script type=\"text/javascript\" src=\"scripts/jquery.easing.1.3.js\"></script>*/
class get_scripts{
	public static function return_scripts($page){
		switch ($page) {
			case 0:
				$scripts = "
					<script type=\"text/javascript\" src=\"scripts/vibrate.js\"></script>
					<script type=\"text/javascript\" src=\"scripts/login.js\"></script>
					";
			break;
			
			case 1:
				$scripts ="
					<script type=\"text/javascript\" src=\"scripts/interface.js\"></script>
					<script type=\"text/javascript\" src=\"scripts/tablesorter.js\"></script>
					<script type=\"text/javascript\" src=\"scripts/cp.js\"></script>
					<script type=\"text/javascript\" src=\"scripts/jquery.selectboxes.js\"></script>
				";
			break;
			
			case 2:
				$scripts = "
					<script type=\"text/javascript\" src=\"scripts/showCall.js\"></script>
				";
			break;
		}
		return $scripts;
	}
	
	public static function return_css($page){
		return	"
				<link rel=\"stylesheet\" type=\"text/css\" href=\"css/cp.css\" />\n
				<link href=\"css/main.css\" rel=\"stylesheet\" type=\"text/css\" />\n
				<link href=\"css/icons.css\" rel=\"stylesheet\" type=\"text/css\" />\n
				";
	}
	
}

?>


