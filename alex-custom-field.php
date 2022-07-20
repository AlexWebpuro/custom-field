<?php

/**
 * @wordpress-plugin
 * Plugin Name:     Alex Custom field
 * Description:     Solución prueba técnica.
 * Version:         0.0.1
 * Author:          Alex Monroy
 * Author URI:      https://alexmonroy.com/
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     alex-custom-field
 * Domain Path:     /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not permitted.' );
}

if ( ! class_exists( 'ALX_Custom_field' ) ) {

	/*
	 * main plugin_name class
	 *
	 * @class plugin_name
	 * @since 0.0.1
	 */
	class ALX_Custom_field {

		/*
		 * plugin_name plugin version
		 *
		 * @var string
		 */
		public $version = '1';

		/**
		 * The single instance of the class.
		 *
		 * @var plugin_name
		 * @since 0.0.1
		 */
		protected static $instance = null;

		/**
		 * Main plugin_name instance.
		 *
		 * @since 0.0.1
		 * @static
		 * @return plugin_name - main instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * plugin_name class constructor.
		 */
		public function __construct() {
			$this->load_plugin_textdomain();
			$this->define_constants();
			$this->includes();
			$this->define_actions();
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'alex-custom-field', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Include required core files
		 */
		public function includes() {
            // Example
//			require_once __DIR__ . '/includes/loader.php';

			// Load custom functions and hooks
			require_once __DIR__ . '/includes/includes.php';
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}


		/**
		 * Define plugin_name constants
		 */
		private function define_constants() {
			define( 'ALEX_CF_PLUGIN_FILE', __FILE__ );
			define( 'ALEX_CF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'ALEX_CF_VERSION', $this->version );
			define( 'ALEX_CF_PATH', $this->plugin_path() );
		}

		/**
		 * Define plugin_name actions
		 */
		public function define_actions() {
			add_action( 'add_meta_boxes', array( $this, 'custom_field') );
			add_action( 'save_post', array( $this, 'custom_field_save_meta_box' ) );
			add_action( 'init', array( $this, 'shortcodes') );
		}

		/**
		 * Define plugin_name menus
		 */
		public function define_menus() {
            //
		}

		/**
		 * Register postmeta custom field in post
		 * @return void
		 */
		public function custom_field()
		{
			add_meta_box('alx-cf', __('Citación', 'alex-custom-field'), array( $this, 'custom_field_display_callback'), 'post' );
		}

		/**
		 * Template to display admin form meta box
		 * @return void
		 */
		public function custom_field_display_callback() {
			include_once ALEX_CF_PATH . '/includes/templates/form.php';
		}

		/**
		 * Save meta data when save the post
		 * @param int $post_id
		 * @return void
		 */
		public function custom_field_save_meta_box( int $post_id ) {
			if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
			if ( $parent_id = wp_is_post_revision( $post_id ) ) {
				$post_id = $parent_id;
			}

			$fields = array( 'custom_field_citacion' );
			foreach ( $fields as $field ) {
				if( array_key_exists( $field, $_POST ) ) {
					update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field]) );
				}
			}
		}

		/**
		 * Load shortcodes in init hook
		 * @return void
		 */
		public function shortcodes()
		{
			add_shortcode( 'mc-citacion', array( $this, 'mc_citacion_shortcode') );
		}

		/**
		 * Shortcode handle the post meta and show
		 * @param $atts
		 * @return void
		 */
		public function mc_citacion_shortcode( $atts = null )
		{
			$a = shortcode_atts( array(
				'post_id' => get_the_ID(),
			), $atts );

			$post_id = $a['post_id'] ? $a['post_id'] : get_the_ID();

			$citacion = get_post_meta( $post_id, 'custom_field_citacion', true );

			return esc_attr( $citacion );
		}
	}

	$alex_plugin = new ALX_Custom_field();
}
