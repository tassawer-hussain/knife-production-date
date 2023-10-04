(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
        
        
        $(document).on('click', '#fetch_result', function() {
            var knife_number = $('input[name="knife_number"]').val();
            
            if(knife_number) {
                jQuery.ajax({
                    type: 'POST',
                    url: frontend_ajax_object.ajaxurl,
                    data: {
                        action: "produce_knife_production_date",
                        k_number: knife_number,
                    },
                    beforeSend: function() {
                        jQuery("#knife_result").empty();
                    },
                    success: function (response) {
                        jQuery("#knife_result").append(response);
                    },
                    complete: function(){
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError);
                    }
                });
                
            } else {
                jQuery("#knife_result").empty().append("Please enter a valid number");
            }
            
        });
        
        
        $(document).on('click', '#fetch_result_footer', function() {
            var knife_number = $('input[name="knife_number_footer"]').val();
            
            if(knife_number) {
                jQuery.ajax({
                    type: 'POST',
                    url: frontend_ajax_object.ajaxurl,
                    data: {
                        action: "produce_knife_production_date",
                        k_number: knife_number,
                    },
                    beforeSend: function() {
                        jQuery("#knife_result_footer").empty();
                    },
                    success: function (response) {
                        jQuery("#knife_result_footer").append(response);
                    },
                    complete: function(){
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError);
                    }
                });
                
            } else {
                jQuery("#knife_result_footer").empty().append("Please enter a valid number");
            }
            
        });
        

})( jQuery );
