<?php
	//POST: event_id, course, assignment, location, date, start_time, end_time
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);

	$start_datetime = date_create_from_format("Y-m-d H:i", $_POST["date"] . " " .  $_POST["start_time"]);
	$end_datetime = date_create_from_format("Y-m-d H:i", $_POST["date"] . " " .  $_POST["end_time"]);

	$update_stmt = $db->prepare("UPDATE events SET course = ?, assignment = ?, location = ?, start_time = ?, end_time = ? WHERE id = ? AND owner_sub = ?");
	$update_stmt->execute(array(
		$_POST["course"],
		$_POST["assignment"],
		$_POST["location"],
		date_timestamp_get($start_datetime),
		date_timestamp_get($end_datetime),
		$_POST["event_id"],
		$user_sub
	));
?>
