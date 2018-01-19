<?php
	include("oidc.php");
	include("db.php");
	include("user.php");

	$logged_in=false;
	if (isset($_COOKIE["login_uid"])) {
		$logged_in = is_valid_login_uid($db, $_COOKIE["login_uid"]);
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
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="semantic/dist/semantic.min.js"></script>
</head>
<body>
	<div id="bg">
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
					<input type="checkbox" id="persistent" value="Stay logged in" checked>
					<a id="login" href="#">
						<button class="ui primary button log">
							Log In
						</button>
					</a>
					<script>
						const hrefPart1 = "https://oidc.mit.edu/authorize?<?php	echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$state; ?>";
						const hrefPart2 = "<?php echo "&nonce=" . $nonce; ?>";
						const loginButton = document.getElementById("login");
						const persistentCheckbox = document.getElementById("persistent");
						function updatePersistent() {
							let href;
							if (persistentCheckbox.checked == true) {
								href = hrefPart1 + ".persistent" + hrefPart2;
							} else {
								href = hrefPart1 + hrefPart2;
							}
							loginButton.href = href;
						}
						persistentCheckbox.onclick = updatePersistent;
						updatePersistent();
					</script>
				<?php } ?>

			</div>
			<?php
				if ($logged_in) {
					$sub = get_sub($db, $_COOKIE["login_uid"]);
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
	</div>
</body>
</html>
