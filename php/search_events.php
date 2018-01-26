<?php
	//GET: course, assignment, location, available_date, start_available_time, end_available_time
	//returns: all matching events as a JSON

	include("db.php");
	include("user.php");
	include("event.php");

	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND end_time > UNIX_TIMESTAMP()");
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%"
	));

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$requester_sub = false;

	if (isset($_COOKIE["login"])) {
		$requester_sub = get_sub($db, $_COOKIE["login"]);
	}

	if (!$requester_sub) {
		$results = append_events_details($db, $results);
	} else {
		$results = append_events_details($db, $results, $requester_sub);
	}

	header("Content-type: application/json");
	echo json_encode($results);
?>
