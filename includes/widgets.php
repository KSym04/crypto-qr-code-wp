<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Crypto QR Code WP main widget class.
 *
 * @since  1.0.0
 */
class Crypto_QR_Code_WP_Widget extends WP_Widget {

	// Set up the widget name and description.
	public function __construct() {
		$widget_options = array(
			'classname'   => 'cqcw-widget',
			'description' => esc_html__( 'Show a cryptocurrency label, wallet address and click to reveal QR code.', 'crypto-qr-code-wp' ),
		);

		parent::__construct( 'crypto_qr_code_wp_widget', esc_html__( 'Crypto QR Code WP', 'crypto-qr-code-wp' ), $widget_options );
	}

	// Create the widget output.
	public function widget( $args, $instance ) {
		$title   = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$heading = ! empty( $instance['heading'] ) ? $instance['heading'] : '';
		$label   = ! empty( $instance['label'] ) ? $instance['label'] : '';
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided markup.

		if( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided wrappers.
		}

		// The shortcode escapes its own output.
		echo do_shortcode( sprintf(
			'[cqcw_generator heading="%1$s" label="%2$s" address="%3$s"]',
			esc_attr( $heading ),
			esc_attr( $label ),
			esc_attr( $address )
		) );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided markup.
	}

	// Create the admin area widget settings form.
	public function form( $instance ) {
		$title   = isset( $instance['title'] ) ? $instance['title'] : '';
		$heading = isset( $instance['heading'] ) ? $instance['heading'] : '';
		$label   = isset( $instance['label'] ) ? $instance['label'] : '';
		$address = isset( $instance['address'] ) ? $instance['address'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'crypto-qr-code-wp' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php esc_html_e( 'Tooltip Text Heading:', 'crypto-qr-code-wp' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" placeholder="<?php esc_attr_e( 'Donate', 'crypto-qr-code-wp' ); ?>" value="<?php echo esc_attr( $heading ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Crypto Label:', 'crypto-qr-code-wp' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>" placeholder="<?php esc_attr_e( 'BTC', 'crypto-qr-code-wp' ); ?>" value="<?php echo esc_attr( $label ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Wallet Address:', 'crypto-qr-code-wp' ); ?></label>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" placeholder="<?php esc_attr_e( 'Insert wallet public key address', 'crypto-qr-code-wp' ); ?>"><?php echo esc_textarea( $address ); ?></textarea>
		</p>
		<?php
	}

	// Apply settings to the widget instance.
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']   = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['heading'] = isset( $new_instance['heading'] ) ? sanitize_text_field( $new_instance['heading'] ) : '';
		$instance['label']   = isset( $new_instance['label'] ) ? sanitize_text_field( $new_instance['label'] ) : '';
		$instance['address'] = isset( $new_instance['address'] ) ? sanitize_text_field( $new_instance['address'] ) : '';
		return $instance;
	}
}

/**
 * Register Crypto QR Code WP widget.
 *
 * @since 1.0.2
 * @package Crypto QR Code WP
 */
function crypto_qr_code_wp_register_widget() {
	register_widget( 'Crypto_QR_Code_WP_Widget' );
}
add_action( 'widgets_init', 'crypto_qr_code_wp_register_widget' );
