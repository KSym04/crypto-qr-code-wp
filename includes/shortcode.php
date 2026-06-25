<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Render the Crypto QR Code element.
 *
 * The wallet address is placed in a data attribute and the QR code is drawn in
 * the browser by the bundled qrcode.js library. Nothing is written to the server.
 *
 * Usage: [cqcw_generator heading="Donate" label="BTC" address="bc1q..."]
 *
 * @since 1.0.1
 * @package Crypto QR Code WP
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function crypto_qr_code_wp_shortcode_logic( $atts ) {
	// Parse and sanitize shortcode attributes.
	$a = shortcode_atts( array(
		'heading' => __( 'Donate', 'crypto-qr-code-wp' ),
		'label'   => '',
		'address' => '',
	), $atts, 'cqcw_generator' );

	$heading = sanitize_text_field( $a['heading'] );
	$label   = sanitize_text_field( $a['label'] );
	$address = sanitize_text_field( $a['address'] );

	// A label and an address are required.
	if( empty( $address ) || empty( $label ) ) {
		return '';
	}

	// Enqueue assets only when the shortcode is actually used on the page.
	wp_enqueue_style( 'crypto-qr-code-wp' );
	wp_enqueue_script( 'crypto-qr-code-wp' );

	// QR size from the settings page, clamped to a sane range.
	$options = get_option( 'cqcw_settings', array() );
	$qr_size = isset( $options['qr_size'] ) ? absint( $options['qr_size'] ) : 180;
	if( $qr_size < 80 || $qr_size > 400 ) {
		$qr_size = 180;
	}

	// Unique dialog id per instance (compatible back to WordPress 4.7).
	static $cqcw_instance = 0;
	$cqcw_instance++;
	$dialog_id = 'cqcw-dialog-' . $cqcw_instance;

	$content = sprintf(
		'<span class="cqcw-block">'
			. '<label class="cqcw-block__label">%1$s:</label> '
			. '<a href="#%3$s" class="cqcw-block__button" aria-haspopup="true">%2$s</a>'
			. '<em id="%3$s" class="cqcw-block__dialog">'
				. '<strong class="cqcw-block__dialog-heading">%4$s %1$s</strong>'
				. '<span class="cqcw-block__qr" data-cqcw-address="%5$s" data-cqcw-size="%8$s" role="img" aria-label="%6$s"></span>'
				. '<strong class="cqcw-block__dialog-content">%2$s</strong>'
				. '<button type="button" class="cqcw-block__button-close" aria-label="%7$s">&times;</button>'
			. '</em>'
		. '</span>',
		esc_html( $label ),
		esc_html( $address ),
		esc_attr( $dialog_id ),
		esc_html( $heading ),
		esc_attr( $address ),
		/* translators: %s: cryptocurrency label such as BTC. */
		esc_attr( sprintf( __( 'QR code for the %s wallet address', 'crypto-qr-code-wp' ), $label ) ),
		esc_attr__( 'Close', 'crypto-qr-code-wp' ),
		esc_attr( $qr_size )
	);

	return apply_filters( 'crypto_qr_code_wp_shortcode', $content, $a );
}
add_shortcode( 'cqcw_generator', 'crypto_qr_code_wp_shortcode_logic' );
