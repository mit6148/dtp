<?php
	require_once __DIR__ . "vendor/autoload.php";

	include("db.php");
	include("user.php");
	include("event.php");

	$cal = new \Eluceo\iCal\Component\Calendar("dtp.mit.edu");
?>