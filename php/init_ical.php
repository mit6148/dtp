<?php
	include("db.php");
	include("user.php");
	include("event.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$stmt = $db->prepare("SELECT ical_id FROM users WHERE sub = ?");
	$stmt->execute(array(
		$user_sub
	));
	$id = 