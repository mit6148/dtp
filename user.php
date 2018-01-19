<?php
	function get_sub($db, $login_uid) {
		$stmt = $db->prepare("SELECT sub FROM logins WHERE uid = ?");
		$stmt->execute(array($login_uid));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["sub"];
	}
	function get_userinfo($db, $sub) {
		$stmt = $db->prepare("SELECT * FROM users WHERE sub = ?");
		$stmt->execute(array($sub));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	function is_valid_login_uid($db, $login_uid) {
		$db->query("DELETE FROM `logins` WHERE expire_time < UNIX_TIMESTAMP()");
		$stmt = $db->prepare("SELECT * FROM logins WHERE uid = ?");
		$stmt->execute(array($login_uid));
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}
?>
