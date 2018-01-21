<?php
	//GET: course, assignment, location, available_date, start_available_time, end_available_time
	//returns: all matching events as a JSON

	include("db.php");
	include("user.php");
	include("event.php");

	/*function exists($string) {
		if (trim($string) == "") {
			return false;
		}
		return true;
	}
	if (exists($_GET["available_date"])) {
		if (exists($_GET["start_available_time"])) {
			$start_available_datetime = date_create_from_format("Y-m-d H:i", $_GET["available_date"] . " " . $_GET["start_available_time"]);
		} else {
			$start_available_datetime = date_create_from_format("Y-m-d", $_GET["available_date"]);
		}
		$start_available_timestamp = date_timestamp_get($start_available_datetime);
		if (exists($_GET["end_available_time"])) {
			$end_available_datetime = date_create_from_format("Y-m-d H:i", $_GET["available_date"] . " " . $_GET["end_available_time"]);
			$end_available_timestamp = date_timestamp_get($end_available_datetime);
		} else {
			$end_available_datetime = date_create_from_format("Y-m-d", $_GET["available_date"]);
			$end_available_timestamp = date_timestamp_get($end_available_datetime) + 86399;
		}
		$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND start_time < ? AND end_time > ?");
		$stmt->execute(array(
			"%" . $_GET["course"] . "%",
			"%" . $_GET["assignment"] . "%",
			"%" . $_GET["location"] . "%",
			$end_available_timestamp,
			$start_available_timestamp
		));
	} else {
		if (exists($_GET["start_available_time"]) || exists($_GET["end_available_time"])) {
			$timezone = new DateTimeZone(date_default_timezone_get());
			$datetime = new DateTime();
			$timezone_offset = timezone_offset_get($timezone, $datetime);

			if (exists($_GET["start_available_time"])) {
				$start_available_datetime_modulo = date_create_from_format("Y-m-d H:i", "1970-01-01 " . $_GET["start_available_time"]);
				$start_available_modulo = date_timestamp_get($start_available_datetime_modulo) + $timezone_offset;
				//echo $start_available_modulo;
			} else {
				$start_available_modulo = 0;
			}
			if (exists($_GET["end_available_time"])) {
				$end_available_datetime_modulo = date_create_from_format("Y-m-d H:i", "1970-01-01 " . $_GET["end_available_time"]);
				$end_available_modulo = date_timestamp_get($end_available_datetime_modulo) + $timezone_offset;
				//echo $end_available_modulo;
			} else {
				$end_available_modulo = 86399;
			}
			$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ? AND ((start_time + ?) % 86400) < ? AND ((end_time + ?) % 86400) > ?");
			$stmt->execute(array(
				"%" . $_GET["course"] . "%",
				"%" . $_GET["assignment"] . "%",
				"%" . $_GET["location"] . "%",
				$timezone_offset,
				$end_available_modulo,
				$timezone_offset,
				$start_available_modulo
			));
		} else {

		}
	}*/

	$stmt = $db->prepare("SELECT * FROM events WHERE course LIKE ? AND assignment LIKE ? AND location LIKE ?");
	$stmt->execute(array(
		"%" . $_GET["course"] . "%",
		"%" . $_GET["assignment"] . "%",
		"%" . $_GET["location"] . "%"
	));

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$requester_sub = get_sub($db, $_COOKIE["login_uid"]);

	foreach ($results as $key => $row) {
		$owner_userinfo = get_userinfo($db, $row["owner_sub"]);
		$results[$key]["owner_name"] = $owner_userinfo["name"];
		$results[$key]["owner_email"] = $owner_userinfo["email"];
		$results[$key]["is_signed_up"] = (is_signed_up($db, $requester_sub, $row["id"])) ? 1 : 0;
	}




	echo json_encode($results);
?>
