$('#searchEvents').submit(function(event) {
    event.preventDefault();
    let data = {
        course : $('[name="search_course"]').val(),
        assignment : $('[name="search_assignment"]').val(),
        location : $('[name="search_location"]').val(),
        available_date : $('[name="search_date"]').val(),
        start_available_time : $('[name="search_start_time"]').val(),
        end_available_time : $('[name="search_end_time"]').val(),
    };
    $.ajax({
        method : 'GET',
        cache : false,
        data : data,
        dataType : "json",
        url : "get_events.php",
    })
    .done(function(res) {
        for (let i = 0; i < res.length; i++) {
            parseEvent(res[i]);
        }
    })
    .fail(console.log)
    ;
});
function parseEvent(event) {
    $('#event').append('<div class="ui success compact message">There is an event here!</div>');
}
