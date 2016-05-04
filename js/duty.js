/**
 * duty.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	// Select all checkboxes of a user
	$('.set-user').click(function() {
		var user = $(this).attr('user');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="usr_' + user + '_' + day + '"]').prop('disabled')) {
				$('input[name="usr_' + user + '_' + day + '"]').prop('checked', true);
			}
		});
	});
	
	// Deselect all checkboxes of a user
	$('.unset-user').click(function() {
		var user = $(this).attr('user');
		var days = ['mon', 'tue', 'wed', 'thu', 'fri'];

		$.each(days, function(idx, day) {
			if (!$('input[name="usr_' + user + '_' + day + '"]').prop('disabled')) {
				$('input[name="usr_' + user + '_' + day + '"]').prop('checked', false);
			}
		});
	});
});