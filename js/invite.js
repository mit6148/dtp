function inviteModal(eventId) {
	$('#inviteModal').modal('show');
	$('#inviteForm').off('submit');
	$('#inviteForm').on('submit', function(jsEvent) {
		jsEvent.preventDefault();
		invite(eventId)
	});
}



function invite(eventId) {
	let invitee_emails = $('#inviteFormEmail');
	$.ajax({
		type: 'POST',
		url: 'php/invite.php',
		data: {
			'invitee_emails': invitee_emails.val(),
			'event_id': eventId
		},
		cache: false
	}).done(function(res) {
		console.log(res);
		if (res.indexOf("0") != -1) {
			message('messages', 'error', 'Error', 'Some users have already received an invitation for that event.');
		} else if (res.indexOf("1") != -1) {
			message('messages', 'success', 'Invitation sent!', 'Your invitation has been sent.');
		}
		$('#inviteFormEmail').val('');
		$('#inviteModal').modal('hide');
	}).fail(console.log);
}
