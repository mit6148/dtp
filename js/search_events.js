$('#searchEvents').submit(searchEvents);
$('#searchEvents').keyup(function() {
    $('#searchEvents').submit();
});
$('[name=search_location]').change($('#searchEvents').submit);
$('.ui.dropdown').dropdown({
    allowAdditions: true,
});
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
        //console.log(res);
        while ($('#eventsTable > *').length > 1){
            $($('#eventsTable > *')[1]).remove();
        }
        $('#eventsTable').show();
        if (res.length === 0){
            $('#eventsTable').append('<tr><td id="centerCell" colspan="10"><p>No events found :(</p>' + ((logged_in) ? '<button class="ui blue center floated icon submit button" onclick="createEventFromSearch();">Create Event</button>' : '') + '</td></tr>');
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
    } else if (date.getHours() === 12) {
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
    $('#eventsTable').append(('<tbody><tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td>{7}</td><td><button class="ui white button" onclick="viewEvent({8}, {9})">Details</button></td>' + ((logged_in) ? ((event.is_signed_up == "1") ? '<td><button class="ui black right labeled icon cancel button" onclick="cancel_signup({8})">Cancel<i class="remove icon"></i></button></td>' : '<td><button class="ui blue right labeled icon submit button" onclick="signup({8})">Sign&nbsp;up<i class="add icon"></i></button></td>') : '') + '</tr></tbody>').format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), event.num_attending_event, (event.owner_sub == sub) ? 'You' : ('<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'), event.id,event.owner_sub == sub));}
function createEventFromSearch() {
    $('[name="submit_course"]').val($('[name=search_course]').val());
    $('[name="submit_assignment"]').val($('[name=search_assignment]').val());
    $('[name="submit_location"]').val($('[name=search_location]').val());
    $('#submitEventModal').modal('show');
}
$(document).ready(function(){
    $('.ui.accordion').accordion();
});
