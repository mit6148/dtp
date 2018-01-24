$('#invitations').on('click', function() {
	$('#invitationsModal').modal('show');
})

function updateInvitations() {
	$.ajax({
		type: 'GET',
		url: 'php/get_invitations.php',
		cache: false,
		dataType: 'json'
	}).done(populateInvitations).fail(console.log);
}

function populateInvitations(invitations) {
	$('#invitationsBody').html('');
	if (invitations.length === 0) {
		$('#invitationsBody').html('<tr><td id="centerCell2" colspan="10">No invitations</td></tr>');
	}
	invitations.sort(ByStartTime);
	for (i in invitations) {
		addToInvitationsTable(invitations[i]);
	}
}

function addToInvitationsTable(invitation) {
	console.log(invitation);
	$('#invitationsBody').append('You got an invitation from ' + invitation.inviter.given_name + '.<br>');
	$('#invitationsBody').append('');
}

updateInvitations();
