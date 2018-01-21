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
	<meta charset="utf-8">
	<title>dtp</title>
	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="semantic/dist/semantic.min.js"></script>
	<script>
		<?php
			if ($logged_in) {
				echo "logged_in = true;";
				echo "sub = '" . get_sub($db, $_COOKIE["login_uid"]) . "';";
			} else {
				echo "logged_in = false;";
				echo "sub = ''";
			}
		?>
	</script>
</head>
<body>
	<div class="ui big secondary menu">
	 	<!--<a class="item" href="index.php">
	    Home
	 	</a>-->
	 	<!--Todo feature<a class="item">
	    My Classes
	 	</a>-->
  	<?php if ($logged_in) { ?>
  			<a class="item" id="scheduleLink">
		    	My Schedule
		 	</a>
		 	<!--<a class="item">
		    	Profile
		 	</a>-->
			<div class="right menu">
				<div class="item">
			 		<div class="ui white button" id="addEvent">
				  	New Event
					</div>
				</div>
				<a class="item" href="logout.php">
					Logout
				</a>
			</div>
		<?php } else { ?>
			<div class="right menu">
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
			</div>
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
	<div class="ui center aligned container" id="containter">
		<div id="main">
			<h1 class="ui header">
				dtp
			</h1>
			<p class="ui">
				Find a study group...
			</p>
			<form class="ui form" id="searchEvents">
				<div class="field">
					<div class="three fields">
						<div class="field">
							<input placeholder="Search by Course" type="text" name="search_course">
						</div>
						<div class="field">
				  		<input placeholder="Assignment" type="text" name="search_assignment">
				  	</div>
				  	<div class="field">
				  		<input placeholder="Location" type="text" name="search_location">
				  	</div>
					</div>
				</div>
				<div class="field">
					<div class="three fields" class="center">
						<div class="field">
							<input placeholder="Date" type="date" name="search_date">
						</div>
						<div class="field">
					  	<input placeholder="Start Time" type="time" name="search_start_time">
					  </div>
					  <div class="field">
					  	<input placeholder="End Time" type="time" name="search_end_time">
					  </div>
					</div>
				</div>
				<button class="ui blue center floated right labeled icon submit button">
		      Search for Study Events
		      <i class="search icon"></i>
		    </button>
			</form>

		</div>
	</div>
	<div class="ui modal" id="submitEventModal">
		<i class="close icon"></i>
		<div class="ui center aligned header">
	    Add A New Event
	  </div>
	  <form class="ui form" id="submitEvent">
			<div class="field">
				<div class="three fields">
					<div class="field">
						<input placeholder="Course" type="text" name="submit_course" required>
					</div>
					<div class="field">
			  		<input placeholder="Assignment" type="text" name="submit_assignment" required>
			  	</div>
			  	<div class="field">
			  		<input placeholder="Location" type="text" name="submit_location" required>
			  	</div>
				</div>
			</div>
			<div class="field">
				<div class="three fields" class="center">
					<div class="field">
						<input placeholder="Date" type="date" name="submit_date" required>
					</div>
					<div class="field">
				  	<input placeholder="Start Time" type="time" name="submit_start_time" required>
				  </div>
				  <div class="field">
				  	<input placeholder="End Time" type="time" name="submit_end_time" required>
				  </div>
				</div>
			</div>
			<div class="actions">
				<button class="ui green right floated right labeled icon submit button">
		      Add My Event
		      <i class="checkmark icon"></i>
		    </button>
		    <div class="ui black right floated deny button">
		      Cancel
		    </div>
		  </div>
		</form>
	</div>
	<div class="ui modal" id="scheduleModal">
		<i class="close icon"></i>
		<div class="ui center aligned header">
	    	My Schedule
	  	</div>
	  	<table class="ui table">
			<thead>
				<tr>
					<th>Course</th>
					<th>Assignment</th>
					<th>Location</th>
					<th>Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Owner</th>
					<th></th>
				</tr>
			</thead>
		  <tbody class="ui title" id="scheduleBody">
		  </tbody>
		</table>
	</div>
	<div class="ui center aligned container" id="messages">
	</div>
	<div class="ui container">
		<table class="ui table" hidden="" id="eventsTable">
			<thead>
				<tr>
					<th>Course</th>
					<th>Assignment</th>
					<th>Location</th>
					<th>Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Owner</th>
					<?php if ($logged_in) {
						echo "<th></th>";
					}?>
				</tr>
			</thead>
		  <tbody class="ui title" id="events">
		    
		  </tbody>
		</table>
	</div>
	<script src="universal.js"></script>
	<script src="get_events.js"></script>
	<script src="signup_event.js"></script>
	<?php if ($logged_in){ ?>
		<script src="add_event.js"></script>
		<script src="schedule.js"></script>
	<?php } ?>
</body>
</html>
