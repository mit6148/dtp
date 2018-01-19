<?php
	include("oidc.php");
	include("db.php");
	include("user.php");

	$logged_in=false;
	if (isset($_COOKIE["session_uid"])) {
		$stmt = $db->prepare("SELECT * FROM sessions WHERE uid = ?");
		$stmt->bindParam(1,$uid);
		$uid = $_COOKIE["session_uid"];
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			$session = $stmt->fetch();
			if($session["expire_time"] > time()) {
				$logged_in = true;
			}
		}
	}
	if (!$logged_in) {
		$state = md5(rand());
		$nonce = md5(rand());
		setcookie("state", $state);
		setcookie("nonce", $nonce);
	}
?>

<!doctype html>
<html>
<head>
	<title>dtp</title>
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
      Dumb &amp; Lonely
    </h1>
		<a class="item">Lobby</a>
		<a class="item">Profile</a>
  </div>
	<div class="container">
		<div class="right">
			<?php
			if ($logged_in) { ?>
				<a href="logout.php">
					<button class="ui primary button log">
						Log Out
					</button>
				</a>
			<?php } else { ?>
				<a href="https://oidc.mit.edu/authorize?<?php
				echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$state."&nonce=".$nonce;?>">
					<button class="ui primary button log">
						Log In
					</button>
				</a>
			<?php } ?>

		</div>
		<?php
			if ($logged_in) {
				/*$stmt = $db->prepare("SELECT * FROM sessions WHERE uid = ?");
				$stmt->execute(array($_COOKIE["session_uid"]));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);*/
				//echo $row["sub"];
				$sub = get_sub($db, $_COOKIE["session_uid"]);
				/*$userdata_stmt = $db->prepare("SELECT * FROM users WHERE sub = ?");
				$userdata_stmt->execute(array($sub));
				$userdata = $userdata_stmt->fetch(PDO::FETCH_ASSOC);*/
				$userdata = get_userdata($db, $sub);
				echo "<h1>Hi, " . $userdata["given_name"] . ".</h1>\n";
			} 
		?>
		<h1>Welcome to Dumb &amp; Lonely</h1>
		<!--<h3>OpenID Test</h3>
		<p>Click "Test" on the side</p>-->
		<div class="ui icon input fluid" id="start">
  		<input placeholder="Search for classes or assignments..." type="text">
  		<i class="search icon"></i>
		</div>
	</div>
</body>
</html>
