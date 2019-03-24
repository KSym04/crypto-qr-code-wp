<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

function crypto_qr_code_wp_shortcode_logic( $atts ) {
	// Parse shortcode attributes.
	$a = shortcode_atts( array(
        'heading' => esc_html__( 'Donate', 'crypto-qr-code-wp' ),
		'label'   => '',
		'address' => ''
    ), $atts );

    if( empty( $a['address'] ) || empty( $a['label'] ) ) {
        return;
    }
    
    // Set variables.
    $content = NULL;
    settype( $content, 'string' );

    $heading = $a['heading'];
    $label   = $a['label'];
    $address = $a['address'];

    // Generate QR address.
    $qr_svgCode_generate = QRcode::svg( $address, CRYPTO_QR_CODE_WP_UPLOADS . 'uploads/crypto-qr-codes/' . $address . '.svg' );
    $qr_svgCode_url = CRYPTO_QR_CODE_WP_URL . 'uploads/crypto-qr-codes/' . $address . '.svg';

    // Build template.
    $content .= "<span class=\"cqcw-block\">
                    <label class=\"cqcw-block__label\">{$label}:</label>
                    <a href=\"#{$label}_{$address}\" class=\"cqcw-block__button\">{$address}</a>
                    <em id=\"{$label}_{$address}\" class=\"cqcw-block__dialog\">
                        <strong class=\"cqcw-block__dialog-heading\">{$heading}</strong>
                        <img src=\"{$qr_svgCode_url}\" alt=\"{$address}\" />
                        <strong class=\"cqcw-block__dialog-content\">{$address}</strong>
                    </em>
                 </span>";

    return $content;
}
add_shortcode( 'cqcw_generator', 'crypto_qr_code_wp_shortcode_logic' );