jQuery(document).ready(function($) {
    /* Rewrites indexes of the fields after removing and sorting rows */
     function row_indexes(row, i) {
        var n, len, prop, attr;
        row.attr('data-array_index', i);
        for (n = 0, len = field_properties.length; n < len; n++) {
            prop = field_properties[n];
            attr = 'field_' + prop + '_' + i;
            row.find('.field_' + prop).attr('name', attr).attr('id', attr);
        }
    };

    /* Field type change event listener */
    function field_type_change (field) {
        var row = field.closest('tr');
        var values = row.find('.field_values');
        var type = field.val();
        if (type === "drop-down" || type === "checkbox" || type === "radio") {
            values.fadeIn();
            values.rules("add", {
                required: true
            });
        } else {
            values.rules("remove");
            values.fadeOut();
            values.parent().find('label').remove();
        }
    };

    /* Delete row */
    function delete_item (td) {
        if (confirm("Are you sure you want to delete this item?")) {
            td.closest('tr').remove();
            var row = $('#acf_profile_field-table').find('tbody').find('tr');
            var len = row.length - 1;
            row.each(function(i) {
                if (i != len) {
                    row_indexes($(this), i + 1);
                    field_type_change($(this).find('.field_type'));
                }
            });
        }
    };




    /* Add Rules */
    $(".field_name").each(function() {
        if ($(this).attr("type") === "text" && $(this).attr("id") !== "field_name_") {
            $(this).rules("add", {
                required: true,
                alphanumeric: true
            });
        }
    });

    $(".field_limits_attr").each(function() {
        $(this).rules("add", {
            numericdelim: true
        });
    });

    $('.field_type').change(function() {
        field_type_change($(this));
    });
    $('.field_type').change();

    /* Add new item event listener */
    $('#acf_add-field-btn').click(function() {
        var template_row = $('#template_row').clone(withDataAndEvents = true);
        var field_name = template_row.find('.field_name');
        var rowid = $('#acf_profile_field-table').find('tbody').find('tr').length;

        template_row.attr('id', '').addClass('even');

        row_indexes(template_row, rowid);

        template_row.insertBefore('#template_row').hide().fadeIn();

        field_name.rules("add", {
            required: true,
            alphanumeric: true
        });
        field_type_change(template_row.find('.field_type'));
    });


    $('.row_actions').click(function() {
        delete_item($(this));
    });

    /* Sortable Profile rows */
    // Return a helper with preserved width of cells
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };
    $("#acf_profile_field-table").sortable({
        items: 'tbody tr',
        helper: fixHelper,
        opacity: 0.9,
        revert: true,
        axis: 'y',
        cursor: 'move',
        placeholder: 'ui-placeholder',
        forcePlaceholderSize: true,
        update: function(event, ui) {
            var row = $('#acf_profile_field-table').find('tbody').find('tr');
            var len = row.length - 1;
            row.each(function(i) {
                if (i != len) {
                    row_indexes($(this), i + 1);
                    field_type_change($(this).find('.field_type'));
                }
            });
        }
    }); //.disableSelection(); removed util solution could be found;


    /* Form Submit event listener */
   $('#'+dna_vars.dna_options_form).submit(function() {
        if ($('#acf_options_form').valid()) {
            $('#template_row').remove();
            return true;
        } else {
            return false;
        }
    });
});