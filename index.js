$('#addEvent').on('click', function() {
    $('.ui.modal').modal('show');
});
$('#submitEvent').submit(function(event) {
    //console.log("hello");
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
    });
    event.preventDefault();
});