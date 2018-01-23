
function viewEvent(id, owner) {
    console.log("Editing " + id);
    $.ajax({
        type: 'GET',
        url: 'php/get_event_details.php',
        data: {
            event_id: id,
        },
        dataType: "json",
        cache: false,
    }).done(function(res) {
    for (i in res.attendees) {
        $('#participants').append("<div class='item'>" + res.attendees[i].name + "</div>");
    }
	$('#randomInfo').html('');
    let start_time = new Date(res.start_time * 1000);
    let end_time = new Date(res.end_time * 1000);
    $('#viewModalTitle').text(res.course + " " + res.assignment);
    $('#viewModalOwner').text(res.owner_name);
    $('#viewModalLocation').text(res.location);
    $('#viewModalDate').text(start_time.toDateString());
    $('#viewModalStartTime').text(parseTime(start_time));
    $('#viewModalEndTime').text(parseTime(end_time));
    let attendeesString = '';
    if (res.attendees.length === 0) {
        attendeesString = 'No one';
    } else {
        attendeesString = res.attendees[0].name;
        if (res.attendees.length === 2) {
            attendeesString += ' and ' + res.attendees[1].name;
        } else {
            for (let i = 1; i < res.attendees.length; i++) {
                attendeesString += ((i < (res.attendees.length - 1)) ? ', ' : ', and ') + res.attendees[i].name;
            }
        }
    }
    $('#viewModalAttendees').text(attendeesString);
    if (owner) {
        $('#editModal').show();
        $('#editModal').off('click');
        $('#editModal').on('click', function() {
            $('#changeEventModal').modal('show');
            $('#changeEventModal').off('submit');
            $('input[name=change_course]').val(res.course);
            $('input[name=change_assignment]').val(res.assignment);
            $('input[name=change_location]').val(res.location);
            let start_time = new Date(res.start_time * 1000);
            let start_hours = "0" + start_time.getHours();
            let start_minutes = "0" + start_time.getMinutes();
            $('input[name=change_start_time]').val(start_hours.substr(-2) + ":" + start_minutes.substr(-2));
            let end_time = new Date(res.end_time * 1000);
            let end_hours = "0" + end_time.getHours();
            let end_minutes = "0" + end_time.getMinutes();
            $('input[name=change_end_time]').val(end_hours.substr(-2) + ":" + end_minutes.substr(-2));
            let year = start_time.getFullYear();
            let month = "0" + (parseInt(start_time.getMonth()) + 1);
            let day = "0" + start_time.getDate();
            $('input[name=change_date]').val(year + "-" + month.substr(-2) + "-" + day.substr(-2));
            $('#changeEventModalDelete').on('click', function() {
                deleteEvent(res.id);
            });
            $('#changeEventModal').on('submit', function(event) {
                event.preventDefault();
                let data = {
                    event_id : id,
                    'course' : $('input[name=change_course]').val(),
                    'assignment' : $('input[name=change_assignment]').val(),
                    'location' : $('input[name=change_location]').val(),
                    'date' : $('input[name=change_date]').val(),
                    'start_time' : $('input[name=change_start_time]').val(),
                    'end_time' : $('input[name=change_end_time]').val(),
                };
                $.ajax({
                    type : 'POST',
                    cache : false,
                    data : data,
                    url : 'php/edit_event.php',
                }).done(function() {
                    $('#changeEventModal').modal('hide');
                    $('#viewModal').modal('hide');
                    viewEvent(id, owner);
                    if (searched) {
                        searchEvents();
                    }
		    updateScheduleBody();
                });
            });
        });
    } else {
        $('#editModal').hide();
    }
    $('#viewModal').modal('show');
    }).fail(console.log);
}
function deleteEvent(event_id) {
    $.ajax({
        type : 'POST',
        cache : false,
        data : {
            'event_id' : event_id
        },
        url : 'php/delete_event.php'
    }).done(function(res) {
        $('#changeEventModal').modal('hide');
        if (searched) {
            searchEvents();
        }
	updateScheduleBody();
    }).fail(console.log);
}
