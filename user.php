<?php
	function get_sub($db, $session_uid) {
		$stmt = $db->prepare("SELECT sub FROM sessions WHERE uid = ?");
		$stmt->execute(array($session_uid));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["sub"];
	}
	function get_userdata($db, $sub) {
		$stmt = $db->prepare("SELECT * FROM users WHERE sub = ?");
		$stmt->execute(array($sub));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
?>
