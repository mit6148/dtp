<?php
	include("oidc.php");
	include("db.php");
	
	$logged_in=false;
	if(isset($_COOKIE["session_uid"])){
		$stmt=$db->prepare("SELECT * FROM sessions WHERE uid = ?");
		$stmt->bindParam(1,$uid);
		$uid=$_COOKIE["session_uid"];
		$stmt->execute();
		$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
		if($stmt->rowCount()>0){
			$session=$stmt->fetch();
			if($session["expire_time"]>time()){
				$logged_in=true;
			}
		}
	}
	if(!logged_in){
		$state=md5(rand());
		$nonce=md5(rand());
		setcookie("state",$state);
		setcookie("nonce",$nonce);
	}
?>
<!doctype html>
<html>
<head>
	<title>Log in</title>
</head>
<body>

	<?php
		if($logged_in){ ?>
		<h1>You are logged in!</h1>
	<?php }else{ ?>
		<h1>Log in</h1>
	<a href="https://oidc.mit.edu/authorize?<?php
		echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$state."&nonce=".$nonce;
	?>">Click here to login</a><?php } ?>
</body>
</html>
