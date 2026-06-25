/**
 * Crypto QR Code WP front-end behaviour.
 *
 * Generates each QR code in the browser with the bundled qrcode.js library and
 * handles opening and closing the tooltip dialog.
 */
jQuery( document ).ready( function ( $ ) {
	'use strict';

	// Render a QR code into every placeholder.
	if ( typeof QRCode !== 'undefined' ) {
		$( '.cqcw-block__qr' ).each( function () {
			var address = $( this ).attr( 'data-cqcw-address' );
			if ( ! address || $( this ).children().length ) {
				return;
			}
			var size = parseInt( $( this ).attr( 'data-cqcw-size' ), 10 ) || 180;
			new QRCode( this, {
				text: String( address ),
				width: size,
				height: size,
				colorDark: '#000000',
				colorLight: '#ffffff',
				correctLevel: QRCode.CorrectLevel.M
			} );
		} );
	}

	// Reset every open dialog.
	function cqcwResetState() {
		var doc = $( document );
		doc.find( '.cqcw-block.active' ).removeClass( 'active' );
		doc.find( '.cqcw-block__button.active' ).removeClass( 'active' );
		doc.find( '.cqcw-block__dialog.active' ).removeClass( 'active' );
	}

	// Toggle the QR dialog.
	$( document ).on( 'click', '.cqcw-block__button', function ( e ) {
		e.preventDefault();
		var dialogID = $( this ).attr( 'href' );
		var isActive = $( this ).hasClass( 'active' );
		cqcwResetState();
		if ( ! isActive ) {
			$( this ).addClass( 'active' );
			$( this ).closest( '.cqcw-block' ).addClass( 'active' );
			$( dialogID ).addClass( 'active' );
		}
	} );

	// Close button.
	$( document ).on( 'click', '.cqcw-block__button-close', function () {
		cqcwResetState();
	} );

	// Close dialogs on window resize.
	$( window ).on( 'resize', function () {
		if ( $( '.cqcw-block' ).length > 0 ) {
			cqcwResetState();
		}
	} );
} );
