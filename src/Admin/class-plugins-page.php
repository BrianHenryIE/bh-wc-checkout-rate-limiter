<?php
/**
 * The plugin page output of the plugin.
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Admin;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\Psr\Log\LoggerAwareTrait;
use BrianHenryIE\Checkout_Rate_Limiter\Psr\Log\LoggerInterface;

/**
 * This class adds a `Settings` link on the plugins.php page.
 */
class Plugins_Page {

	use LoggerAwareTrait;

	/**
	 * The plugin's settings.
	 *
	 * @var Settings_Interface
	 */
	protected Settings_Interface $settings;

	/**
	 * Instantiate Plugins_Page.
	 *
	 * TODO The logger is not being used.
	 *
	 * @param Settings_Interface $settings The plugin settings.
	 * @param LoggerInterface    $logger PSR logger.
	 */
	public function __construct( Settings_Interface $settings, LoggerInterface $logger ) {
		$this->settings = $settings;
		$this->setLogger( $logger );
	}

	/**
	 * Add link to settings page in plugins.php list.
	 *
	 * @hooked plugin_action_links_{basename}
	 *
	 * @param array<int|string, string> $links_array The existing plugin links (usually "Deactivate").
	 *
	 * @return array<int|string, string> The links to display below the plugin name on plugins.php.
	 */
	public function action_links( $links_array ): array {

		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting' );

		array_unshift( $links_array, '<a href="' . $settings_url . '">Settings</a>' );

		return $links_array;
	}

}
