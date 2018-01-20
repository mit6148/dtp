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
    let available_date;
    if ($('[name="search_start_time"]').val() && parseInt($('[name="search_start_time"]').val().substr(2)) < 4) {
        if ($('[name="search_date"]').val()) {
            let s = $('[name="search_date"]').val().split("-");
            let d = new Date(parseInt(s[0]), parseInt(s[1]), parseInt(s[2]));
            d = d.setDate(d.getDate() + 1);
            month = "0" + d.getMonth();
            day = "0" + d.getDate();
            available_date = d.getFullYear() + "-" + month.substr(-2) + "-" + day.substr(-2);
            console.log("Old: " + $('[name="search_date"]').val());
            console.log("New: " + available_date);
        }
    }
    let data = {
        course : $('[name="search_course"]').val(),
        assignment : $('[name="search_assignment"]').val(),
        location : $('[name="search_location"]').val(),
        available_date : available_date || $('[name="search_date"]').val(),
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
    let start_date = (new Date(event.start_time * 1000 - 3600 * 1000 * 4)).toDateString().split(" ");
    let end_time = new Date(event.end_time * 1000);
    $('#events').append('<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td></tr>'.format(event.course, event.assignment, event.location, start_date[0] + " " + start_date[1] + " " + start_date[2], parseTime(start_time), parseTime(end_time), '<a href="mailto:' + event.owner_email + '">' + event.owner_name + '</a>'));
}
