<?php
	//POST: event_id
	//returns:

	include("db.php");
	include("event.php");
	include("user.php");

	$sub = get_sub($db, $_COOKIE["login"]);
	if (!$sub) {
		die("Not logged in");
	}
	dismiss_invitation($db, $_POST["event_id"], $sub);
	signup($db, $sub, $_POST["event_id"]);
?>
