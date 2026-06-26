/**
 * Crypto QR Code WP — Appearance tab live preview.
 *
 * Renders a sample QR tooltip and updates it in real time as the admin changes
 * the size and color controls. Nothing is saved until the form is submitted; the
 * preview only reflects the current control values.
 */
jQuery( document ).ready( function ( $ ) {
	'use strict';

	var $block = $( '.cqcw-preview-block' );
	var $qr = $( '.cqcw-preview-qr' );
	if ( ! $block.length || ! $qr.length || typeof QRCode === 'undefined' ) {
		return;
	}

	var address = $qr.attr( 'data-cqcw-address' );

	// (Re)draw the preview QR from the current control values.
	function renderQR() {
		var size = parseInt( $( '#cqcw_qr_size' ).val(), 10 ) || 180;
		var fg = $( '#cqcw_qr_fg' ).val() || '#000000';
		var bg = $( '#cqcw_qr_bg' ).val() || '#ffffff';
		$qr.empty();
		new QRCode( $qr.get( 0 ), {
			text: String( address ),
			width: size,
			height: size,
			colorDark: fg,
			colorLight: bg,
			correctLevel: QRCode.CorrectLevel.M
		} );
	}

	// Apply a single control's value to the preview.
	function applyControl( el ) {
		var $el = $( el );
		var target = $el.attr( 'data-cqcw-target' );
		if ( 'var' === target ) {
			var cssVar = $el.attr( 'data-cqcw-var' );
			$block.get( 0 ).style.setProperty( cssVar, $el.val() );
			$el.closest( '.cqcw-control' ).find( '.cqcw-color-value' ).text( $el.val() );
		} else if ( 'qr-fg' === target || 'qr-bg' === target ) {
			$el.closest( '.cqcw-control' ).find( '.cqcw-color-value' ).text( $el.val() );
			renderQR();
		} else if ( 'size' === target ) {
			renderQR();
		}
	}

	// Live updates.
	$( document ).on( 'input change', '.cqcw-pv', function () {
		applyControl( this );
	} );

	// Reset every color control to its stored default, then redraw.
	$( document ).on( 'click', '.cqcw-reset-appearance', function () {
		$( '.cqcw-color' ).each( function () {
			var def = $( this ).attr( 'data-cqcw-default' );
			if ( def ) {
				$( this ).val( def );
				applyControl( this );
			}
		} );
	} );

	renderQR();
} );
