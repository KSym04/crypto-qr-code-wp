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
            'classname' => 'cqcw-widget', 
            'description' => esc_html__( 'Create element of cryptocurreny label, wallet address and QR code.', 'crypto-qr-code-wp' ) 
        );
    
        parent::__construct( 'crypto_qr_code_wp_widget', 'Crpyto QR Code WP', $widget_options );
    }
  
    // Create the widget output.
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo do_shortcode( '[cqcw_generator heading="' . $instance['heading'] . '" label="' . $instance['label'] . '" address="' . $instance['address'] . '"]' );
        echo $args['after_widget'];
    }
  
    // Create the admin area widget settings form.
    public function form( $instance ) {
        global $wp_customize;

        // widget title.
        echo '<p>';
            echo '<label for="' . $this->get_field_id( 'title' ) . '">' . esc_html__( 'Title:', 'crypto-qr-code-wp' ) . '</label><br />';
            echo '<input type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" class="widefat">';
        echo '</p>';

        // heading.
        echo '<p>';
            echo '<label for="' . $this->get_field_id( 'heading' ) . '">' . esc_html__( 'Tooltip Text Heading:', 'crypto-qr-code-wp' ) . '</label><br />';
            echo '<input type="text" id="' . $this->get_field_id( 'heading' ) . '" name="' . $this->get_field_name( 'heading' ) . '" required placeholder="' . esc_html__( 'Donate', 'crypto-qr-code-wp' ) . '" value="' . $instance['heading'] . '" class="widefat">';
        echo '</p>';

        // label.
        echo '<p>';
            echo '<label for="' . $this->get_field_id( 'label' ) . '">' . esc_html__( 'Crypto Label:', 'crypto-qr-code-wp' ) . '</label><br />';
            echo '<input type="text" id="' . $this->get_field_id( 'label' ) . '" name="' . $this->get_field_name( 'label' ) . '" required placeholder="' . esc_html__( 'BTC', 'crypto-qr-code-wp' ) . '" value="' . $instance['label'] . '" class="widefat">';
        echo '</p>';

        // address.
        echo '<p>';
            echo '<label for="' . $this->get_field_id( 'address' ) . '">' . esc_html__( 'Wallet Address:', 'crypto-qr-code-wp' ) . '</label><br />';
            echo '<textarea id="' . $this->get_field_id( 'address' ) . '" name="' . $this->get_field_name( 'address' ) . '" rows="5" required placeholder="' . esc_html__( 'Insert wallet public key address', 'crypto-qr-code-wp' ) . '" class="widefat">' . $instance['address'] . '</textarea>';
        echo '</p>';
    }
  
    // Apply settings to the widget instance.
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']   = $new_instance['title'];
        $instance['heading'] = $new_instance['heading'];
        $instance['label']   = $new_instance['label'];
        $instance['address'] = $new_instance['address'];
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