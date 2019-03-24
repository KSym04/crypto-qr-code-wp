<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

function crypto_qr_code_wp_shortcode_logic( $atts ) {
	// Parse shortcode attributes.
	$a = shortcode_atts( array(
		'label' => '',
		'address' => ''
    ), $atts );
    
    // Set variables.
    $content = NULL;
    settype( $content, 'string' );

    // Build template.
    $content .= '<div>
                    <a href="#'. $a['address'] .'" title="'. $a['label'] .' '. $a['address'] .'">
                        <label>'. $a['label'] .'</label>: '. $a['address'] .'
                    </a>
                 </div>';

    return $content;
}
add_shortcode( 'cqcw_generator', 'crypto_qr_code_wp_shortcode_logic' );