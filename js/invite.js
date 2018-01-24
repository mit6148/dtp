$('#inviteButton').on('click', function() {
	$('#inviteModal').modal('show');
})
$('#inviteForm').on('submit', invite);

function invite(event) {
	event.preventDefault();
	let invitee_kerberos = $('#inviteFormKerberos');
	$.ajax({
		type: 'POST',
		url: 'php/invite.php',
		data: {
			'invitee_kerberos': invitee_kerberos
		},
		cache: false
	}).done(function(res) {
		console.log(res);
		$('#inviteModal').modal('hide');
	}).fail(console.log);
}