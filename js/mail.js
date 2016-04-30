$(function() {
	if (!$('input[value=select]').is(':checked'))
		$('#receiver-select').hide();
	
	$('input[name=receiver]').change(function() {
		if ($('input[value=select]').is(':checked'))
			$('#receiver-select').slideDown();
		else
			$('#receiver-select').slideUp();
	});
});