/**
 * Crypto QR Code WP admin settings behaviour.
 *
 * Handles the wallet repeater: add / remove rows, a live shortcode preview, and
 * a copy to clipboard button. No external requests.
 */
jQuery( document ).ready( function ( $ ) {
	'use strict';

	var l10n = ( typeof cqcwAdmin !== 'undefined' ) ? cqcwAdmin : {};
	var $body = $( '.cqcw-wallets-body' );
	var $counter = $( '.cqcw-next-index' );

	function nextIndex() {
		var n = parseInt( $counter.attr( 'data-next' ), 10 ) || 0;
		$counter.attr( 'data-next', n + 1 );
		return n;
	}

	function escapeAttr( value ) {
		return String( value ).replace( /"/g, '' );
	}

	// Rebuild a row's shortcode preview from its current field values.
	function refreshShortcode( $row ) {
		var label = escapeAttr( $row.find( '.cqcw-field-label' ).val() || '' );
		var address = escapeAttr( $row.find( '.cqcw-field-address' ).val() || '' );
		var heading = escapeAttr( $row.find( '.cqcw-field-heading' ).val() || '' );
		if ( ! heading ) {
			heading = l10n.defaultHeading || 'Donate';
		}
		var shortcode = '[cqcw_generator heading="' + heading + '" label="' + label + '" address="' + address + '"]';
		$row.find( '.cqcw-shortcode' ).val( shortcode );
	}

	// Add a wallet row from the template.
	$( '.cqcw-add-wallet' ).on( 'click', function () {
		var html = $( '#cqcw-row-template' ).html().replace( /__INDEX__/g, nextIndex() );
		var $row = $( html );
		$body.append( $row );
		refreshShortcode( $row );
		$row.find( '.cqcw-field-label' ).trigger( 'focus' );
	} );

	// Remove a wallet row.
	$body.on( 'click', '.cqcw-remove', function () {
		if ( l10n.confirmRemove && ! window.confirm( l10n.confirmRemove ) ) {
			return;
		}
		$( this ).closest( '.cqcw-row' ).remove();
		if ( $body.find( '.cqcw-row' ).length === 0 ) {
			$( '.cqcw-add-wallet' ).trigger( 'click' );
		}
	} );

	// Live update the shortcode preview.
	$body.on( 'input', '.cqcw-field', function () {
		refreshShortcode( $( this ).closest( '.cqcw-row' ) );
	} );

	// Copy a shortcode to the clipboard.
	$body.on( 'click', '.cqcw-copy', function () {
		var $btn = $( this );
		var $input = $btn.closest( '.cqcw-shortcode-cell' ).find( '.cqcw-shortcode' );
		var value = $input.val();

		function flash() {
			var $icon = $btn.find( '.dashicons' );
			$icon.removeClass( 'dashicons-clipboard' ).addClass( 'dashicons-yes' );
			$btn.addClass( 'cqcw-copied' );
			window.setTimeout( function () {
				$icon.removeClass( 'dashicons-yes' ).addClass( 'dashicons-clipboard' );
				$btn.removeClass( 'cqcw-copied' );
			}, 1500 );
		}

		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			navigator.clipboard.writeText( value ).then( flash, function () {
				$input.trigger( 'focus' )[ 0 ].select();
				document.execCommand( 'copy' );
				flash();
			} );
		} else {
			$input.trigger( 'focus' )[ 0 ].select();
			document.execCommand( 'copy' );
			flash();
		}
	} );
} );
