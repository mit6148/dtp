<?php
	if($_COOKIE["state"]==$_GET["state"]){
		echo "state variables match. ok";
	}else{
		echo "state variables do not match";
	}
?>
