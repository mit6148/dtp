<?php
	//GET:
	//returns: new ical id

	include("db.php");
	include("user.php");
	include("event.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$ical_id = md5($user_sub . (string) rand() . (string) time());
	$stmt = $db->prepare("UPDATE users SET ical_id = ? WHERE sub = ?");
	$stmt->execute(array(
		$ical_id,
		$user_sub
	));
	echo $ical_id;
?>
