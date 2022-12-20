<?php
/**
 * The plugin page output of the plugin.
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Admin;

use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

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
	 * @param array<int|string, string>   $action_links The existing plugin links (usually "Deactivate").
	 * @param ?string                     $_plugin_basename The plugin's directory/filename.php.
	 * @param ?array<string, string|bool> $_plugin_data Associative array including PluginURI, slug, Author, Version. See `get_plugin_data()`.
	 * @param ?string                     $_context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                                                'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @return array<int|string, string> The links to display below the plugin name on plugins.php.
	 */
	public function action_links( array $action_links, ?string $_plugin_basename, ?array $_plugin_data, ?string $_context ): array {

		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting' );

		array_unshift( $action_links, '<a href="' . $settings_url . '">Settings</a>' );

		return $action_links;
	}

}
