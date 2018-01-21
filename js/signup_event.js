function signup(event_id) {
	$.ajax({
		type: 'POST',
		url: 'php/signup_event.php',
		data: {
			'event_id': event_id
		},
		cache: false
	}).done(function() {
		$('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event Added</div></div>');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    eventButton(event_id,true);
    updateScheduleBody();
	})
	.fail(function() {
        $('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your request was not received by the server.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
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
    $('#messages').prepend('<div class="ui success compact message"><i class="close icon"></i><div class="header">Event Removed</div></div>');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    if (searchResultEvents){
      if (searchResultEvents.map(function(a){return parseInt(a.id)}).indexOf(event_id)!=-1){
        eventButton(event_id,false);
      }
    }
    updateScheduleBody();
  })
  .fail(function() {
        $('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your request was not received by the server.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
        $('.message .close').off('click');
        $('.message .close').on('click', function() {
            $(this)
              .closest('.message')
              .transition('fade')
            ;
        });
    });
}
