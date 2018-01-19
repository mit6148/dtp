<?php
	include("db.php");

	if (isset($_COOKIE["login_uid"])) {
		$stmt = $db->prepare("DELETE FROM logins WHERE uid = ?");
		$stmt->bindParam(1, $uid);
		$uid = $_COOKIE["login_uid"];
		$stmt->execute();
	}
	$db->query("DELETE FROM `logins` WHERE expire_time < UNIX_TIMESTAMP()");
	setcookie("login_uid", "", time()-3600);
	header("Location: https://jungj.scripts.mit.edu:444/dtp/");
?>