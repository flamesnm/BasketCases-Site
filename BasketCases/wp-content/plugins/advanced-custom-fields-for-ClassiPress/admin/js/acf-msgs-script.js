jQuery(document).ready(function($) {
    $('#acf_error_msgs-table .format_name').each(function() {
            var value = $(this).html();
            var cont = field_formats[value].desc;
            $(this).easyTooltip({
                    content: cont,
                    yOffset: -20
            });
    });

    /* Default validation messages */
	var n;
    if (field_formats) {
            for (n in field_formats) {
                    if ($('#' + n + '_err').val() == '') {
                            var args = field_formats[n].args;
                            if (args == '2') {
                                    $('#' + n + '_err').val($.validator.messages[n]("{0}","{1}"));
                            } else if (args == '1') {
                                    $('#' + n + '_err').val($.validator.messages[n]("{0}"));
                            } else {
                                    $('#' + n + '_err').val($.validator.messages[n]);
                            }
                    }
            }
    }



});