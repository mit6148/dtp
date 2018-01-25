<?php
	//POST: event_id
	//returns:

	include("db.php");
	include("event.php");
	include("user.php");

	if (!isset($_COOKIE["login_uid"])) {
		die("Not logged in");
	}

	$sub = get_sub($db, $_COOKIE["login_uid"]);
	dismiss_invitation($db, $_POST["event_id"], $sub);
	signup($db, $sub, $_POST["event_id"]);
?>
