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
		$('#invitationsBody').html('<div class="ui center aligned segment">No invitations</div>');
	} else {
		$('#invitationsBody').html('<div class="ui aligned segments" id="segBody"></div>');
	}
	invitations.sort(ByStartTime);
	for (i in invitations) {
		addToInvitationsTable(invitations[i]);
	}
}

function addToInvitationsTable(invitation) {
	//console.log(invitation);
	str = "";
	str += '<div class="ui segment">Invitation from <b>' + invitation.inviter.name + '</b> for <b>' + invitation.event.course + '</b>: <b>' + invitation.event.assignment + '</b><div class="ui right floated red button" style="margin-top:-11px" onclick="dismissInvitation(' + invitation.event_id + ')">Dismiss</div><div class="ui right floated green button invitationButton" style="margin-top:-11px" onclick="acceptInvitation(' + invitation.event_id + ')">Accept</div></div><div class="ui segments">';
	str += '<div class="ui horizontal segments"><div class="ui segment">Location: <b>'+ invitation.event.location + '</b></div></div>';
	let start_time = new Date(invitation.event.start_time * 1000);
    let start_date = new Date(invitation.event.start_time * 1000).toDateString();
    let end_time = new Date(invitation.event.end_time * 1000);
	str += '<div class="ui horizontal segments"><div class="ui segment">Date: <b>' + start_date + '</b></div><div class="ui segment">Start Time: <b>' + parseTime(start_time) + '</b></div><div class="ui segment">End Time: <b>'+ parseTime(end_time) + '</b></div></div>';
	str += '</div>';
	$('#segBody').append(str);
}

function acceptInvitation(event_id) {
	$.ajax({
		type: 'POST',
		url: 'php/accept_invitation.php',
		data: {
			'event_id': event_id
		},
		cache: false
	}).done(function(res){
		//console.log(res);
		updateInvitations();
		updateScheduleBody();
		if (searched) {
			searchEvents();
		}
		message('messages', 'success', 'Invitation accepted', 'You have signed up for the event.');
	}).fail(console.log);
}

function dismissInvitation(event_id) {
	$.ajax({
		type: 'POST',
		url: 'php/dismiss_invitation.php',
		data: {
			'event_id': event_id
		},
		cache: false
	}).done(function(res){
		console.log(res);
		updateInvitations();
		updateScheduleBody();
		if (searched) {
			searchEvents();
		}
		message('messages', 'success', 'Invitation dismissed', 'You have dismissed the invitation.');
	}).fail(console.log);
}

updateInvitations();
