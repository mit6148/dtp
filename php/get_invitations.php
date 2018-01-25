<?php
	//GET
	//returns: list of events user has been invitationd to

	include("db.php");
	include("event.php");
	include("user.php");

	$user_sub = get_sub($db, $_COOKIE["login_uid"]);
	$userinfo = get_userinfo($db, $user_sub);

	$invitations = get_invitations($db, $userinfo["email"]);	
	header("Content-Type: application/json");
	echo json_encode($invitations);
?>
