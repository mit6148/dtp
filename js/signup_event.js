function signup(event_id) {
	$.ajax({
		type: 'POST',
		url: 'php/signup_event.php',
		data: {
			'event_id': event_id
		},
		cache: false
	}).done(function() {
		    //$('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event Added</div></div>');
        message('messages', 'success', 'Event Added', '');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    searchEvents();
    updateScheduleBody();
	})
	.fail(function() {
        message('messages', 'error', 'Your request was not received by the server.', 'If the problem persists, contact a Network Adminstrator.');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    });
}
function cancel_signup(event_id) {
  $.ajax({
    type: 'POST',
    url: 'php/cancel_signup_event.php',
    data: {
      'event_id': event_id
    },
    cache: false
  }).done(function() {
        //$('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event Removed</div></div>');
        message('messages', 'success', 'Event Removed', '');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    if (searched){
      searchEvents();
    }
    updateScheduleBody();
  })
  .fail(function() {
        //$('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your request was not received by the server.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
        message('messages', 'error', 'Your request was not received by the server.', 'If the problem persists, contact a Network Adminstrator.');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    });
}
