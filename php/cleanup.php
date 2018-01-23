<?php
	//script that runs daily
	//cleans up old events and logins

	include("db.php");
	include("user.php");
	include("event.php");

	login_cleanup($db);
?>
