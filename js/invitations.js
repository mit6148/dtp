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
		$('#invitationsBody').html('<div class="ui center aligned segment">No invitations :(</div>');
	} else {
		$('#invitationsBody').html('<div class="ui center aligned segments" id="segBody"></div>');
	}
	invitations.sort(ByStartTime);
	for (i in invitations) {
		addToInvitationsTable(invitations[i]);
	}
}

function addToInvitationsTable(invitation) {
	console.log(invitation);
	str = "";
	str += '<div class="ui segments"><div class="ui segment">Invitation from: ' + invitation.inviter.name + '<div class="ui right floated red button">Delete</div><div class="ui right floated green button">Accept</div></div>';
	str += '<div class="ui horizontal segments"><div class="ui segment"> Course: ' + invitation.course + '</div><div class="ui segment"> Assignment: ' + invitation.assignment + '</div><div class="ui segment"> Location: '+ invitation.location + '</div></div>';
	let start_time = new Date(invitation.start_time * 1000);
    let start_date = new Date(invitation.start_time * 1000).toDateString();
    let end_time = new Date(invitation.end_time * 1000);
	str += '<div class="ui horizontal segments"><div class="ui segment"> Date: ' + start_date + '</div><div class="ui segment"> Start Time: ' + parseTime(start_time) + '</div><div class="ui segment"> End Time: '+ parseTime(end_time) + '</div></div>';
	str += '</div>';
	$('#segBody').append(str);
}

updateInvitations();
