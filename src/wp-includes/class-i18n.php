<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\WP_Includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @hooked plugins_loaded
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {

		load_plugin_textdomain(
			'bh-wc-checkout-rate-limiter',
			false,
			dirname( plugin_basename( __FILE__ ), 3 ) . '/languages/'
		);

	}

}
