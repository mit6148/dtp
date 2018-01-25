<?php
	//POST: event_id, invitee_emails
	//returns: 1 if successful, 2 if already invited

	include("oidc.php");
	include("db.php");
	include("user.php");
	include("event.php");

	if (!isset($_COOKIE["login_uid"])) {
		die("Not logged in");
	}

	$emails = explode(',', $_POST["invitee_emails"]);


	$sub = get_sub($db, $_COOKIE["login_uid"]);
	foreach ($emails as $email){
		$find_stmt = $db->prepare("SELECT EXISTS(SELECT * FROM invitations WHERE email = ? AND event_id = ?)");
		$find_stmt->execute(array(
			$email,
			$_POST["event_id"]
		));
		$exists = $find_stmt->fetch(PDO::FETCH_NUM)[0];
		$email = trim($email);
		if ($exists != 1) {
			$stmt = $db->prepare("INSERT INTO invitations (email, event_id, inviter_sub, invite_time) VALUES (?, ?, ?, UNIX_TIMESTAMP())");
			$stmt->execute(array(
				$email,
				$_POST["event_id"],
				$sub
			));
			$sender_userinfo = get_userinfo($db, $sub);
			$eventinfo = get_eventinfo($db, $_POST["event_id"]);
			$recipient_email = $email;
			$sender_email = "noreply";
			$subject = $sender_userinfo["name"] . " invited you to work on " . $eventinfo["course"] . ": " . $eventinfo["assignment"];
			$body = $sender_userinfo["name"] . " invited you to work on " . $eventinfo["course"] . ": " . $eventinfo["assignment"] . " on " . date("l F j, Y", $eventinfo["start_time"]) . " from " . date("g:i A", $eventinfo["start_time"]) . " to " . date("g:i A", $eventinfo["end_time"]) . " at " . $eventinfo["location"] . ".<br>\n";
			$body .= '<a href="' . INDEX_URL . '">Click here to login and view your invitation.</a>';
			$header = "From: " . $sender_email . "\n";
			$header .= "Content-Type: text/html; charset=utf-8";
			mail($recipient_email, $subject, $body, $header);
			echo "1";
		} else {
			echo "0";
		}
	}
?>
