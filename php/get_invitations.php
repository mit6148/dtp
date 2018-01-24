<?php
	//GET
	//returns: list of events user has been invitationd to

	include("db.php");
	include("event.php");
	include("user.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$userinfo = get_userinfo($db, $user_sub);
	$stmt = $db->prepare("SELECT * FROM invitations WHERE kerberos = ?");
	$stmt->execute(array(
		$userinfo["kerberos"]
	));
	$invitations = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($invitations as $i => $invitation) {
		$invitations[$i]["event"] = get_eventinfo($db, $invitation["event_id"]);
                $owner_userinfo = get_userinfo($db, $invitations[$i]["event"]["owner_sub"]);
                $invitations[$i]["event"]["owner_name"] = $owner_userinfo["name"];
                $invitations[$i]["event"]["owner_email"] = $owner_userinfo["email"];
                $invitations[$i]["event"]["num_attending_event"] = num_attending_event($db, $invitations[$i]["event"]["id"]);
	}

	header("Content-Type: application/json");
	echo json_encode($invitations);
?>
