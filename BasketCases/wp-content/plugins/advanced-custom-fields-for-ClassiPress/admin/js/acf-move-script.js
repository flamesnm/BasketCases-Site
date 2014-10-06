jQuery(document).ready(function($) {
    $.validator.addMethod("accept", function (value, element) {
        return true;
    }, '');
});