<?php
	function signup($db, $user_sub, $event_id) {
		$stmt = $db->prepare("INSERT INTO signups (user_sub, event_id) VALUES (?, ?)");
		$stmt->execute(array(
			$user_sub,
			$event_id
		));
	}
?>