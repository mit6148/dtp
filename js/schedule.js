$('#scheduleLink').on('click', function() {
	$('#scheduleModal').modal('show');
});
$('#scheduleLinkMobile').on('click', function() {
	$('#scheduleModal').modal('show');
});
function updateScheduleBody(){
	$.ajax({
		type: 'GET',
		url: 'php/get_signedup_events.php',
		cache: false,
		dataType: "json"
	})
	.done(populateTable)
	.fail(console.log)
	;
}
function populateTable(events) {
	//console.log(events);
	$('#scheduleBody').html('');
	if (events.length === 0) {
		$('#scheduleBody').html('<tr><td id="centerCell2" colspan="10">There are no events in your schedule. :(</td></tr>');
	}
	events.sort(ByStartTime);
	for (i in events){
		addToSchedule(events[i]);
	}
}
function addToSchedule(event) {
    let start_time = new Date(event.start_time * 1000);
    let start_date = (new Date(event.start_time * 1000)).toDateString().split(" ");
    let end_time = new Date(event.end_time * 1000);
    $('#scheduleBody').append('<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4} - {5}</td><td>{6}</td><td><button class="ui white button" onclick="viewEvent({8}, {9})">Details</button></td><td><button class="ui black right labeled icon cancel button" onclick="cancel_signup({8});">Cancel<i class="remove icon"></i></button></td></tr>'.format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), event.num_attending_event, (event.owner_sub == sub) ? 'You' : ('<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'), event.id, (event.owner_sub == sub)));
}
updateScheduleBody();
