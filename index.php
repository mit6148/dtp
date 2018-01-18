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
	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
	<script src="semantic/dist/semantic.min.js"></script>
</head>
<body>
	<div class="ui sidebar vertical menu visible" id="navbar">
    <h1 class="item">
      Dumb & Lonely
    </h1>
		<a class="item" href="https://oidc.mit.edu/authorize?<?php
			echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$session["state"]."&nonce=".$session["nonce"];
		?>">Test</a>
  </div>
	<div class="container">
		<h1>Welcome to Dumb & Lonely</h1>
		<h3>OpenID Test</h3>
		<p>Click "Test" on the side</p>
	</div>
</body>
</html>
