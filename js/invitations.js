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
	$('#invitationsBody').append('<p>Invitation from ' + invitation.inviter.given_name + '.</p>');
	$('#invitationsBody').append('<p>' + invitation.course + ': ' + invitation.assignment + '</p>');
	$('#invitationsBody').append('<p>Location: ' + invitation.location + '</p>');
	let start_time = new Date(invitation.start_time * 1000);
    let start_date = new Date(invitation.start_time * 1000).toDateString();
    let end_time = new Date(invitation.end_time * 1000);
	$('#invitationsBody').append('<p>Date: ' + start_date + '</p>');
	$('#invitationsBody').append('<p>Time: ' + parseTime(start_time) + ' to ' + parseTime(end_time) + '</p>');
}

updateInvitations();
