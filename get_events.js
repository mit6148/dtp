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
    let start_date = new Date(event.start_time * 1000);
    let hours = "0" + start_date.getHours();
    let minutes = "0" + start_date.getMinutes();

    let end_date = new Date(event.end_time * 1000);
    let end_hours = "0" + end_date.getHours();
    let end_minutes = "0" + end_date.getMinutes();
    $('#events').append('<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td></tr>'.format(event.course, event.assignment, event.location, start_date.toDateString(), hours.substr(-2) + ":" + minutes.substr(-2), end_hours.substr(-2) + ":" + end_minutes.substr(-2)));
}
