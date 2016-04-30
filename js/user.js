function action_switch() {
	switch ($('select[name=action]').val()) {
		case 'new':
			$('#user-user').hide();
			$('#user-name').show().val('');
			$('#user-email').show().val('');
			$('#user-password').show().val('');
			$('#user-password label').addClass('required').prop('required', true);
			$('#user-class').show().val('');
			$('#user-mobile').show().val('');
			$('#user-permission').show().val('');
			break;
		case 'edit':
		case 'delete':
			$('#user-user').show();
			$('#user-name').hide();
			$('#user-email').hide();
			$('#user-password').hide();
			$('#user-password label').removeClass('required').prop('required', false);
			$('#user-class').hide();
			$('#user-mobile').hide();
			$('#user-permission').hide();
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

$(function() {
	action_switch();
	
	$('select[name=action]').change(function() {
		action_switch();
	});
});