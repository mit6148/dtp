<?php
	//GET: event_id
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);

	echo delete_event($db, $_POST["event_id"], $user_sub);
?>