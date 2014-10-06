jQuery.extend({
   getScript: function(url, callback) {
      var head = document.getElementsByTagName("head")[0];
      var script = document.createElement("script");
      script.src = url;

      // Handle Script loading
      {
         var done = false;

         // Attach handlers for all browsers
         script.onload = script.onreadystatechange = function(){
            if ( !done && (!this.readyState ||
                  this.readyState == "loaded" || this.readyState == "complete") ) {
               done = true;
               if (callback)
                  callback();

               // Handle memory leak in IE
               script.onload = script.onreadystatechange = null;
            }
         };
      }

      head.appendChild(script);

      // We handle everything using the script element injection
      return undefined;
   }
});
jQuery(document).ready(function($) {
	var dna_tab = dna_vars.dna_default_tab;
	function post_option_tab(tab) {
		dna_loader($("#dna-tab-"+tab).position(), $("#dna-tab-"+tab).width());
		$('#dna-preloader').show();
		$('#dna_submit_btn').hide();

		data = {
			action: 'dna_ajax',
			dna_action: 'dna_get_option_tab',
			dna_tab: tab,
			dna_nonce: $('#dna_nonce').val(),
			dna_plugin: $('#dna_plugin').val()
		};
     	$.post(ajaxurl, data, function (response) {
			$('#dna_option_tab').html(response);
			var scripts = dna_tab_scripts;
			dna_tab_scripts = [];
			if(typeof scripts !== 'undefined'){
				//load_dna_scripts(dna_tab_scripts);
				$.ajaxSetup({cache: true});
				for (var i = 0; i < scripts.length; i++) {
					$.getScript(scripts[i]);
				}
			}
			dna_tab = tab;
			load_dna_bindings();
			$('th:first').removeClass('dna-col');
			$('.options-column').click(function(){dna_tabbed_table($(this));});
			dna_tabbed_table($('.options-column:first'));
			$('#dna_option_tab').fadeIn();
			$('#dna_submit_btn').show();
			$('#dna-preloader').hide();

		});
		return false;
	}

	/*function load_dna_scripts(scripts) {
		$.ajaxSetup({cache: true});
		for (var i = 0; i < scripts.length; i++) {
			$.getScript(scripts[i],function(){console.log(scripts[i]);});
		}
	}*/

	function load_dna_bindings() {
		/* Rows colors */
		$("tbody tr:even").addClass("even");
		$("tbody tr:odd").addClass("alt");

		/* Tool Tips */
		$(".titletip").easyTooltip({
			yOffset: -20
		});
		$(".titletip, .tabtip").mousemove(function(e) {
			$("#easyTooltip").css("left", ((document.body.offsetWidth - e.pageX < 430) ? document.body.offsetWidth - 430 : e.pageX) + "px");
		});
		$('#'+dna_vars.dna_options_form).ajaxForm(dna_submit());
	}

	$(".dna-tab:first").addClass('nav-tab-active');

	post_option_tab(dna_vars.dna_default_tab);

	function dna_loader(position, whidth){
		$("#dna-preloader").css('margin-left', position.left).css('width', whidth+30);
	}

	/* Navigation tabs */
	$(".dna-tab").click(function(){
		if($(this).hasClass('nav-tab-active') !== true){
			$(".dna-tab").removeClass('nav-tab-active');
			//$("#dna-preloader").appendTo($(this));
			$(this).addClass('nav-tab-active');
			$('#dna_option_tab').fadeOut();
			//dna_loader($(this).position(), $(this).width());
			post_option_tab($(this).attr('dna_tab'));

		}
		return false;
	});

	$(".tabtip").easyTooltip({
		yOffset: -20
	});

	function dna_submit(){
		var options = {
			url: ajaxurl,
			data: {
				action: 'dna_ajax',
				dna_action: 'dna_submit',
				dna_tab: dna_tab,
				dna_nonce: $('#dna_nonce').val(),
				dna_plugin: $('#dna_plugin').val()
			},
			beforeSubmit: function() {
				dna_loader($("#dna-tab-"+dna_tab).position(), $("#dna-tab-"+dna_tab).width());
				$('#dna-preloader').show();
			},
			success:    function() {
				$('#dna-preloader').hide();
			}
		};
		return options;
	}
	//$('#'+dna_vars.dna_options_form).submit(function(){$(this).ajaxForm(dna_submit());});
	$('#'+dna_vars.dna_options_form).ajaxForm(dna_submit());

	function dna_tabbed_table(tab){
		var col = $(tab).attr('id');
		$(".options-column").removeClass('nav-tab-active');
		$(tab).addClass('nav-tab-active');
		$('td.'+col).fadeIn();
		$('tr.'+col).fadeIn();
		$('th.'+col).show();
		$(".dna-col:not(."+col+")").hide();
	}


});