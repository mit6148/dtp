function signup(event_id) {
	$.ajax({
		type: 'POST',
		url: 'signup_event.php',
		data: {
			'event_id': event_id
		},
		cache: false
	}).done(console.log)
	.fail(console.log);
}