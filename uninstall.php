<?php
/**
 * Crypto QR Code WP uninstall routine.
 *
 * Runs only when the plugin is deleted from the Plugins screen, never on
 * deactivation. Removes every option the plugin creates:
 *
 * - cqcw_settings: the wallet library, QR size, and appearance colors.
 * - widget_crypto_qr_code_wp_widget: the saved Crypto QR Code WP widget instances.
 *
 * On multisite each site keeps its own copy of these options, so every site
 * in the network is cleaned.
 *
 * @since 1.3.2
 * @package Crypto QR Code WP
 */

// If plugin is not being uninstalled, exit (do nothing).
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! function_exists( 'cqcw_uninstall_site' ) ) {
	/**
	 * Delete this plugin's options on the current site.
	 *
	 * @since 1.3.2
	 *
	 * @return void
	 */
	function cqcw_uninstall_site() {
		delete_option( 'cqcw_settings' );
		delete_option( 'widget_crypto_qr_code_wp_widget' );
	}
}

if ( is_multisite() ) {
	$cqcw_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $cqcw_site_ids as $cqcw_site_id ) {
		switch_to_blog( $cqcw_site_id );
		cqcw_uninstall_site();
		restore_current_blog();
	}
} else {
	cqcw_uninstall_site();
}
