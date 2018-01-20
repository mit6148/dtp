$('#addEvent').on('click', function() {
    $('.ui.modal').modal('show');
});
$('#submitEvent').form({
        submit_course: 'empty',
        submit_assignment: 'empty',
        submit_location: 'empty',
        submit_date: 'empty',
        submit_start_time: 'empty',
        submit_end_time: 'empty'
    }
);
$('#submitEvent').form('onSuccess', function() {
    var input = {
        'course' : $('input[name=submit_course]').val(),
        'assignment' : $('input[name=submit_assignment]').val(),
        'location' : $('input[name=submit_location]').val(),
        'date' : $('input[name=submit_date]').val(),
        'start_time' : $('input[name=submit_start_time]').val(),
        'end_time' : $('input[name=submit_end_time]').val(),
    };
    $.ajax({
        type: 'POST',
        url: 'addevent.php',
        data : input,
        cache: false,
    })
    .done(function() {
        $('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event received!</div><p>Other students will be able to see and add your event.</p></div>');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    })
    .fail(function() {
        $('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your event was not received.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    });
    $('.ui.modal').modal('hide');
    $('#submitEvent').form('reset');
});
$('#submitEvent').submit(function(event) {
    //console.log("hello");

    $('#submitEvent').form('validate form');
    event.preventDefault();
});
