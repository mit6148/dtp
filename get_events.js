if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}
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
        console.log(res);
        $('#events').html("");
        $('#eventsTable').show();
        for (let i = 0; i < res.length; i++) {
            parseEvent(res[i]);
        }
    })
    .fail(console.log)
    ;
});
function parseEvent(event) {
    $('#events').append('<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td></tr>'.format(event.course, event.assignment, event.location, event.date, event.start_time, event.end_time));
}
