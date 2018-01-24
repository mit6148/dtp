<?php
	require_once __DIR__ . "vendor/autoload.php";

	include("db.php");
	include("user.php");
	include("event.php");

	$cal = new \Eluceo\iCal\Component\Calendar("dtp.mit.edu");

	$get_sub_stmt = $db->prepare("SELECT sub FROM users WHERE ical_id = ?");
	$get_sub_stmt->execute(array(
		$_GET["id"]
	));
	$user_sub = get_sub_stmt->fetch(PDO::FETCH_NUM)[0];

	$events = get_user_signedup_events($db, $user_sub);
	$events = append_events_details($db, $events);

	foreach ($events as $event) {
		$new_event = new \Eluceo\iCal\Component\Event();
		$start_datetime = new DateTime();
		$start_datetime->setTimestamp($event["start_time"]);
		$end_datetime = new DateTime();
		$end_datetime->setTimestamp($event["end_time"]);
		$new_event->setDtStart($start_datetime);
		$new_event->setDtEnd($end_datetime);
		$new_event->setSummary($event["course"] . " " . $event["assignment"]);
		$cal->addComponent($new_event);
	}
	header("Content-type: text/calendar");
	header("Content-Disposition: attachment; filename='cal.ics'");

	echo $cal->render();
?>