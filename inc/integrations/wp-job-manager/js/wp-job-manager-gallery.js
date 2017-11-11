(function($) {

	window.cGallery = window.cGallery || {};

	var gallery = gallery || {
		archive: null
	};

	cGallery.Archive = function() {
		var self = this;

		$( '.gallery-overlay-trigger' ).magnificPopup({
			type: 'ajax',
			tClose: listifySettings.l10n.magnific.tClose,
			tLoading: listifySettings.l10n.magnific.tLoading,
			ajax: {
				settings: {
					type: 'GET',
					data: { 'view': 'singular' }
				}
			},
			gallery: {
				enabled: true,
				preload: [1,1]
			},
			callbacks: {
				open: function() {
					$( 'body' ).addClass( 'gallery-overlay' );
				},
				close: function() {
					$( 'body' ).removeClass( 'gallery-overlay' );
				},
				lazyLoad: function(item) {
					var $thumb = $( item.el ).data( 'src' );
				},
				parseAjax: function(mfpResponse) {
					mfpResponse.data = $(mfpResponse.data).find( '#main' );
					mfpResponse.data.find( '.single-job_listing-attachment' ).append( $( this.arrowLeft ) ).append( $( this.arrowRight ) );
				}
			}
		});

		if ( window.location.hash ) {
			var hash = window.location.hash.substring(1);

			if ( $( 'a[href="' + hash + '"]:first' ).length ) {
				$( $( 'a[href="' + hash + '"]:first' ) ).trigger( 'click' );
			}
		}
	}

	$(function() {
		gallery.archive = new cGallery.Archive();
	});

})(jQuery);
