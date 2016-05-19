/**
 * user.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('PHP-API requires jQuery');

/**
 * action_switch
 * 
 * En-/Disables fields due to the selected action, that should be perfomed.
 */
function action_switch() {
	switch ($('select[name=action]').val()) {
		case 'new':
			$('#user-user').hide();
			$('#user-name').show();
			$('#user-email').show();
			$('#user-password').show();
			$('#user-password label').addClass('required');
			$('#user-class').show();
			$('#user-mobile').show();
			$('#user-permission').show();
			
			$('input[name=name]').val('');
			$('input[name=firstname]').val('');
			$('input[name=email]').val('');
			$('input[name=password]').val('').attr('placeholder', 'Das Passwort MUSS gesetzt werden');
			$('input[name=class]').val('');
			$('input[name=room]').val('');
			$('input[name=mobile]').val('');
			$('input[name=qualification]').val('');
			
			$.each($('input[type=checkbox]'), function(idx, box) {
				$(box).prop('checked', false);
			});
			break;
		case 'edit':
			user_switch();
		case 'delete':
			$('#user-user').show();
			$('#user-name').hide();
			$('#user-email').hide();
			$('#user-password').hide();
			$('#user-password label').removeClass('required');
			$('#user-class').hide();
			$('#user-mobile').hide();
			$('#user-permission').hide();
			
			$('input[name=password]').val('').attr('placeholder', 'Nur zum Ändern eintragen');
			break;
		default:
			$('#user-user').hide();
			$('#user-name').hide();
			$('#user-email').hide();
			$('#user-password').hide();
			$('#user-class').hide();
			$('#user-mobile').hide();
			$('#user-permission').hide();
			break;
	}
}

/**
 * user_switch
 * 
 * Loads more information corresponding to the selected user.
 * These data are filled in the form fields.
 */
function user_switch() {
	if ($('select[name=action]').val() == 'delete')
		return;

	if ($('select[name=user]').val() === '') {
		$('#user-name').hide();
		$('#user-email').hide();
		$('#user-password').hide();
		$('#user-class').hide();
		$('#user-mobile').hide();
		$('#user-permission').hide();
	} else {
		api('user_getUser', $('select[name=user]').val(), function(res) {
			if (res.error === '') {
				$('#user-name').show();
				$('#user-email').show();
				$('#user-password').show();
				$('#user-class').show();
				$('#user-mobile').show();
				$('#user-permission').show();
				
				$('input[name=name]').val(res.data.name);
				$('input[name=firstname]').val(res.data.firstname);
				$('input[name=email]').val(res.data.email);
				$('input[name=class]').val(res.data.class);
				$('input[name=room]').val(res.data.room);
				$('input[name=mobile]').val(res.data.mobile);
				$('input[name=qualification]').val(res.data.qualification);
				
				$.each($('input[type=checkbox]'), function(idx, box) {
					$(box).prop('checked', false);
				});
				
				$.each(res.data.permissions, function(idx, perm) {
					$('input[value="' + perm + '"]').prop('checked', true);
				});
			} else {
				console.log("Error:\n" + res.error);
			}
		});
	}
}

$(function() {
	// Prepare the form.
	action_switch();
	
	// Show the fields, needed for the selected action.
	$('select[name=action]').change(function() {
		action_switch();
	});
	
	// Load the data for the selected user.
	$('select[name=user]').change(function() {
		user_switch();
	});
});