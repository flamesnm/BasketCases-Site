jQuery(document).ready(function($) {

    /* Date Picker Settings  */

    // Date Picker Settings constructor
    function dateOptConstr() {
		var dateOptions = {};
		var dpLocale = $("#locale").val();
        dateOptions = $.datepicker.regional[dpLocale];
        dateOptions.showAnim = $("#animation").val();
        dateOptions.numberOfMonths = parseInt($("#multi_month").val(), 10);
        dateOptions.showButtonPanel = ($("#button_bar").attr("checked") === "checked") ? true : false;
        dateOptions.changeMonth = ($("#menus").attr("checked") === "checked") ? true : false;
        dateOptions.changeYear = ($("#menus").attr("checked") === "checked") ? true : false;
		dateOptions.minDate = ($("#minDate").val());
		dateOptions.maxDate = ($("#maxDate").val());
		dateOptions.yearRange = ($("#yearRange").val());
        dateOptions.showOtherMonths = ($("#other_dates").attr("checked") === "checked") ? true : false;
        dateOptions.selectOtherMonths = ($("#other_dates").attr("checked") === "checked") ? true : false;
        dateOptions.showOn = ($("#icon_trigger").attr("checked") === "checked") ? "both" : "focus";
        dateOptions.buttonImage = ($("#icon_trigger").attr("checked") === "checked") ? buttonImage : "";
        dateOptions.buttonImageOnly = ($("#icon_trigger").attr("checked") === "checked") ? true : false;
        dateOptions.dateFormat = ($("input[name=date_format]:checked").val() != 0) ? $("input[name=date_format]:checked").val() : dateOptions.dateFormat;
        return dateOptions;
    };

    $("#datepicker").datepicker(dateOptConstr());

    $("input[name=date_format]").change(function() {
        if ($("input[name=date_format]:checked").val() != 0) {
            $("#datepicker").datepicker("option", "dateFormat", $("input[name=date_format]:checked").val());
        } else {
            $("#datepicker").datepicker("destroy").datepicker(dateOptConstr());
        }
    });
    $("#custom_format_text").change(function() {
        $("#date_format_9").attr("value", $(this).val());
        $("#datepicker").datepicker("option", "dateFormat", $("#custom_format_text").val());
    });

    $("#locale, #animation, #multi_month, #button_bar, #menus, #other_dates, #icon_trigger, #minDate, #maxDate, #yearRange").change(function() {
        $("#datepicker").datepicker("destroy").datepicker(dateOptConstr());
    });

	$.validator.addMethod(
			"datevalidator",
			function(value, element) {
				var options = dateOptConstr();
				var format = options.dateFormat;
				var inst = $.datepicker._getInst(element);
				// parseDate throws exception if the value is invalid
				try {
					var date = $.datepicker.parseDate(format, value, options);
					var minDate = $.datepicker._determineDate(inst, $.datepicker._get(inst, 'minDate'), null);
					var maxDate = $.datepicker._determineDate(inst, $.datepicker._get(inst, 'maxDate'), null);
					return this.optional(element) || !date || ((!minDate || date >= minDate) && (!maxDate || date <= maxDate));
				}
				catch (e) {
					return false;
				}
			}, dateCustom_err
	);
});