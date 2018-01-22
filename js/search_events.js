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
        $('tbody').html('');
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
    $('#eventsTable').append(('<tbody class="ui title"><tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td>{7}</td>' + ((logged_in) ? ((event.is_signed_up == "1") ? '<td><button class="ui black right labeled icon cancel button" onclick="cancel_signup({8})">Cancel<i class="remove icon"></i></button></td>' : '<td><button class="ui blue right labeled icon submit button" onclick="signup({8})">Signup<i class="add icon"></i></button></td>') : '') + '</tr></tbody><tbody class="ui content"><tr><td colspan="10">{9}<td></tr></tbody>').format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), event.num_attending_event, (event.owner_sub == sub) ? 'You' : ('<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'), event.id, "Sample Text"));
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