<?php
	//GET
	//returns: list of events user has been invited to

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$userinfo = get_userinfo($db, $user_sub);
	$stmt = $db->prepare("SELECT * FROM invites WHERE kerberos = ?");
	$stmt->execute(array(
		$userinfo["kerberos"]
	));
	$invites = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($invites as $i => $invite) {
		$eventinfo = get_eventinfo($db, $invite["event_id"]);
		$invites[$i] = array_merge($invite, $eventinfo);
	}
	$invites = append_event_details($db, $invites);

	header("Content-Type: application/json");
	echo json_encode($invites);
?>