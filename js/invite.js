function inviteModal(eventId) {
	$('#inviteModal').modal('show');
	$('#inviteForm').on('submit', function(jsEvent) {
		jsEvent.preventDefault();
		invite(eventId)
	});
}



function invite(eventId) {
	let invitee_kerberos = $('#inviteFormKerberos');
	$.ajax({
		type: 'POST',
		url: 'php/invite.php',
		data: {
			'invitee_kerberos': invitee_kerberos.val(),
			'event_id': eventId
		},
		cache: false
	}).done(function(res) {
		//console.log(res);
		if (res === "2") {
			message('messages', 'error', 'Error', 'That user has already received an invitation for that event.');
		} else if (res === "1") {
			message('messages', 'success', 'Invitation sent!', 'Your invitation has been sent.');
		}
		$('#inviteFormKerberos').val('');
		$('#inviteModal').modal('hide');
	}).fail(console.log);
}
