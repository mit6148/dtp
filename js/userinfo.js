$('#viewUserinfoModal').on('click', function() {
	$('#userinfoModal').modal('show');
});
$('#viewUserinfoModalMobile').on('click', function() {
	$('#userinfoModal').modal('show');
});
$('#newIcalId').on('click', function() {
	$.ajax({
		method : 'GET',
		cache: false,
		url: 'php/new_ical_id.php'
	})
	.done(function(res) {
		$('#ical_id').val(index_url + 'php/ical.php?id=' + res);
	})
	.fail(console.log);
});
