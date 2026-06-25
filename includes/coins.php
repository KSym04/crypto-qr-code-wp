<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Common cryptocurrency presets.
 *
 * Keyed by ticker (uppercase). Colors are brand-adjacent so each coin is
 * recognizable; icons are simple inline SVG badges generated locally (no
 * external requests, no third party logos).
 *
 * @since 1.2.0
 * @package Crypto QR Code WP
 *
 * @return array
 */
function cqcw_get_coins() {
	return array(
		'BTC'  => array( 'name' => 'Bitcoin',      'color' => '#f7931a' ),
		'ETH'  => array( 'name' => 'Ethereum',     'color' => '#627eea' ),
		'LTC'  => array( 'name' => 'Litecoin',     'color' => '#345d9d' ),
		'USDT' => array( 'name' => 'Tether',       'color' => '#26a17b' ),
		'DOGE' => array( 'name' => 'Dogecoin',     'color' => '#c2a633' ),
		'SOL'  => array( 'name' => 'Solana',       'color' => '#9945ff' ),
		'BCH'  => array( 'name' => 'Bitcoin Cash', 'color' => '#0ac18e' ),
		'XMR'  => array( 'name' => 'Monero',       'color' => '#ff6600' ),
	);
}

/**
 * Build an inline SVG coin badge for a given label, if it matches a known coin.
 *
 * Returns a safe, self-contained SVG string (or an empty string). All values
 * come from cqcw_get_coins(), so the markup is trusted; user input only selects
 * which preset to render.
 *
 * @since 1.2.0
 * @package Crypto QR Code WP
 *
 * @param string $label Coin label such as BTC.
 * @return string
 */
function cqcw_coin_icon( $label ) {
	$coins  = cqcw_get_coins();
	$ticker = strtoupper( trim( $label ) );

	if( ! isset( $coins[ $ticker ] ) ) {
		return '';
	}

	$color     = $coins[ $ticker ]['color'];
	$font_size = ( strlen( $ticker ) > 3 ) ? 7 : 9;

	return sprintf(
		'<svg class="cqcw-coin-icon" viewBox="0 0 32 32" width="20" height="20" role="img" aria-hidden="true" focusable="false">'
			. '<circle cx="16" cy="16" r="16" fill="%1$s"></circle>'
			. '<text x="16" y="20" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="%2$s" font-weight="700" fill="#ffffff">%3$s</text>'
		. '</svg>',
		esc_attr( $color ),
		esc_attr( $font_size ),
		esc_html( $ticker )
	);
}
