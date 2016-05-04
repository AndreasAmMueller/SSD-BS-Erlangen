/**
 * main.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	// Enable the datepicker-functionality
	$('.datepicker, .input-daterange').datepicker({
		format: 'dd.mm.yyyy',
		language: 'de',
		calendarWeeks: true,
		autoclose: true
	});
	
	// Enable tooltips
	$('[data-toggle="tooltip"]').tooltip().css('cursor', 'help');

	// Enable auto-submit for all inputs with id = week
	// Used on the selection at the main site and the disposition
	$('#week').change(function() {
		$(this).parents('form').submit();
	});

	// Hide / show some fields
	$('.no-js').hide();
	$('.need-js').show();
});