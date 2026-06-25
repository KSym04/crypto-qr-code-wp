<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Add an init CSS class on the body so the element styling stays scoped.
 *
 * @since 1.0.2
 * @package Crypto QR Code WP
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function crypto_qr_code_wp_body_class( $classes ) {
	$classes[] = 'cqcw-init';
	return $classes;
}
add_filter( 'body_class', 'crypto_qr_code_wp_body_class' );
