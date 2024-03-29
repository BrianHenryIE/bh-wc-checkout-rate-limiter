<?php
/**
 * Instance of settings for WooCommerce checkout rate limiting, plus its logging.
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\API;

use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Payments;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Logger\Logger_Settings_Trait;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Logger\WooCommerce_Logger_Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Logger\Logger;
use Psr\Log\LogLevel;

/**
 * The UI for the settings is in the Settings_Payments class.
 *
 * @see Settings_Payments
 *
 * Class Settings
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */
class Settings implements Settings_Interface, WooCommerce_Logger_Settings_Interface {
	use Logger_Settings_Trait;

	/**
	 * Is the setting enabled in the settings UI and ratelimits set?
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {

		$woocommerce_setting_enabled = 'yes' === get_option( 'bh_wc_checkout_rate_limiter_checkout_rate_limiting_enabled', 'yes' );
		return $woocommerce_setting_enabled && count( $this->get_checkout_rate_limits() ) > 0;
	}

	/**
	 * Array of rates, where the key is the interval and the value is the permitted number of events during that interval.
	 *
	 * @return array<int, int>
	 */
	public function get_checkout_rate_limits(): array {

		$defaults = array(
			1 => array(
				'interval' => 60,
				'attempts' => 2,
			),
			2 => array(
				'interval' => 120,
				'attempts' => 3,
			),
			3 => array(
				'interval' => 300,
				'attempts' => 5,
			),
		);

		$rates = array();

		foreach ( array( 1, 2, 3 ) as $index ) {
			$rate = get_option( 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_' . $index, $defaults[ $index ] );
			if ( isset( $rate['interval'] ) && 0 !== intval( $rate['interval'] )
				&& isset( $rate['attempts'] ) && 0 !== intval( $rate['attempts'] ) ) {
				$rates[ intval( $rate['interval'] ) ] = intval( $rate['attempts'] );
			}
		}

		return $rates;
	}

	/**
	 * Plugin log level. Recommended: Debug for testing, Info for normal use.
	 *
	 * NB: Not all possible log levels are used.
	 *
	 * @return string Minimum level to log events at.
	 */
	public function get_log_level(): string {
		return get_option( 'bh_wc_checkout_rate_limiter_log_level', LogLevel::INFO );
	}

	/**
	 * Plugin name for use by the logger in messages printed to WordPress admin UI.
	 *
	 * @see Logger
	 *
	 * @return string
	 */
	public function get_plugin_name(): string {
		return __( 'Checkout Rate Limiter', 'bh-wc-checkout-rate-limiter' );
	}

	/**
	 * The plugin slug is used by the logger in file and URL paths.
	 *
	 * @see Logger
	 *
	 * @return string
	 */
	public function get_plugin_slug(): string {
		return 'bh-wc-checkout-rate-limiter';
	}

	/**
	 * The plugin basename is used by the logger to add the plugins page action link.
	 *
	 * @see Logger
	 *
	 * @return string
	 */
	public function get_plugin_basename(): string {
		return defined( 'BH_WC_CHECKOUT_RATE_LIMITER_BASENAME' ) ? BH_WC_CHECKOUT_RATE_LIMITER_BASENAME : 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php';
	}
}
