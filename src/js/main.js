/**
 * main.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	// Open links with class = blank on new tab/window
	$('a').on('click', function(e) {
		var href=$(this).attr('href');
		
		if (href == '#') {
			e.preventDefault();
		}
		if ($(this).hasClass('blank')) {
			e.preventDefault();
			var site = window.open(href);
			site.focus();
		}
	});
	
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