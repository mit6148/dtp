<?php
	//GET: course, assignment, location, available_date, start_available_time, end_available_time
	//returns: all matching events as a JSON

	include("db.php");
	include("user.php");
	include("event.php");

	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ?");
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%"
	));

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$requester_sub = get_sub($db, $_COOKIE["login_uid"]);

	/*foreach ($results as $key => $row) {
		$owner_userinfo = get_userinfo($db, $row["owner_sub"]);
		$results[$key]["owner_name"] = $owner_userinfo["name"];
		$results[$key]["owner_email"] = $owner_userinfo["email"];
		$results[$key]["is_signed_up"] = (is_signed_up($db, $requester_sub, $row["id"])) ? 1 : 0;
		$results[$key]["num_attending_event"] = num_attending_event($db, $row["id"]);
	}*/
	$results = append_events_details($db, $results, $requester_sub);

	echo json_encode($results);
?>
