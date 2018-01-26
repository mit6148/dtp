<?php
	//user functions library

	function get_sub($db, $login) {
		$login_array = unserialize($login);
		$stmt = $db->prepare("SELECT * FROM logins WHERE id = ?");
		$stmt->execute(array($login_array[0]));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (password_verify($login_array[1], $row["hash"])) {
			return $row["sub"];
		}
		return false;
	}
	function get_userinfo($db, $sub) {
		$stmt = $db->prepare("SELECT * FROM users WHERE sub = ?");
		$stmt->execute(array($sub));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	function is_valid_login($db, $login) {
		login_cleanup($db);
		$login_array = unserialize($login);
		$stmt = $db->prepare("SELECT * FROM logins WHERE id = ?");
		$stmt->execute(array($login_array[0]));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return password_verify($login_array[1], $row["hash"]);
	}
	function get_user_owned_events($db, $sub) {
		$stmt = $db->prepare("SELECT * FROM events WHERE owner_sub = ?");
		$stmt->execute(array(
			$sub
		));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	function get_user_signedup_events($db, $sub) {
		$stmt = $db->prepare("SELECT event_id FROM signups WHERE user_sub = ?");
		$stmt->execute(array(
			$sub
		));
		$event_ids = $stmt->fetchAll(PDO::FETCH_NUM);
		$results = array();
		foreach ($event_ids as $event_id) {
			$new_event = get_eventinfo($db, $event_id[0]);
			if ($new_event["end_time"] > time()) {
				$results[] = $new_event;
			}
		}
		return $results;
	}
	function login_cleanup($db) {
		$db->query("DELETE FROM `logins` WHERE expire_time < UNIX_TIMESTAMP()");
	}
?>
