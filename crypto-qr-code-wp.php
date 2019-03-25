<?php
/*
Plugin Name: Crypto QR Code WP
Plugin URI: https://www.dopethemes.com/downloads/crypto-qr-code-wp/
Description: Add cryptocurrencies QR code donate with tooltip.
Author: DopeThemes
Author URI: https://www.dopethemes.com/
Text Domain: crypto-qr-code-wp
Version: 1.0.1
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

	/*
	*  __construct
	*
	*  A dummy constructor to ensure Crypto QR Code WP is only initialized once.
	*
	*  @type	function
	*  @date	03/24/19
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/
	public function __construct() {
        // Do nothing here.
	}

	/*
	*  initialize
	*
	*  The real constructor to initialize Crypto QR Code WP.
	*
	*  @type	function
	*  @date	03/24/19
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/
	public function initialize() {
		// Parameters.
		$this->settings = array(
			'name'		 => esc_html__( 'Crypto QR Code WP', 'crypto-qr-code-wp' ),
			'version'	 => '1.0.0',
			'basename'	 => plugin_basename( __FILE__ ),
			'path'	     => plugin_dir_path( __FILE__ ),
			'dir'	     => plugin_dir_url( __FILE__ )
        );

        // Global Defines.
        define( 'CRYPTO_QR_CODE_WP_UPLOAD', trailingslashit( WP_CONTENT_DIR ) . 'uploads/' );
        define( 'CRYPTO_QR_CODE_WP_IMG_DIR', trailingslashit( WP_CONTENT_DIR ) . 'uploads/crypto-qr-codes/' );
        define( 'CRYPTO_QR_CODE_WP_URL', trailingslashit( WP_CONTENT_URL ) );

        // Resources.
        add_action( 'init',	array( $this, 'register_assets' ) );
        add_action( 'admin_init', array( $this, 'create_file_resources' ) );
        
        // Libraries.
        include( 'includes/shortcode.php' );
        include( 'includes/widgets.php' );

        // Vendor.
        include( 'includes/vendor/phpqrcode/qrlib.php' );
    }
    
	/*
	*  register_assets
	*
	*  @type	function
	*  @date	03/26/19
	*  @since	1.0.0
	*/
	function register_assets() {
        wp_register_script( 'crypto-qr-code-wp', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), $this->settings['version'] );
        wp_register_style( 'crypto-qr-code-wp', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), $this->settings['version'] );

        // Call assets.
        wp_enqueue_script( 'crypto-qr-code-wp' );
        wp_enqueue_style( 'crypto-qr-code-wp' );
    }

	/*
	*  create_file_resources
	*
	*  @type	function
	*  @date	03/26/19
	*  @since	1.0.1
	*/
	function create_file_resources() {
        // Check upload folder existence.
        if( ! is_dir( CRYPTO_QR_CODE_WP_IMG_DIR ) && is_writable( CRYPTO_QR_CODE_WP_UPLOAD ) && current_user_can( 'manage_options' ) ) {
            mkdir( CRYPTO_QR_CODE_WP_IMG_DIR , 0755 );
        }

        // HTAccess rules and secure folder.
        $htaccess_file = CRYPTO_QR_CODE_WP_IMG_DIR . '.htaccess';
        if( ( ! file_exists( $htaccess_file ) && is_writable( CRYPTO_QR_CODE_WP_UPLOAD ) ) && current_user_can( 'manage_options' ) ) {
            // Set htaccess rules.
            $rules = NULL;
            settype( $rules, 'string' );

            $rules  = "Options -Indexes\n";
            $rules .= "deny from all\n";
            $rules .= "<FilesMatch '\.(svg)$'>\n";
            $rules .= "Order Allow,Deny\n";
            $rules .= "Allow from all\n";
            $rules .= "</FilesMatch>";

            $fp = fopen( $htaccess_file, "w" );
            if ( ! $fp ) {
                return false;
            }

            // File lock security.
            flock( $fp, LOCK_EX );

            // Start writing data.
            fseek( $fp, 0 );
            $data_bytes = fwrite( $fp, $rules );
            if ( $data_bytes ) {
                ftruncate( $fp, ftell( $fp ) );
            }
            fflush( $fp );
            flock( $fp, LOCK_UN );
            fclose( $fp );
        }
    }
}

/*
*  crypto_qr_code_wp
*
*  The main function responsible for returning the one true crypto_qr_code_wp Instance to functions everywhere.
*  Use this function like you would a global variable, except without needing to declare the global.
*
*  Example: <?php $crypto_qr_code_wp = crypto_qr_code_wp(); ?>
*
*  @type	function
*  @date	03/24/19
*  @since	1.0.0
*
*  @param	N/A
*  @return	(object)
*/
function crypto_qr_code_wp() {
	global $crypto_qr_code_wp;
	if( ! isset($crypto_qr_code_wp) ) {
		$crypto_qr_code_wp = new crypto_qr_code_wp();
		$crypto_qr_code_wp->initialize();
    }
    
	return $crypto_qr_code_wp;
}

// initialize.
crypto_qr_code_wp();

endif; // class_exists check.