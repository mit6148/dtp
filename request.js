$.ajax({
    method : 'GET',
    data : {},
    dataType : "json",
    url: "comment.php",
    cache: false,
})
    .done(function(json) {
        console.log(json.data)
        for i in json.data {
            result += handleComment(i.data)
        }
        $.(".comments").html() = result
    }
    .fail(function() {
        $.(".comments").html() = '<p class="error">Error: Unable to connect to comment.php</p>';
    });
function handleComment() {
    //To Do
}

function submitComment() {
    commentData = {
        comment : $.("#submit").html(),
    }
    console.log($.("#submit").html());
    $.ajax({
        method : 'POST',
        data : commentData,
        dataType : "json",
        url: "comment.php",
    })
}
