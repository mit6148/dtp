<?php
	include("oidc.php");
	$session=array();
	$session["state"]=md5(rand());
	$session["nonce"]=md5(rand());
	setcookie("session",serialize($session));
?>

<!doctype html>
<html>
<head>
	<title>OIDC Test</title>
</head>
<body>
	<h1>OpenID Connect Test</h1>
	<a href="https://oidc.mit.edu/authorize?<?php 
		echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$session["state"]."&nonce=".$session["nonce"];
	?>">Test</a>
</body>
</html>
