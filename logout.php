<?php
	include("php/db.php");
	include("php/user.php");
	include("php/oidc.php");

	if (isset($_COOKIE["login"])) {
		$login_array = unserialize($_COOKIE["login"]);
		$stmt = $db->prepare("DELETE FROM logins WHERE id = ?");
		$stmt->execute(array($login_array[0]));
	}
	unset($_COOKIE["login_uid"]);
	setcookie("login_uid", "", time()-3600);
	header("Location: " . INDEX_URL);
?>
