<?php
	include("oidc.php");
	$state=md5(rand());
	$nonce=md5(rand());
	setcookie("state",$state);
?>

<!doctype html>
<html>
<head>
	<title>OIDC Test</title>
</head>
<body>
	<h1>OpenID Connect Test</h1>
	<a href="https://oidc.mit.edu/authorize?<?php 
		echo "client_id=".CLIENT_ID."&response_type=code&scope=openid&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/")."&state=".$state."&nonce=".$nonce;
	?>">Test</a>
</body>
</html>
