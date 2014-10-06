jQuery(document).ready(function($) {
    /* Check all checkboxes in the column */
    $('.col_check').click(function() {
            var col = $(this).attr('id');
            if (this.checked) {
                    $('.' + col).attr("checked", "checked");
            }
            else {
                    $('.' + col).removeAttr("checked");
            }
            return true;
    });

    $(".field_limits_attr").each(function() {
            $(this).rules("add", {
                    numericdelim: true
            });
    });

    $('.deletecross').click(function() {
        if (confirm("WARNING: Deleting this field will prevent any existing ads currently using this field from displaying the field value. Deleting fields is NOT recommended unless you do not have any existing ads using this field. Are you sure you want to delete this field?? (This cannot be undone)")){
			var id = $(this).attr('id');
			$("#deleted_fields").val($("#deleted_fields").val()+","+id);
			$(this).closest('tr').fadeOut().remove();
		}
    });
});