<?php
	include("php/db.php");
	include("php/user.php");

	if (isset($_COOKIE["login_uid"])) {
		$stmt = $db->prepare("DELETE FROM logins WHERE uid = ?");
		$stmt->bindParam(1, $uid);
		$uid = $_COOKIE["login_uid"];
		$stmt->execute();
	}
	login_cleanup($db);
	unset($_COOKIE["login_uid"]);
	setcookie("login_uid", "", time()-3600);
	header("Location: " . INDEX_URL);
?>
