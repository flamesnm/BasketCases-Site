/*
 * Validation parameters was taken from AppThemes theme-script.js
 * And used for registration and profiel forms
 */

jQuery(document).ready(function($) {
    // Validation parameters

	var acf_validate = {
		ignore: '.ignore',
		errorClass: 'invalid',
		errorPlacement: function(error, element) {
			if (element.attr('type') == 'checkbox' || element.attr('type') == 'radio') {
				element.closest('ol').after(error);
			} else if ( jQuery.isFunction( jQuery.fn.selectBox ) && element.is('select') ) {
				if ( jQuery(window).width() > 600 ) {
					var nextelement = jQuery(element).next();
					error.insertAfter(nextelement);
					error.css('display', 'inline-block');
				} else {
					error.insertBefore(element);
				}
			} else {
				if ( jQuery(window).width() > 600 ) {
					error.insertAfter(element);
					error.css('display', 'inline-block');
				} else {
					error.insertBefore(element);
				}
			}
		},
		highlight: function(element, errorClass, validClass) {
			jQuery(element).addClass(errorClass).removeClass(validClass);
			jQuery(element).parent().find('a.selectBox').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function(element, errorClass, validClass) {
			jQuery(element).removeClass(errorClass).addClass(validClass);
			jQuery(element).parent().find('a.selectBox').removeClass(errorClass).addClass(validClass);
		}
	};

	$('#registerform').validate(acf_validate);
	$('#your-profile').data('validator', null).unbind('validate').validate(acf_validate);
});