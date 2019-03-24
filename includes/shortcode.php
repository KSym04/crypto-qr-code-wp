<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

function crypto_qr_code_wp_shortcode_logic( $atts ) {
	// Parse shortcode attributes.
	$a = shortcode_atts( array(
		'label' => '',
		'address' => ''
    ), $atts );

    if( empty( $a['label'] ) && empty( $a['address'] ) ) {
        return;
    }
    
    // Set variables.
    $content = NULL;
    settype( $content, 'string' );

    // Build template.
    $content .= '<div class="cqcw-block">
                    <a href="#'. $a['label'] .'_'. $a['address'] .'" title="'. $a['label'] .' - '. $a['address'] .'" class="cqcw-block--button">
                        <label class="cqcw-block--label">'. $a['label'] .'</label>: <em class="cqcw-block--address">'. $a['address'] .'</em>
                    </a>
                 </div>';

    return $content;
}
add_shortcode( 'cqcw_generator', 'crypto_qr_code_wp_shortcode_logic' );