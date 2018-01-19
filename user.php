<?php
	function get_sub($db, $login_uid) {
		$stmt = $db->prepare("SELECT sub FROM logins WHERE uid = ?");
		$stmt->execute(array($login_uid));
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
