// Invoke Color Picker
jQuery(document).ready(function ($) {
	$('.wp-color-picker').iris();	
	$(document).click(function ($) {
		if (!$(e.target).is(".wp-color-picker, .iris-picker, .iris-picker-inner")) {
			$('.wp-color-picker').iris('hide');
			return false;
		}
	});
	$('.wp-color-picker').click(function ($) {
		$('.wp-color-picker').iris('hide');
		$(this).iris('show');
		return false;
	});
});