<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Add init CSS class on body to ensure styling compatibility.
 * 
 * @since 1.0.2
 * @package Crypto QR Code WP
 */
function crypto_qr_code_wp_body_class( $classes ) {
    global $post;
    if( ! empty( $post ) ) {
        $classes[] = 'cqcw-init';
    }
    return $classes;
}
add_filter( 'body_class', 'crypto_qr_code_wp_body_class' );