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
function ByStartTime(a,b) {
	return parseInt(a.start_time) - parseInt(b.start_time);
}
let searchResultEvents = false;
//$('#messages').prepend('<div class="ui error compact message"><i class="close icon"></i><div class="header">Your request was not received by the server.</div><p>If the problem persists, contact a Network Adminstrator.</p></div>');
function message(div_id, messageType, headerText, bodyText) {
  $('#'+div_id).prepend('<div><div class="ui ' + messageType + ' compact message"><i class="close icon"></i><div class="header">' + headerText + '</div><p>' + bodyText + '</p></div></div>');
}