<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/admin
 */

namespace BH_WC_Checkout_Rate_Limiter\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/admin
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @hooked admin_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'bh-wc-checkout-rate-limiter', plugin_dir_url( __FILE__ ) . 'css/bh-wc-checkout-rate-limiter-admin.css', array(), BH_WC_CHECKOUT_RATE_LIMITER_VERSION, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @hooked admin_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'bh-wc-checkout-rate-limiter', plugin_dir_url( __FILE__ ) . 'js/bh-wc-checkout-rate-limiter-admin.js', array( 'jquery' ), BH_WC_CHECKOUT_RATE_LIMITER_VERSION, false );

	}

}
