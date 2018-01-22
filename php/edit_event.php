<?php
	//POST: event_id, course, assignment, location, date, start_time, end_time
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);

	$update_stmt = $db->prepare("UPDATE events SET course = ?, assignment = ?, location = ?, date = ?, start_time = ?, end_time = ? WHERE event_id = ? AND owner_sub = ?");
	$update_stmt->execute(array(
		$_POST["course"],
		$_POST["assignment"],
		$_POST["location"],
		$_POST["date"],
		$_POST["start_time"],
		$_POST["end_time"],
		$_POST["event_id"],
		$user_sub
	));
?>