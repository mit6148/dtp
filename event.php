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
		return $exists_stmt->rowCount() > 0;
	}
?>