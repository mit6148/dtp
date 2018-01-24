<?php
	//POST: event_id, invitee_kerberos
	//returns: 1 if successful, 2 if already invited

	include("oidc.php");
	include("db.php");
	include("user.php");
	include("event.php");

	if (!isset($_COOKIE["login_uid"])) {
		die("Not logged in");
	}

	$find_stmt = $db->prepare("SELECT EXISTS(SELECT * FROM invitations WHERE kerberos = ? AND event_id = ?)");
	$find_stmt->execute(array(
		$_POST["invitee_kerberos"],
		$_POST["event_id"]
	));
	$exists = $find_stmt->fetch(PDO::FETCH_NUM)[0];

	$sub = get_sub($db, $_COOKIE["login_uid"]);

	if ($exists == 1) {
		echo "2";
	} else {
		$stmt = $db->prepare("INSERT INTO invitations (kerberos, event_id, inviter_sub, invite_time) VALUES (?, ?, ?, UNIX_TIMESTAMP())");
		$stmt->execute(array(
			$_POST["invitee_kerberos"],
			$_POST["event_id"],
			$sub
		));
		$sender_userinfo = get_userinfo($db, $sub);
		$eventinfo = get_eventinfo($db, $_POST["event_id"]);
		$recipient_email = $_POST["invitee_kerberos"] . "@mit.edu";
		$subject = $sender_userinfo["given_name"] . " invited you to work on " . $eventinfo["course"] . ": " . $eventinfo["assignment"];
		$body = '<a href="' . INDEX_URL . '">Click here to login and view your invitation.</a>';
		$header = "From: noreply\n";
		$header .= "Content-Type: text/html; charset=ISO-8859-1";
		mail($recipient_email, $subject, $body, $header);
	}

?>
