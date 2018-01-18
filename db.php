<?php
	define("DB_USER","jungj");
	define("DB_PASSWORD","Cp7oEAxc8BAzrioPB2ijz3StP");
	define("DB_URL","sql.mit.edu");
	define("DB_NAME","jungj+dtp");

	//echo "mysql:host=".DB_URL.";dbname=jungj+".$dbname;
	$db=new PDO("mysql:host=".DB_URL.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//var_dump($db);
?>