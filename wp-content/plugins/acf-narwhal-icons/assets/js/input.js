(function($){

	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function formatSVGTemplate(svg){
		if (!svg.id) {
			return svg.text;
		}
		var $svgtemplate = $(
			'<div class="narwhal-icons-select2-template"><svg fill="#000" stroke="#000" width="20" height="20" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'+svg.id+'</svg><span>'+svg.text+'</span></div>'
		);

		$svgtemplate.find("svg").html(svg.id);
		$svgtemplate.find("span").html(svg.text);

		return $svgtemplate;
	}

	function initialize_field( $field ) {

		$(document).ready(function() {
			$field.find('select').select2({
				templateSelection: formatSVGTemplate,
				templateResult: formatSVGTemplate,
				dropdownCssClass: 'narwhal-icons-select2'
			});
		});
	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/

		acf.add_action('ready_field/type=narwhal_icons', initialize_field);
		acf.add_action('append_field/type=narwhal_icons', initialize_field);


	} else {

		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			// find all relevant fields
			$(postbox).find('.field[data-field_type="narwhal_icons"]').each(function(){

				// initialize
				initialize_field( $(this) );

			});

		});

	}

})(jQuery);
