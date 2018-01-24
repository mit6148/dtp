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
		console.log(res);
		$('#inviteModal').modal('hide');
	}).fail(console.log);
}
