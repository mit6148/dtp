<?php
	//POST: event_id
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");

	$sub = get_sub($db, $_COOKIE["login_uid"]);
	cancel_signup($db, $sub, $_POST["event_id"]);
?>