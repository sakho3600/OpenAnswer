<?php
/*phpinfo();
exit();*/
//error_reporting(E_ERROR);
session_start();
require('include/functions.php');
require('include/constants.php');
require('include/what_if.php');
set_page();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agent Answering Panel</title>
        <script type="text/javascript" src="scripts/jquery.js"></script>
        <script type="text/javascript" src="scripts/jquery-ui.js"></script>
        <?
        echo get_scripts::return_scripts(CURRENT_PAGE);
        echo get_scripts::return_css(CURRENT_PAGE);
        ?>
        
    </head>
    
    <body>
        <?php
			if(!@include(page::get_page_file(CURRENT_PAGE))){
				@include(page::get_page_file(0));
			}
        ?>
    </body>
</html>