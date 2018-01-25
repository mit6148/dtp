$('#addEvent').on('click', function() {
    $('#submitEventModal').modal('show');
});

$('#submitEvent').submit(addEventForm);
function addEventForm(event) {
    event.preventDefault();
    let available_date;
    var input = {
        'course' : $('input[name=submit_course]').val(),
        'assignment' : $('input[name=submit_assignment]').val(),
        'location' : $('[name=submit_location]').val(),
        'date' : $('input[name=submit_date]').val(),
        'start_time' : $('input[name=submit_start_time]').val(),
        'end_time' : $('input[name=submit_end_time]').val(),
    };
    $.ajax({
        type: 'POST',
        url: 'php/new_event.php',
        data : input,
        cache: false,
    })
    .done(function(response) {
        //$('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event Created</div></div>');
        message('messages', 'success', 'Event Created', '');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
        updateScheduleBody();
        //console.log(response);
	if (searched) {
		searchEvents();
	}
    viewEvent(response, true);
    })
    .fail(function() {
        //$('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your event was not received by the server.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
        message('messages', 'error', 'Your request was not received by the server.', 'If the problem persists, contact a Network Adminstrator.');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    });
    $('#submitEventModal').modal('hide');
    $('#submitEvent').form('clear');
};
