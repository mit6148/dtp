<?php
	//GET: event_id
	//returns: number of people attending event

	include("db.php");
	include("event.php");

	echo num_attending_event($db, $_GET["event_id"]);
?>
