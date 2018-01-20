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
	<div class="ui big secondary menu">
	 	<!--<a class="item" href="index.php">
	    Home
	 	</a>-->
	 	<!--Todo feature<a class="item">
	    My Classes
	 	</a>-->
	 	<a class="item">
	    My Events
	 	</a>
	 	<div class="ui white button">
		  Add My Event
		</div>
	  <div class="right menu">
	  	<?php if ($logged_in) { ?>
	  		<a class="blue item">
			    New Event
			 	</a>
				<a class="item" href="logout.php">
					Logout
				</a>
			<?php } else { ?>
				<div class="ui item">
					<div class="ui toggle checkbox">
					 	<input type="checkbox" id="persistent" checked="">
					 	<label>Stay logged in</label>
					</div>
				</div>
				<!--<input type="checkbox" id="persistent" value="Stay logged in" checked>-->
				<a class="item" id="login" href="#">
					Log In
				</a>
				<script>
					const hrefPart1 = "https://oidc.mit.edu/authorize?<?php	echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode("https://jungj.scripts.mit.edu:444/dtp/login.php")."&state=".$state; ?>";
					const hrefPart2 = "<?php echo "&nonce=" . $nonce; ?>";
					const loginButton = $("#login");
					const persistentCheckbox = $("#persistent");
					function updatePersistent() {
						let href;
						if (persistentCheckbox.prop("checked")) {
							href = hrefPart1 + ".persistent" + hrefPart2;
						} else {
							href = hrefPart1 + hrefPart2;
						}
						loginButton.attr("href", href);
					};
					persistentCheckbox.on("click", updatePersistent);
					updatePersistent();
				</script>
			<?php } ?>
	  </div>
	</div>
	<div class="ui center aligned container" id="containter">
		<div>
			<h1 class="ui header">
				Are you down to pset?
			</h1>
			<h2 class="ui header">
				Find pset buddies for all of your classes!
			</h2>
			<!--<h3>OpenID Test</h3>
			<p>Click "Test" on the side</p>-->
			<form class="ui form">
				<div class="field">
					<div class="three fields">
						<div class="field">
							<input placeholder="Search by Course" type="text" name="search[course]">
						</div>
						<div class="field">
				  		<input placeholder="Assignment" type="text" name="search[assignment]">
				  	</div>
				  	<div class="field">
				  		<input placeholder="Location" type="text" name="search[location]">
				  	</div>
					</div>
				</div>
				<div class="field">
					<div class="three fields">
						<div class="field">
							<input placeholder="Date" type="date" name="search[date]">
						</div>
						<div class="field">
					  	<input placeholder="Start Time" type="time" name="search[start_time]">
					  </div>
					  <div class="field">
					  	<input placeholder="End Time" type="time" name="search[end_time]">
					  </div>
					</div>
				</div>

			</form>
		</div>
	</div>
	<div class="ui modal">
		<i class="close icon"></i>
		<div class="header">
	    Add A New Event
	  </div>
	  <form class="ui form">
			<div class="field">
				<div class="three fields">
					<div class="field">
						<input placeholder="Course" type="text" name="submit[course]">
					</div>
					<div class="field">
			  		<input placeholder="Assignment" type="text" name="submit[assignment]">
			  	</div>
			  	<div class="field">
			  		<input placeholder="Location" type="text" name="submit[location]">
			  	</div>
				</div>
			</div>
			<div class="field">
				<div class="three fields">
					<div class="field">
						<input placeholder="Date" type="date" name="submit[date]">
					</div>
					<div class="field">
				  	<input placeholder="Start Time" type="time" name="submit[start_time]">
				  </div>
				  <div class="field">
				  	<input placeholder="End Time" type="time" name="submit[end_time]">
				  </div>
				</div>
			</div>
			<div class="actions">
		    <div class="ui black deny button">
		      Cancel
		    </div>
		    <div class="ui positive right labeled icon button">
		      Add My Event
		      <i class="checkmark icon"></i>
		    </div>
		  </div>
		</form>
	</div>
</body>
</html>
