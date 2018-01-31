<?php
	//POST: course, assignment, location, date, start_time, end_time
	//returns: none

	include("db.php");
	include("user.php");
	include("event.php");


	$sub = get_sub($db, $_COOKIE["login"]);

	if (!$sub) {
		die("Not logged in");
	}

	$event_stmt = $db->prepare("INSERT INTO events (owner_sub, course, assignment, location, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");

	$start_datetime = date_create_from_format("Y-m-d H:i", $_POST["date"] . " " .  $_POST["start_time"]);
	$end_datetime = date_create_from_format("Y-m-d H:i", $_POST["date"] . " " .  $_POST["end_time"]);
	if ($start_datetime > $end_datetime) {
		date_add($end_datetime, date_interval_create_from_date_string('1 day'));
	}
	$event_stmt->execute(array(
		$sub,
		strip_tags($_POST["course"]),
		strip_tags($_POST["assignment"]),
		strip_tags($_POST["location"]),
		date_timestamp_get($start_datetime),
		date_timestamp_get($end_datetime)
	));
	$event_id_stmt = $db->query("SELECT LAST_INSERT_ID()");
	$row = $event_id_stmt->fetch(PDO::FETCH_ASSOC);
	$event_id = $row["LAST_INSERT_ID()"];

	signup($db, $sub, $event_id);
	echo $event_id;
?>
