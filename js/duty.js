$('.set-user').click(function() {
		var user = $(this).attr('user');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="usr_' + user + '_' + day + '"]').prop('disabled')) {
				$('input[name="usr_' + user + '_' + day + '"]').prop('checked', true);
			}
		});
	});
	$('.unset-user').click(function() {
		var user = $(this).attr('user');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="usr_' + user + '_' + day + '"]').prop('disabled')) {
				$('input[name="usr_' + user + '_' + day + '"]').prop('checked', false);
			}
		});
	});