<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class Deactivator {

	const DEACTIVATED_TIME_OPTION_KEY = 'bh_wc_checkout_rate_limiter_deactivated_time';

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate(): void {

		$option_key = self::DEACTIVATED_TIME_OPTION_KEY;

		$deactivations = get_option( $option_key, array() );

		$deactivations[] = time();

		update_option( $option_key, $deactivations );

	}

}
