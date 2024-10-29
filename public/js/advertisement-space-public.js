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
})( jQuery );

jQuery(function() {
	jQuery('#advtModel').show();
	var minutes = 0, seconds = 15;
	  jQuery(function(){
	    jQuery("#advtModel #autoClose").html("( "+minutes + ":" + seconds+ " )");
	    var count = setInterval(function(){ 
	    	if(parseInt(minutes) < 0) { clearInterval(count); } 
	    	else {
	    		jQuery("#advtModel #autoClose").html("( "+minutes + ":" + seconds+" )");
	    	  seconds--; 
	    	  if(seconds == 0){
	    	  	clearInterval(count); 
				 jQuery(".advt-close").trigger("click");
				 jQuery('#advtModel').remove();
	    	  	// jQuery('.age-verification-overlay').remove();
	    	  }
	    	 
	    	} 
	    	}, 1000);
	  });
     jQuery(".advt-close").click(function($){
            jQuery('#advtModel').remove();
        });

     jQuery('.advt-close').on('click', function(e) {
        e.preventDefault();

        jQuery.ajax({
            type: 'POST',
            url: my_ajax_object.ajax_url,
            data: {
                _ajax_nonce: my_ajax_object.nonce,
                action: 'set_advertisement_space'
            },
            success: function(response) {
                jQuery('#advtModel').remove();
            }
        });
    });

     jQuery("#advClick").click(function($){
            const url = jQuery(this).data('url');
            const id = jQuery(this).data('id');
            window.open(url, '_blank');
            jQuery.ajax({
            type: 'POST',
            url: my_ajax_object.ajax_url,
            data: {
                _ajax_nonce: my_ajax_object.nonce,
                action: 'set_advertisement_click',
                id: id
            },
            success: function(response) {

                jQuery('#advtModel').remove();
                window.open(url, '_blank');

            }
        });
        });
});
