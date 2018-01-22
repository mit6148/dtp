<?php
	//event functions library

	function signup($db, $user_sub, $event_id) {
		if (!is_signed_up($db, $user_sub, $event_id)){
			$stmt = $db->prepare("INSERT INTO signups (user_sub, event_id) VALUES (?, ?)");
			$stmt->execute(array(
				$user_sub,
				$event_id
			));
		}
	}
	function cancel_signup($db, $user_sub, $event_id) {
		$stmt = $db->prepare("DELETE FROM signups WHERE user_sub = ? AND event_id = ?");
		$stmt->execute(array(
			$user_sub,
			$event_id
		));
	}
	function get_eventinfo($db, $event_id) {
		$stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
		$stmt->execute(array(
			$event_id
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	function is_signed_up($db, $user_sub, $event_id) {
		$stmt = $db->prepare("SELECT * FROM signups WHERE user_sub = ? AND event_id = ?");
		$stmt->execute(array(
			$user_sub,
			$event_id
		));
		return $stmt->rowCount() > 0;
	}
	function get_signedup_events($db, $user_sub) {
		$stmt = $db->prepare("SELECT event_id FROM signups WHERE user_sub = ?");
		$stmt->execute(array(
			$user_sub
		));
		$results = [];
		$event_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($event_ids as $event_id) {
			$results[] = get_eventinfo($db, $event_id[0]);
		}
		return $results;
	}
	function event_cleanup($db) {
		$stmt = $db->query("SELECT id FROM events WHERE end_time < UNIX_TIMESTAMP()");
		$rows = $stmt->fetchAll(PDO::FETCH_NUM);
		foreach ($rows as $row) {
			$signup_del_stmt = $db->prepare("DELETE FROM signups WHERE event_id = ?");
			$signup_del_stmt->execute($row);
			$event_del_stmt = $db->prepare("DELETE FROM events WHERE id = ?");
			$event_del_stmt->execute($row);
		}
	}
	function num_attending_event($db, $event_id) {
		$stmt = $db->prepare("SELECT COUNT(*) FROM `signups` WHERE event_id = ?");
		$stmt->execute(array(
			$event_id
		));
		return $stmt->fetch(PDO::FETCH_NUM)[0];
	}
?>
