<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/frontend
 */

namespace BH_WC_Checkout_Rate_Limiter\Frontend;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend-facing stylesheet and JavaScript.
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/frontend
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class Frontend {

	/**
	 * Register the stylesheets for the frontend-facing side of the site.
	 *
	 * @hooked wp_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {

		wp_enqueue_style( 'bh-wc-checkout-rate-limiter', plugin_dir_url( __FILE__ ) . 'css/bh-wc-checkout-rate-limiter-frontend.css', array(), BH_WC_CHECKOUT_RATE_LIMITER_VERSION, 'all' );

	}

	/**
	 * Register the JavaScript for the frontend-facing side of the site.
	 *
	 * @hooked wp_enqueue_scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {

		wp_enqueue_script( 'bh-wc-checkout-rate-limiter', plugin_dir_url( __FILE__ ) . 'js/bh-wc-checkout-rate-limiter-frontend.js', array( 'jquery' ), BH_WC_CHECKOUT_RATE_LIMITER_VERSION, false );

	}

}
