<?php
	//GET
	//returns: signed up event details as JSON
	
	include("db.php");
	include("user.php");
	include("event.php");

	$requester_sub = get_sub($db, $_COOKIE["login_uid"]);
	$results = get_user_signedup_events($db, $requester_sub);

	/*foreach ($results as $key => $row) {
		$owner_userinfo = get_userinfo($db, $row["owner_sub"]);
		$results[$key]["owner_name"] = $owner_userinfo["name"];
		$results[$key]["owner_email"] = $owner_userinfo["email"];
	}*/
	$results = append_events_details($db, $results);

	echo json_encode($results);
?>