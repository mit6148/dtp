<?php
	function get_sub($session_uid) {
		$stmt = $db->prepare("$SELECT sub FROM sessions WHERE uid = ?");
		$stmt->execute(array($session_uid));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["sub"];
	}
?>