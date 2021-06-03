<?php
/**
 * Interface for OO instance of settings saved in WooCommerce.
 *
 * @see \BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Payments
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\API;

use BrianHenryIE\WC_Venmo_Gateway\Admin\Admin;

interface Settings_Interface {

	/**
	 * Should the rate limiter be used?
	 *
	 * @return bool
	 */
	public function is_enabled(): bool;

	/**
	 * Array of rates, where the key is the interval and the value is the permitted number of events during that interval.
	 *
	 * @return array<int, int> <interval, num_allowed_events>
	 */
	public function get_checkout_rate_limits(): array;

	/**
	 * The plugin basename is used to add the settings link on plugins.php.
	 *
	 * @return string Filename of plugin base file relative to WP_PLUGINS dir.
	 */
	public function get_plugin_basename(): string;

	/**
	 * Plugin slug is used when making admin notices.
	 *
	 * @see Admin
	 *
	 * @return string
	 */
	public function get_plugin_slug(): string;


	/**
	 * Plugin name is used when making admin notices.
	 *
	 * @see Admin
	 *
	 * @return string
	 */
	public function get_plugin_name(): string;
}
