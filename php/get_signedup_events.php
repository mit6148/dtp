<?php
	//GET
	//returns: signed up event details as JSON
	
	include("db.php");
	include("user.php");
	include("event.php");

	$sub = get_sub($db, $_COOKIE["login_uid"]);
	$user_signedup_events = get_user_signedup_events($db, $sub);
	echo json_encode($user_signedup_events);
?>