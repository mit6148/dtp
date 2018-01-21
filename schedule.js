$('#scheduleLink').on('click', function() {
	$('#scheduleModal').modal('show');
});

$.ajax({
	type: 'GET',
	url: 'get_signedup_events.php',
	cache: false
})
.done(console.log)
.fail(console.log)
;