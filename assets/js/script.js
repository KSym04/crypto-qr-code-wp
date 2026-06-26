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
			var fg = $( this ).attr( 'data-cqcw-fg' ) || '#000000';
			var bg = $( this ).attr( 'data-cqcw-bg' ) || '#ffffff';
			new QRCode( this, {
				text: String( address ),
				width: size,
				height: size,
				colorDark: fg,
				colorLight: bg,
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

	// Copy the wallet address to the clipboard.
	$( document ).on( 'click', '.cqcw-block__copy', function () {
		var $btn = $( this );
		var address = $btn.attr( 'data-cqcw-copy' );
		var $label = $btn.find( '.cqcw-copy-label' );

		if ( ! address || $btn.hasClass( 'cqcw-copied' ) ) {
			return;
		}

		function flash() {
			var original = $label.text();
			$label.text( $btn.attr( 'data-cqcw-copied' ) || 'Copied' );
			$btn.addClass( 'cqcw-copied' );
			window.setTimeout( function () {
				$label.text( original );
				$btn.removeClass( 'cqcw-copied' );
			}, 1500 );
		}

		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			navigator.clipboard.writeText( address ).then( flash, flash );
		} else {
			var tmp = document.createElement( 'textarea' );
			tmp.value = address;
			tmp.setAttribute( 'readonly', '' );
			tmp.style.position = 'absolute';
			tmp.style.left = '-9999px';
			document.body.appendChild( tmp );
			tmp.select();
			try { document.execCommand( 'copy' ); } catch ( e ) {}
			document.body.removeChild( tmp );
			flash();
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
