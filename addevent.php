<?php
	include("db.php");
	include("user.php");
	include("event.php");

	echo $_COOKIE["login_uid"];
	if (!is_valid_login_uid($db, $_COOKIE["login_uid"])) {
		die("Not logged in");
	}
	$sub = get_sub($db, $_COOKIE["login_uid"]);

	$event_stmt = $db->prepare("INSERT INTO events (owner_sub, course, assignment, location, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
	$event_stmt->execute(array(
		$sub,
		$_POST["course"],
		$_POST["assignment"],
		$_POST["location"],
		strtotime($_POST["date"] . $_POST["start_time"]),
		strtotime($_POST["date"] . $_POST["end_time"])
	));
	$event_id_stmt = $db->query("SELECT LAST_INSERT_ID()");
	$row = $event_id_stmt->fetch(PDO::FETCH_ASSOC);
	$event_id = $row["LAST_INSERT_ID()"];

	signup($db, $sub, $event_id);
?>
