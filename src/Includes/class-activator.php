<?php
/**
 * Fired during plugin activation
 *
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class Activator {

	const ACTIVATED_TIME_OPTION_KEY = 'bh_wc_checkout_rate_limiter_activated_time';

	/**
	 * Record each time the plugin has been activated.
	 *
	 * I.e. was the plugin active when the denial of service attack happened?
	 *
	 * @since    1.0.0
	 */
	public static function activate(): void {

		$option_key = self::ACTIVATED_TIME_OPTION_KEY;

		$activations = get_option( $option_key, array() );

		$activations[] = time();

		update_option( $option_key, $activations );

	}

}
