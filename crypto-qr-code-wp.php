<?php
/*
Plugin Name: Crypto QR Code WP
Plugin URI: https://www.dopethemes.com/downloads/crypto-qr-code-wp/
Description: Display a cryptocurrency wallet address with a click to reveal QR code, generated entirely in the browser.
Author: DopeThemes
Author URI: https://www.dopethemes.com/
Text Domain: crypto-qr-code-wp
Version: 1.1.0
Requires at least: 4.7
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Domain Path: /lang
*/

/*
    Copyright DopeThemes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1335, USA
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if( ! class_exists( 'crypto_qr_code_wp' ) ) :

class crypto_qr_code_wp {

	/**
	 * Plugin settings (paths, urls, version).
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * A dummy constructor to ensure Crypto QR Code WP is only initialized once.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Do nothing here.
	}

	/**
	 * The real constructor to initialize Crypto QR Code WP.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		// Parameters.
		$this->settings = array(
			'name'     => esc_html__( 'Crypto QR Code WP', 'crypto-qr-code-wp' ),
			'version'  => '1.1.0',
			'basename' => plugin_basename( __FILE__ ),
			'path'     => plugin_dir_path( __FILE__ ),
			'dir'      => plugin_dir_url( __FILE__ ),
		);

		// Front-end assets (registered here, enqueued only when a code is rendered).
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		// Libraries (absolute paths so includes resolve in every SAPI, admin included).
		$path = $this->settings['path'];
		include_once $path . 'includes/helpers.php';
		include_once $path . 'includes/shortcode.php';
		include_once $path . 'includes/widgets.php';

		// Admin settings page.
		if( is_admin() ) {
			include_once $path . 'includes/admin.php';
			$admin = new Crypto_QR_Code_WP_Admin( $this->settings );
			$admin->init();
		}
	}

	/**
	 * Register front-end assets.
	 *
	 * The QR code is generated in the browser with the bundled qrcode.js library,
	 * so there are no external requests and nothing is written to the server.
	 *
	 * @since 1.0.0
	 */
	public function register_assets() {
		wp_register_script(
			'cqcw-qrcode',
			plugin_dir_url( __FILE__ ) . 'assets/js/qrcode.min.js',
			array(),
			'1.0.0',
			true
		);

		wp_register_script(
			'crypto-qr-code-wp',
			plugin_dir_url( __FILE__ ) . 'assets/js/script.js',
			array( 'jquery', 'cqcw-qrcode' ),
			$this->settings['version'],
			true
		);

		wp_register_style(
			'crypto-qr-code-wp',
			plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
			array(),
			$this->settings['version']
		);
	}
}

/**
 * The main function responsible for returning the one true crypto_qr_code_wp instance.
 *
 * @since 1.0.0
 *
 * @return crypto_qr_code_wp
 */
function crypto_qr_code_wp() {
	global $crypto_qr_code_wp;
	if( ! isset( $crypto_qr_code_wp ) ) {
		$crypto_qr_code_wp = new crypto_qr_code_wp();
		$crypto_qr_code_wp->initialize();
	}

	return $crypto_qr_code_wp;
}

// initialize.
crypto_qr_code_wp();

endif; // class_exists check.
