/**
 * attendence.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('jQuery required');

$(function() {
	
	$('input[name*="week_"]').click(function() {
		var name = $(this).attr('name');
		var checked = $(this).is(':checked');
		var tmp = name.split('_');
		
		var week = parseInt(tmp[1]);
		var day = tmp[2];
		
		var req = { week: week, day: day, value: checked };
		api('sick_update', req, function(result) {
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