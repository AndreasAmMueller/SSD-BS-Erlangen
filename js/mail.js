/**
 * mail.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	// Set the controls correctly
	if (!$('input[value=select]').is(':checked'))
		$('#receiver-select').hide();
	
	// Show the users if there is a selection
	// otherwise hide the select-fields
	$('input[name=receiver]').change(function() {
		if ($('input[value=select]').is(':checked'))
			$('#receiver-select').slideDown();
		else
			$('#receiver-select').slideUp();
	});
});