$(function() {
	$('.datepicker, .input-daterange').datepicker({
		format: 'dd.mm.yyyy',
		language: 'de',
		calendarWeeks: true,
		autoclose: true
	});

	$('#week').change(function() {
		$(this).parents('form').submit();
	});

	$('.no-js').hide();
	$('.need-js').show();
	$('[data-toggle="tooltip"]').tooltip().css('cursor', 'help');
});