<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Crypto QR Code WP admin settings page.
 *
 * Provides a clean wallet library where each saved wallet generates a ready made
 * shortcode, plus a display option for the QR size. Built on the WordPress
 * Settings API so capability and nonce handling are managed by core.
 *
 * @since 1.1.0
 * @package Crypto QR Code WP
 */
class Crypto_QR_Code_WP_Admin {

	const MENU_SLUG    = 'crypto-qr-code-wp';
	const PAGE_HOOK    = 'toplevel_page_crypto-qr-code-wp';
	const OPTION_KEY   = 'cqcw_settings';
	const OPTION_GROUP = 'cqcw_settings_group';
	const CAP          = 'manage_options';

	/**
	 * Plugin settings passed from the bootstrap (path, dir, version).
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param array $settings Plugin settings.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Hook the admin page.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'plugin_action_links_' . $this->settings['basename'], array( $this, 'action_links' ) );
	}

	/**
	 * Register the top level menu.
	 */
	public function register_menu() {
		add_menu_page(
			esc_html__( 'Crypto QR Code WP', 'crypto-qr-code-wp' ),
			esc_html__( 'Crypto QR Code', 'crypto-qr-code-wp' ),
			self::CAP,
			self::MENU_SLUG,
			array( $this, 'render_page' ),
			'dashicons-grid-view',
			80
		);
	}

	/**
	 * Add a Settings link on the plugins screen.
	 *
	 * @param array $links Existing links.
	 * @return array
	 */
	public function action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=' . self::MENU_SLUG ) ),
			esc_html__( 'Settings', 'crypto-qr-code-wp' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Register the single array option and its sanitizer.
	 */
	public function register_settings() {
		register_setting( self::OPTION_GROUP, self::OPTION_KEY, array(
			'sanitize_callback' => array( $this, 'sanitize' ),
			'default'           => $this->defaults(),
		) );
	}

	/**
	 * Default settings.
	 *
	 * @return array
	 */
	public function defaults() {
		return array(
			'wallets' => array(),
			'qr_size' => '180',
		);
	}

	/**
	 * Saved settings merged with defaults.
	 *
	 * @return array
	 */
	public function get_all() {
		$saved = get_option( self::OPTION_KEY, array() );
		return wp_parse_args( is_array( $saved ) ? $saved : array(), $this->defaults() );
	}

	/**
	 * Sanitize the settings array on save.
	 *
	 * @param array $input Raw posted settings.
	 * @return array
	 */
	public function sanitize( $input ) {
		$out = $this->defaults();

		if( ! is_array( $input ) ) {
			return $out;
		}

		// Wallet library.
		$wallets = array();
		if( isset( $input['wallets'] ) && is_array( $input['wallets'] ) ) {
			foreach( $input['wallets'] as $row ) {
				if( ! is_array( $row ) ) {
					continue;
				}
				$label   = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
				$address = isset( $row['address'] ) ? sanitize_text_field( $row['address'] ) : '';
				$heading = isset( $row['heading'] ) ? sanitize_text_field( $row['heading'] ) : '';

				// Skip rows with neither a label nor an address.
				if( '' === $label && '' === $address ) {
					continue;
				}

				$wallets[] = array(
					'label'   => $label,
					'address' => $address,
					'heading' => ( '' === $heading ) ? __( 'Donate', 'crypto-qr-code-wp' ) : $heading,
				);
			}
		}
		$out['wallets'] = $wallets;

		// QR size, clamped to a sane range.
		$size = isset( $input['qr_size'] ) ? absint( $input['qr_size'] ) : 180;
		$out['qr_size'] = ( $size >= 80 && $size <= 400 ) ? (string) $size : '180';

		return $out;
	}

	/**
	 * Enqueue admin assets only on this plugin's screen.
	 *
	 * @param string $hook Current admin page hook suffix.
	 */
	public function enqueue( $hook ) {
		if( self::PAGE_HOOK !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'crypto-qr-code-wp-admin',
			$this->settings['dir'] . 'assets/css/admin.css',
			array( 'dashicons' ),
			$this->settings['version']
		);

		wp_enqueue_script(
			'crypto-qr-code-wp-admin',
			$this->settings['dir'] . 'assets/js/admin.js',
			array( 'jquery' ),
			$this->settings['version'],
			true
		);

		wp_localize_script( 'crypto-qr-code-wp-admin', 'cqcwAdmin', array(
			'copied'       => esc_html__( 'Copied!', 'crypto-qr-code-wp' ),
			'copy'         => esc_html__( 'Copy shortcode', 'crypto-qr-code-wp' ),
			'confirmRemove' => esc_html__( 'Remove this wallet?', 'crypto-qr-code-wp' ),
			'defaultHeading' => esc_html__( 'Donate', 'crypto-qr-code-wp' ),
		) );
	}

	/**
	 * Render the settings page.
	 */
	public function render_page() {
		if( ! current_user_can( self::CAP ) ) {
			return;
		}

		$current  = $this->get_all();
		$wallets  = ! empty( $current['wallets'] ) ? $current['wallets'] : array();
		$qr_size  = $current['qr_size'];

		include $this->settings['path'] . 'includes/views/settings-page.php';
	}

	/**
	 * Build the shortcode string for a wallet (used by the template and JS template row).
	 *
	 * @param array $wallet Wallet row.
	 * @return string
	 */
	public static function build_shortcode( $wallet ) {
		$heading = isset( $wallet['heading'] ) && '' !== $wallet['heading'] ? $wallet['heading'] : 'Donate';
		$label   = isset( $wallet['label'] ) ? $wallet['label'] : '';
		$address = isset( $wallet['address'] ) ? $wallet['address'] : '';

		return sprintf(
			'[cqcw_generator heading="%1$s" label="%2$s" address="%3$s"]',
			$heading,
			$label,
			$address
		);
	}
}
