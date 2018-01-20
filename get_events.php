<?php
	include("db.php");
	include("user.php");

//	echo $_GET["available_date"] . " " . $_GET["start_available_time"];
//	echo $_GET["available_date"] . " " . $_GET["end_available_time"];
	$start_available_datetime = date_create_from_format("Y-m-d H:i", $_GET["available_date"] . " " . $_GET["start_available_time"]);
	$start_available_time = date_timestamp_get($start_available_datetime);
	$end_available_datetime = date_create_from_format("Y-m-d H:i", $_GET["available_date"] . " " . $_GET["end_available_time"]);
	$end_available_time = date_timestamp_get($end_available_datetime);

	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND start_time < ? AND end_time > ?");
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%",
		$end_available_time,
		$start_available_time
	));
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($results as $key => $row) {
		$userinfo = get_userinfo($db, $row["owner_sub"]);
		$results[$key]["owner_name"] = $userinfo["name"];
		$results[$key]["owner_email"] = $userinfo["email"];
	}

	echo json_encode($results);
?>
