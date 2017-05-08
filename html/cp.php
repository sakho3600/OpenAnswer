<div class="topContainer">
    <div class="header"><a href="<?=HOME_PAGE?>"><img src="../images/logo_sm.jpg" height="75" width="200" style="float:left;" class="headerLogo" /></a></div>
    <div class="tabB" id="tabs"><?= display::show_tabs(USER_ID); ?>
    </div>
    <div id="container" class="container">
    <?php if(!isset($_GET['cpg']) || $_GET['cpg'] == 1){ 
	if($_SESSION['active'] === TRUE){$_SESSION['active'] = action::logout_of_queues($_SESSION['user_id'],$_SESSION['extension'],$_SESSION['channel']);}
	?>
        <div class="sideBar" style="padding-top:5px;"><?= display::show_sidebar(USER_ID); ?>
		</div>
        <div class="mainContain">                
            <div class="navTree"><?= display::show_nav(CURRENT_PAGE); ?></div><br />
            <div class="mainPage" id="mainPage"><?= page::get_page_contents(CURRENT_PAGE); ?>
            <!-- Hello, You've reached the bottom of the container. -->
            </div>
        </div>
    <?php } ?>
    <?php if($_GET['cpg'] == 2){ 
		$_SESSION['active'] = action::login_to_queues($_SESSION['user_id'],$_SESSION['extension'],$_SESSION['channel']);
	?>
		<script type="text/javascript" src="scripts/default_hotkeys.js"></script>
        <?php echo "<script type=\"text/javascript\" src=\"scripts/call_manager.js\"></script>"?>
        <div id="mainContain">
        <div id="script" class="script"></div>
        <div id="top" class="top"></div>
        <a href="#" id="view_script" class="drop_button" onclick="show_script()">View Script</a>            
        </div>
        <div id="form"></div><div class="links"><a href="#" onclick="pauseAgent()" id="pauseCalls">Pause Calls</a></div>
    <?php } ?>
    </div>
</div>