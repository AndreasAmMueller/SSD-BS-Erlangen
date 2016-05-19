/**
 * attendence.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	
	// Select all checkboxes matching 'week_xx_*'
	// Where xx is the calendar week
	$('.set-week').click(function() {
		var week = $(this).attr('week');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="week_' + week + '_' + day + '"]').prop('disabled')) {
				$('input[name="week_' + week + '_' + day + '"]').prop('checked', true);
			}
		});
	});
	
	// Deselect all checkboxes matching 'week_xx_*'
	// Where xx is the calendar week
	$('.unset-week').click(function() {
		var week = $(this).attr('week');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="week_' + week + '_' + day + '"]').prop('disabled')) {
				$('input[name="week_' + week + '_' + day + '"]').prop('checked', false);
			}
		});
	});

	// Select all checkboxes matching 'week_*_xx'
	// Where xx is the day of the week
	$('.set-day').click(function() {
		var day = $(this).attr('day');

		$.each($('input[name*="_' + day + '"]'), function(idx, box) {
			if (!$(box).prop('disabled'))
				$(box).prop('checked', true);
		});
	});
	
	// Deselect all checkboxes matching 'week_*_xx'
	// Where xx is the day of the week
	$('.unset-day').click(function() {
		var day = $(this).attr('day');

		$.each($('input[name*="_' + day + '"]'), function(idx, box) {
			if (!$(box).prop('disabled'))
				$(box).prop('checked', false);
		});
	});

	// Deselect all checkboxes of the site
	$('.unset').click(function() {
		$.each($('input[type=checkbox]'), function(idx, box) {
			$(box).prop('checked', false);
		});
	});
	
	$('input[name*="week_"]').click(function() {
		var name = $(this).attr('name');
		var checked = $(this).is(':checked');
		var tmp = name.split('_');
		
		var week = parseInt(tmp[1]);
		var day = tmp[2];
		
		var req = { week: week, day: day, value: checked };
		api('att_update', req, function(result) {
			if (result.error === '')
			{
				if (!result.data)
					$(this).prop('checked', !checked);
			}
			else
			{
				console.log("Error:\n" + result.error);
			}
		});
	});
	
	
	
});