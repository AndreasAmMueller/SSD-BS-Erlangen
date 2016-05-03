$(function() {
	$('.set-week').click(function() {
		var week = $(this).attr('week');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="week_' + week + '_' + day + '"]').prop('disabled')) {
				$('input[name="week_' + week + '_' + day + '"]').prop('checked', true);
			}
		});
	});
	$('.unset-week').click(function() {
		var week = $(this).attr('week');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="week_' + week + '_' + day + '"]').prop('disabled')) {
				$('input[name="week_' + week + '_' + day + '"]').prop('checked', false);
			}
		});
	});

	$('.set-day').click(function() {
		var day = $(this).attr('day');

		$.each($('input[name*="_' + day + '"]'), function(idx, box) {
			if (!$(box).prop('disabled'))
				$(box).prop('checked', true);
		});
	});
	$('.unset-day').click(function() {
		var day = $(this).attr('day');

		$.each($('input[name*="_' + day + '"]'), function(idx, box) {
			if (!$(box).prop('disabled'))
				$(box).prop('checked', false);
		});
	});

	$('.unset').click(function() {
		$.each($('input[type=checkbox]'), function(idx, box) {
			$(box).prop('checked', false);
		});
	})
});