<?php
	include("db.php");
	include("user.php");
	include("event.php");

	$sub = get_sub($db, $_COOKIE["login_uid"]);
	echo (is_signed_up($db, $sub, $_GET["event_id"])) ? 1 : 0;
?>