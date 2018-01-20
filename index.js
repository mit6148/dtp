$('#addEvent').on('click', function() {
    $('.ui.modal').modal('show');
});
$('#submitEvent').form({
    fields: {
        submit_course: 'empty',
        submit_assignment: 'empty',
        submit_location: 'empty',
        submit_date: 'empty',
        submit_start_time: 'empty',
        submit_end_time: 'empty'
    }
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
    $('#submitEvent').form('reset');
});
