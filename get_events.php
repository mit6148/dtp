<?php
	include("db.php");
	include("user.php");

	$start_available_time = strtotime($_GET["available_date"] . $_GET["start_available_time"]);
	$end_available_time = strtotime($_GET["available_date"] . $_GET["end_available_time"]);
	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND start_time < ? AND end_time > ?");
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%",
		$end_available_time,
		$start_available_time
	));
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$userinfo = get_userinfo($db, $results["owner_sub"]);
	$results["name"] = $userinfo["name"];
	$results["email"] = $userinfo["email"];
	
	echo json_encode($results);
?>
