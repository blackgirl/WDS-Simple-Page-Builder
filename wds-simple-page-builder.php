<?php
/**
 * Plugin Name: WebDevStudios Simple Page Builder
 * Plugin URI: http://webdevstudios.com
 * Description: Uses existing template parts in the currently-active theme to build a customized page with rearrangeable elements.
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_Simple_Page_Builder' ) ) {

	class WDS_Simple_Page_Builder {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );

			// Include any required files
			require_once( $this->directory_path . '/inc/options.php' );
			require_once( $this->directory_path . '/inc/functions.php' );

			// Load Textdomain
			load_plugin_textdomain( 'wds-simple-page-builder', false, dirname( $this->basename ) . '/languages' );

			// Activation/Deactivation Hooks
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			// Make sure we have our requirements, and disable the plugin if we do not have them.
			add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		}

		/**
		 * Register CPTs & taxonomies.
		 */
		public function do_hooks() {
			if ( $this->meets_requirements() ) {
			}
		}

		/**
		 * Check that all plugin requirements are met
		 *
		 * @return boolean
		 */
		public static function meets_requirements() {
			// Make sure we have CMB so we can use it
			if ( ! defined( 'CMB2_LOADED' ) ) {
				return false;
			}

			// We have met all requirements
			return true;
		}

		/**
		 * Check if the plugin meets requirements and
		 * disable it if they are not present.
		 */
		public function maybe_disable_plugin() {
			if ( ! $this->meets_requirements() ) {
				// Display our error
				echo '<div id="message" class="error">';
				echo '<p>' . sprintf( __( 'WDS Simple Page Builder requires CMB2 but could not find it. The plugin has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'wds-simple-page-builder' ), admin_url( 'plugins.php' ) ) . '</p>';
				echo '</div>';

				// Deactivate our plugin
				deactivate_plugins( $this->basename );
			}
		}

	}

	$_GLOBALS['WDS_Simple_Page_Builder'] = new WDS_Simple_Page_Builder;
	$_GLOBALS['WDS_Simple_Page_Builder']->do_hooks();
}

/**
 * Public wrapper function
 */
function wds_page_builder() {
	return new WDS_Simple_Page_Builder;
}