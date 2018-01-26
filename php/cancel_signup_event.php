<?php
	//POST: event_id
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");

	$sub = get_sub($db, $_COOKIE["login"]);
	if (!$sub) {
		die("Not logged in");
	}
	cancel_signup($db, $sub, $_POST["event_id"]);
?>