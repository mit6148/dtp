<?php
	//GET: event_id
	//returns: 1 if signed up, 0 if not signed up

	include("db.php");
	include("user.php");
	include("event.php");

	$sub = get_sub($db, $_COOKIE["login_uid"]);
	echo (is_signed_up($db, $sub, $_GET["event_id"])) ? 1 : 0;
?>