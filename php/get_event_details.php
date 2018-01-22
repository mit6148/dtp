<?php
	//GET: event_id
	//returns: course, assignment, location, date, start_time, end_time, owner_name, owner_email, attendees (array)

	include("db.php");
	include("user.php");
	include("event.php");

	$result = get_eventinfo($db, $_GET["event_id"]);
	$ownerinfo = get_userinfo($db, $result["owner_sub"]);
	$result["owner_name"] = $ownerinfo["name"];
	$result["owner_email"] = $ownerinfo["email"];

	$result["attendees"] = array();
	$attendees_stmt = $db->prepare("SELECT user_sub FROM signups WHERE event_id = ?");
	$attendees_stmt->execute(array(
		$_GET["event_id"]
	));
	$attendee_subs = $attendees_stmt->fetchAll(PDO:FETCH_NUM);
	foreach ($attendee_subs as $attendee_sub) {
		$attendee_info = get_userinfo($db, $attendee_sub[0]);
		$result["attendees"][]=array(
			"name"=>$attendee_info["name"];
		);
	}

	echo json_encode($result)
?>