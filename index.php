<?php
	include("php/oidc.php");
	include("php/db.php");
	include("php/user.php");

	$logged_in=false;
	if (isset($_COOKIE["login_uid"])) {
		$logged_in = is_valid_login_uid($db, $_COOKIE["login_uid"]);
		if (!$logged_in) {
			unset($_COOKIE["login_uid"]);
			setcookie("login_uid", "", time() - 3600);
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
				$sub = get_sub($db, $_COOKIE["login_uid"]);
				$userinfo = get_userinfo($db, $sub);
				echo "sub = '" . $sub . "';";
			} else {
				echo "logged_in = false;";
				echo "sub = ''";
			}
		?>
	</script>
</head>
<body>
	<div class="ui large secondary menu">
		<a href="https://jungj.scripts.mit.edu/dtp/"><h1 class="item">dtp</h1></a>
  	<?php if ($logged_in) { ?>
			<div class="right menu topMenu">
				<div class="item">
					Hi, <?php echo $userinfo["given_name"]; ?>.<!--&nbsp;<i class="user icon"></i>-->
				</div>
				<a class="item clickable" href="#" id="addEvent">
		  			New Event&nbsp;
					<i class="add icon"></i>
				</a>
				<a class="item clickable" href="#" id="scheduleLink">
			    	My Schedule&nbsp;
			    	<i class="checked calendar icon"></i>
		 		</a>
				<a class="item clickable" href="logout.php" id="logout">
					Logout&nbsp;
					<i class="sign out icon"></i>
				</a>
			</div>
		<?php } else { ?>
			<div class="right menu topMenu">
				<div class="ui item">
					<div class="ui toggle checkbox">
					 	<input type="checkbox" id="persistent" checked="">
					 	<label>Stay logged in</label>
					</div>
				</div>
				<!--<input type="checkbox" id="persistent" value="Stay logged in" checked>-->
				<a class="item clickable" href="#" id="login">
					Login&nbsp;
					<i class="sign in icon"></i>
				</a>
			</div>
			<script>
				const hrefPart1 = "https://oidc.mit.edu/authorize?<?php	echo "client_id=".CLIENT_ID."&response_type=code&scope=openid%20profile%20email&redirect_uri=".urlencode(LOGIN_PAGE_URL)."&state=".$state; ?>";
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
				down to pset?
			</h1>
			<p class="ui">
				Search by course, assignment, or location...
			</p>
			<form class="ui form" id="searchEvents">
				<div class="field">
					<div class="three fields">
						<div class="field">
							<input placeholder="Course" type="text" name="search_course">
						</div>
						<div class="field">
				  			<input placeholder="Assignment" type="text" name="search_assignment">
				  		</div>
					  	<div class="field">
					  		<input placeholder="Location" type="text" name="search_location">
					  	</div>
					</div>
				</div>
				<button class="ui blue center floated right labeled icon submit button">
		      		Search
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
	<div class="ui modal" id="viewModal">
		<i class="close icon"></i>
		<div class="ui center aligned header">
	    View Event
	  </div>
	  <div class="ui header" id="viewModalTitle">
	  </div>
	  <div id="viewModalBody">
	  	<p>Event created by <span id="viewModalOwner"></span></p>
	  	<p>Location: <span id="viewModalLocation"></span></p>
	  	<p>Date: <span id="viewModalDate"></span></p>
	  	<p>Time: <span id="viewModalStartTime"></span> - <span id="viewModalEndTime"></span></p>
	  	<p><span id="viewModalAttendees"></span> will be there.</p>
	  </div>
		<button class="ui blue right floated right labeled icon button" id="editModal" hidden>
	  	Edit My Event
	  	<i class="edit icon"></i>
	  </button>
	</div>
	<div class="ui modal" id="changeEventModal">
		<i class="close icon"></i>
		<div class="ui center aligned header">
	    Editing Event
	  </div>
	 	<form class="ui form" id="changeEvent">
			<div class="field">
				<div class="three fields">
					<div class="field">
						<input placeholder="Course" type="text" name="change_course" required>
					</div>
					<div class="field">
			  		<input placeholder="Assignment" type="text" name="change_assignment" required>
			  	</div>
			  	<div class="field">
			  		<input placeholder="Location" type="text" name="change_location" required>
			  	</div>
				</div>
			</div>
			<div class="field">
				<div class="three fields" class="center">
					<div class="field">
						<input placeholder="Date" type="date" name="change_date" required>
					</div>
					<div class="field">
				  	<input placeholder="Start Time" type="time" name="change_start_time" required>
				  </div>
				  <div class="field">
				  	<input placeholder="End Time" type="time" name="change_end_time" required>
				  </div>
				</div>
			</div>
			<div class="actions">
				<button class="ui green right floated right labeled icon submit button">
		      Make Changes
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
					<th>Attendees</th>
					<th>Owner</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
		  <tbody class="ui title" id="scheduleBody">
		  </tbody>
		</table>
	</div>
	<div class="ui center aligned container" id="messages">
	</div>
	<div class="ui container" id="eventsTableContainer">
		<table class="ui center aligned structured structured table" id="eventsTable" hidden>
			<thead id="eventHeader">
				<tr>
					<th>Course</th>
					<th>Assignment</th>
					<th>Location</th>
					<th>Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Attendees</th>
					<th>Owner</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>
	<script src="js/universal.js"></script>
	<script src="js/search_events.js"></script>
	<script src="js/signup_event.js"></script>
	<script src="js/view_event.js"></script>
	<?php if ($logged_in){ ?>
		<script src="js/new_event.js"></script>
		<script src="js/schedule.js"></script>
	<?php } ?>
</body>
</html>
