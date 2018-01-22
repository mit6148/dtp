<?php
	include("php/db.php");
	include("php/user.php");
	include("php/event.php");

	echo is_signed_up($db, "bbf102e305eec69b972fdb5899219a39", "73");
?>
