jQuery(document).ready(function($) {
    /* Setup the form validation */
    $('#acf_options_form').validate({
        validClass: 'valid',
        errorClass: 'invalid',
        errorPlacement: function(error, element) {
            if (element.attr('type') === 'checkbox' || element.attr('type') === 'radio') {
                element.closest('ol').after(error);
            } else {
                offset = element.offset();
                error.insertAfter(element);
                error.addClass('message');  // add a class to the wrapper
            }
        }
    });
	//$('#acf_options_form').ajaxForm({dataType: 'json'});

});