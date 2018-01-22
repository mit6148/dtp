$('#searchEvents').submit(searchEvents);
function searchEvents(event) {
    if (event) {
        event.preventDefault();
    }
    let data = {
        course : $('[name="search_course"]').val(),
        assignment : $('[name="search_assignment"]').val(),
        location : $('[name="search_location"]').val()
    };
    $.ajax({
        method : 'GET',
        cache : false,
        data : data,
        dataType : "json",
        url : "php/search_events.php",
    })
    .done(function(res) {
        console.log(res);
        while ($('#eventsTable > *').length > 1){
            $($('#eventsTable > *')[1]).remove();
        }
        $('#eventsTable').show();
        if (res.length == 0){
            $('#eventsTable').append('<tr><td id="centerCell" colspan="' + ((logged_in) ? '8' : '7') + '"><p>No results returned :(</p><button class="ui blue center floated icon submit button" onclick="createEventFromSearch();">Create Event</button></td></tr>');
        } else {
            res.sort(ByStartTime);
            for (let i = 0; i < res.length; i++) {
                parseEvent(res[i]);
            }
        }
        searched = true;
    })
    .fail(console.log)
    ;
}
function parseTime(date) {
    let hours;
    let day;
    if (date.getHours() === 0) {
        hours = 12;
        day = " am";
    } else if (date.getHours() == 12) {
        hours = 12;
        day = " pm";
    } else if (date.getHours() > 12) {
        hours = date.getHours() - 12;
        day = " pm";
    } else {
        hours = date.getHours();
        day = " am";
    }
    let minutes = "0" + date.getMinutes();
    return hours + ":" + minutes.substr(-2) + day;
}
function parseEvent(event) {
    let start_time = new Date(event.start_time * 1000);
    let start_date = (new Date(event.start_time * 1000)).toDateString().split(" ");
    let end_time = new Date(event.end_time * 1000);
    $('#eventsTable').append(('<tbody onclick="viewEvent({8}, {9})"><tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td>{7}</td>' + ((logged_in) ? ((event.is_signed_up == "1") ? '<td><button class="ui black right labeled icon cancel button" onclick="cancel_signup({8})">Cancel<i class="remove icon"></i></button></td>' : '<td><button class="ui blue right labeled icon submit button" onclick="signup({8})">Signup<i class="add icon"></i></button></td>') : '') + '</tr></tbody>').format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), event.num_attending_event, (event.owner_sub == sub) ? 'You' : ('<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'), event.id,event.owner_sub == sub));
}

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
    $('#viewModal').modal('show');
    console.log(res);
	$('#participants').html('');
    for (i in res.attendees) {
        $('#participants').append("<div class='item'>" + res.attendees[i].name + "</div");
    }
	$('#randomInfo').html('');
    $('#randomInfo').append("<div>{0}</div><div>{1}</div><div>{2}</div>".format(res.course, res.assignment, res.location));
    if (owner) {
        $('#editModal').show();
        $('#editModal').off('click');
        $('#editModal').on('click', function() {
            $('#changeEventModal').modal('show');
            $('#changeEventModal').off('submit');
            $('input[name=change_course]').val(res.course);
            $('input[name=change_assipnment]').val(res.assignment);
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
            $('#changeEventModal').on('submit', function(event) {
                event.preventDefault();
                const data = {
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
                });
            });
        });
    } else {
        $('#editModal').hide();
    }
    }).fail(console.log);
}

function eventButton(id, signed_up) {
    if (signed_up) {
        $('#' + id).html('<button class="ui black right labeled icon cancel button" onclick="cancel_signup(' + id + ')">Cancel<i class="remove icon"></i></button>');
    } else {
        $('#' + id).html('<button class="ui blue right labeled icon submit button" onclick="signup(' + id + ')">Signup<i class="add icon"></i></button>');
    }
}
function createEventFromSearch() {
    $('[name="submit_course"]').val($('[name=search_course]').val());
    $('[name="submit_assignment"]').val($('[name=search_assignment]').val());
    $('[name="submit_location"]').val($('[name=search_location]').val());
    $('#submitEventModal').modal('show');
}
$(document).ready(function(){
    $('.ui.accordion').accordion();
});
