jQuery(document).ready(function($) {
    $( "#tabs" ).tabs({ hide: { duration: 150 }, show: { duration: 150 } }).addClass( "ui-tabs-vertical" );
    $( "li.help-menu" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	$( ".ui-tabs-panel li" ).removeClass( "ui-corner-left" );
	$( "#dna_submit_btn" ).hide();
});