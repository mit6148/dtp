<?php
	//GET
	//returns: list of events user has been invited to

	include("db.php");
	include("event.php");
	include("user.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$userinfo = get_userinfo($db, $user_sub);
	$stmt = $db->prepare("SELECT * FROM invites WHERE kerberos = ?");
	$stmt->execute(array(
		$userinfo["kerberos"]
	));
	$invites = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($invites as $i => $invite) {
		$invites[$i]["event"] = get_eventinfo($db, $invite["event_id"]);
                $owner_userinfo = get_userinfo($db, $invites[$i]["event"]["owner_sub"]);
                $invites[$i]["event"]["owner_name"] = $owner_userinfo["name"];
                $invites[$i]["event"]["owner_email"] = $owner_userinfo["email"];
                $invites[$i]["event"]["num_attending_event"] = num_attending_event($db, $invites[$i]["event"]["id"]);
	}

	header("Content-Type: application/json");
	echo json_encode($invites);
?>
