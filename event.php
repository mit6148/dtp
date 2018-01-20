<?php
	function signup($db, $user_sub, $event_id) {
		$stmt = $db->prepare("INSERT INTO signups (user_sub, event_id) VALUES (?, ?)");
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
?>