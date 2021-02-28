<?php
/*
Plugin Name: DePay Widget
Description: Displays DePay Widget for accepting crypto payments on your website.
Version:     0.0.2
Author:      Kryptohelden
Author URI:  https://kryptohelden.de
Text Domain: depay-widget
*/

defined( 'ABSPATH' ) or die;

define( 'DEPAY_WIDGET_VER', '0.0.2' );

if ( ! class_exists( 'Depay_Widget' ) ) {
	class Depay_Widget {
		public static function getInstance() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		private static $instance = null;

		private function __clone() { }

		private function __wakeup() { }

		private function __construct() {
			// Properties
			$this->cdn_url = 'https://unpkg.com/depay-widgets@1.2.0/dist/umd/index.js';
			$this->options = null;

			// Actions
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

			// Shortcode
			add_shortcode( 'DePayWidget', array( $this, 'output_shortcode' ) );
		}

		public function register_settings() {
			register_setting( 'depay_widget_optsgroup', 'depay_widget_options' );
		}

		public function add_admin_menu() {
			add_menu_page(
				__( 'DePay Widget', 'depay-widget' ),
				__( 'DePay Widget', 'depay-widget' ),
				'manage_options',
				'depay-widget',
				array( $this, 'render_options_page' ),
				'dashicons-money-alt'
			);
		}

		public function render_options_page() {
			require( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'options.php' );
		}

		public function register_assets() {
			wp_enqueue_style( 'depay-widget', plugins_url( 'css/style.css', __FILE__ ), array(), DEPAY_WIDGET_VER, 'all' );
			wp_enqueue_script( 'depay-widget', $this->cdn_url, array(), DEPAY_WIDGET_VER, true );
			wp_enqueue_script( 'depay-widget-handler', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery', 'depay-widget' ), DEPAY_WIDGET_VER, true );
		}

		public function output_shortcode( $atts, $content = '', $tag ) {
			$amount   = ( float ) $this->get_option( 'amount' );
			$token    = ( string ) $this->get_option( 'token' );
			$receiver = ( string ) $this->get_option( 'receiver' );

			if ( $amount <= 0 )    return __( 'Widget configuration error: Invalid amount value', 'depay-widget' );
			if ( $token == '' )    return __( 'Widget configuration error: Invalid token value', 'depay-widget' );
			if ( $receiver == '' ) return __( 'Widget configuration error: Invalid receiver value', 'depay-widget' );

			extract( shortcode_atts( array(
				'button_label' => __( 'Support Us with DePay', 'depay-widget' )
			), $atts, $tag ) );

			ob_start();
			require( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'widget.php' );
			return ob_get_clean();
		}

		private function get_option( $option_name, $default = '' ) {
			if ( is_null( $this->options ) ) $this->options = ( array ) get_option( 'depay_widget_options', array() );
			if ( isset( $this->options[$option_name] ) ) return $this->options[$option_name];
			return $default;
		}

		private function get_tokens_dropdown( $name, $default = '' ) {
			$tokens = json_decode( file_get_contents( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'tokens.json' ) );
			if ( ! $tokens ) return __( 'Invalid tokens file', 'depay-widget' );

			$html = '<select name="' . $name . '">';
			foreach( $tokens as $index => $token ) {
				$html .= '<option value="' . esc_attr( $token->address ) . '" ' . ( $default == $token->address ? 'selected' : '' ) . '>' . $token->name . '</option>';
			}
			$html .= '<select>';

			return $html;
		}
	}
}
Depay_Widget::getInstance();