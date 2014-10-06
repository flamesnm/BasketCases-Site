/*
 * Fix some bugs in $.Validation plugin and add new methods.
 *
 */
jQuery(document).ready(function($) {
    //Fix native methods
    $.validator.addMethod("range", function (value, element, param) {
        param = element.attributes.range.value.split(",");
        return +value >= Number(param[0]) && value <= Number(param[1]);
    }, $.validator.messages.range);

    $.validator.addMethod("rangelength", function (value, element, param) {
        param = element.attributes.rangelength.value.split(",");
        var length = this.getLength($.trim(value), element);
        return length >= Number(param[0]) && length <= Number(param[1]);
    }, $.validator.messages.rangelength);

    $.validator.addMethod("minlength", function (value, element, param) {
        return this.getLength($.trim(value), element) >= +param;
    }, $.validator.messages.minlength);

    $.validator.addMethod("maxlength", function (value, element, param) {
        return this.optional(element) || this.getLength($.trim(value), element) <= +param;
    }, $.validator.messages.maxlength);


    $.stripHtml = function(value) {
        // remove html tags and space chars
        return value.replace(/<.[^<>]*?>/g, '').replace(/&nbsp;|&#160;/gi, '')
        // remove numbers and punctuation
        .replace(/[0-9.(),;:!?%#$'"_+=\/-]*/g,'').replace(/\s+/gi,',');
    };

    $.validator.addMethod("maxWords", function (value, element, params) {
        return this.optional(element) || $.stripHtml($.trim(value)).split(",").length <= +params;
    }, $.validator.messages.maxWords);

    $.validator.addMethod("minWords", function (value, element, params) {
        var len = 0;
        value = $.stripHtml($.trim(value));
        if (value != "") len = value.split(",").length;
        return len >= +params;
    }, $.validator.messages.minWords);

    $.validator.addMethod("rangeWords", function (value, element, params) {
        var len = 0;
        params = element.attributes.rangeWords.value.split(",");
        value = $.stripHtml($.trim(value));
        if (value != "") len = value.split(",").length;
        return len >= Number(params[0]) && len <= Number(params[1]);
    }, $.validator.messages.rangeWords);

    $.validator.addMethod("number", function (value, element) {
        return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
    }, $.validator.messages.number);

    $.validator.addMethod("min", function (value, element, param) {
        value = value.replace(",", ".");
        return +value >= +param;
    }, $.validator.messages.min);

    $.validator.addMethod("max", function (value, element , param) {
        value = value.replace(",", ".");
        return this.optional(element) || +value <= +param;
    }, $.validator.messages.max);

    //Additional methods

    $.validator.addMethod("phone", function (value, element) {
        return this.optional(element) || /^(([\+]\d{1,3})?[ \.-]?[\(]?\d{3}[\)]?)?[ \.-]?\d{3}[ \.-]?\d{4}$/.test(value);
    }, $.validator.messages.phoneUS);

    $.validator.addMethod("phone_4_4", function (value, element) {
        return this.optional(element) || /^\d{4}[ \.-]?\d{4}$/.test(value);
    }, $.validator.messages.phoneUS);

    $.validator.addMethod("numeric_ws", function (value, element) {
        return this.optional(element) || /^[\s0-9]+$/.test(value);
    }, $.validator.format("Numbers only or white space please"));

    $.collocationsAndDelim = function(value) {
        // remove html tags and space chars
        return value.replace(/<.[^<>]*?>/g, '').replace(/&nbsp;|&#160;/gi, '')
        // remove numbers and punctuation
        .replace(/[0-9.();:!?%#$'"_+=\/-]*/g,'');
    };

    $.validator.addMethod("maxcollocations", function (value, element, params) {
        value = $.collocationsAndDelim(value).split(",");
    boolval = true;
        for(i = 0; i < value.length; ++i) {
            if (/^(?=.*\S).+$/.test(value[i]) == false){
                boolval = false;
            }
        }
        return this.optional(element) || boolval && value.length <= params;
    }, $.validator.format('Please enter {0} collocations or less.'));

    $.validator.addMethod("mincollocations", function (value, element, params) {
        value = $.collocationsAndDelim(value).split(",");
    boolval = true;
        for(i = 0; i < value.length; ++i) {
            if (/^(?=.*\S).+$/.test(value[i]) == false){
                boolval = false;
            }
        }
        return boolval && value.length >= params;
    }, $.validator.format('Please enter at least {0} collocations.'));

    $.validator.addMethod("rangecollocations", function (value, element, params) {
        params = element.attributes.rangecollocations.value.split(",");
        value = $.collocationsAndDelim(value).split(",");
    boolval = true;
        for(i = 0; i < value.length; ++i) {
            if (/^(?=.*\S).+$/.test(value[i]) == false){
                boolval = false;
            }
        }
        return boolval && value.length >= Number(params[0]) && value.length <= Number(params[1]);
    }, $.validator.format("Please enter at least {0} and no more than {1} collocations."));

    $.validator.addMethod("numericdelim", function (value, element) {
        var valarr = value.split(",");
        return this.optional(element) || /^[0-9,]+$/.test(value) && /^[0-9]+$/.test(valarr[valarr.length-1]);
    }, 'Only numbers and delimitter "," !');


    $.getCheckedBoxes = function(chkboxName) {
      var checkboxes = document.getElementsByName(chkboxName);
      var checkboxesChecked = [];
      // loop over them all
      for (var i=0; i<checkboxes.length; i++) {
	 // And stick the checked ones onto an array...
	 if (checkboxes[i].checked) {
	    checkboxesChecked.push(checkboxes[i]);
	 }
      }
      return checkboxesChecked;
    };

    $.validator.addMethod("rangechoice", function (value, element, param) {
        param = element.attributes.rangechoice.value.split(",");
	var length = $.getCheckedBoxes(element.name).length;
        return length >= Number(param[0]) && length <= Number(param[1]);
    }, $.validator.format("Please select at least {0} and no more than {1} items."));

    $.validator.addMethod("minchoice", function (value, element, param) {
        return $.getCheckedBoxes(element.name).length >= Number(param);
    }, $.validator.format("Please select at least {0} items."));

    $.validator.addMethod("maxchoice", function (value, element, param) {
        return $.getCheckedBoxes(element.name).length <= Number(param);
    }, $.validator.format("Please select no more then {0} items."));

	$.validator.addMethod(
			"dateCustom",
			function(value, element) {
				if ( typeof $.acf_date === 'undefined' ) {
					$.acf_date = $.datepicker.regional["en-GB"];
				}
				var options = $.acf_date;
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
			}, 'Please enter date in valid format!'
	);


    if ( typeof validate_msgs !== 'undefined' && validate_msgs.length != 0 ){
        for(var errmsg in validate_msgs){
            var ms = validate_msgs[errmsg];
            if (ms != "") $.validator.messages[errmsg] = $.validator.format(ms);
        }
    }
});