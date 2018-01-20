<?php
	include("db.php");
	include("user.php");

	//echo $_GET["available_date"] . " " . $_GET["start_available_time"];
	$start_available_datetime = date_create_from_format("m-d-Y h:i a", $_GET["available_date"] . " " . $_GET["start_available_time"]);
	$start_available_time = date_timestamp_get($start_available_datetime);
	$end_available_datetime = date_create_from_format("m-d-Y h:i a", $_GET["available_date"] . " " . $_GET["end_available_time"]);
	$end_available_time = date_timestamp_get($end_available_datetime);
/*	if ($start_available_time === false) {
		echo "failed";
	}*/
	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND start_time < ? AND end_time > ?");
	echo $start_available_time;
	echo $end_available_time;
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%",
		$end_available_time,
		$start_available_time
	));
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*	$userinfo = get_userinfo($db, $results["owner_sub"]);
	$results["name"] = $userinfo["name"];
	$results["email"] = $userinfo["email"];*/

	echo json_encode($results);
?>
