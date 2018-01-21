$('#scheduleLink').on('click', function() {
	$('#scheduleModal').modal('show');
});
function updateScheduleBody(){
	$.ajax({
		type: 'GET',
		url: 'get_signedup_events.php',
		cache: false,
		dataType: "json"
	})
	.done(populateTable)
	.fail(console.log)
	;
}
function populateTable(events) {
	console.log(events);
	events.sort(ByStartTime);
	for (i in events){
		addToSchedule(events[i]);
	}
}
function addToSchedule(event) {
    let start_time = new Date(event.start_time * 1000);
    let start_date = (new Date(event.start_time * 1000 - 3600 * 1000 * 4)).toDateString().split(" ");
    let end_time = new Date(event.end_time * 1000);
    $('#scheduleBody').append('<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td><button class="ui black right labeled icon cancel button" onclick="cancel_signup({7}, false);">Cancel<i class="remove icon"></i></button></td></tr>'.format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), (event.owner_sub == sub) ? 'You' : ('<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'), event.id));
//    eventButton(event.id, event.is_signed_up);
}
updateScheduleBody();
