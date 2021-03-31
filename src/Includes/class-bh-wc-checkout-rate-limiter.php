<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * frontend-facing side of the site and the admin area.
 *
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

use BrianHenryIE\Checkout_Rate_Limiter\Admin\Admin;
use BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page;
use BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Ajax;
use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\Psr\Log\LoggerAwareTrait;
use BrianHenryIE\Checkout_Rate_Limiter\Psr\Log\LoggerInterface;
use BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Advanced;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * frontend-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 * @subpackage BH_WC_Checkout_Rate_Limiter/includes
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */
class BH_WC_Checkout_Rate_Limiter {

	use LoggerAwareTrait;

	/**
	 * The plugin settings.
	 *
	 * @var Settings_Interface
	 */
	protected Settings_Interface $settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @param Settings_Interface $settings The plugin's settings.
	 * @param LoggerInterface    $logger PSR logger.
	 *
	 * @since    1.0.0
	 */
	public function __construct( Settings_Interface $settings, LoggerInterface $logger ) {

		$this->settings = $settings;
		$this->setLogger( $logger );

		$this->set_locale();
		$this->define_woocommerce_ajax_hooks();
		$this->define_woocommerce_settings_hooks();
		$this->define_admin_hooks();
		$this->define_plugins_page_hooks();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	protected function set_locale(): void {

		$plugin_i18n = new I18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Registers the hooks related to WooCommerce ajax actions. i.e. the main hook.
	 *
	 * @since    1.0.0
	 */
	protected function define_woocommerce_ajax_hooks(): void {

		$ajax = new Ajax( $this->settings, $this->logger );

		add_action( 'wc_ajax_checkout', array( $ajax, 'rate_limit_checkout' ), 0 );

	}

	/**
	 * Registers the hooks related to adding the settings page to WooCommerce.
	 *
	 * @since    1.0.0
	 */
	protected function define_woocommerce_settings_hooks(): void {

		$settings_advanced = new Settings_Advanced( $this->settings, $this->logger );

		add_filter( 'woocommerce_get_sections_advanced', array( $settings_advanced, 'add_section' ) );
		add_filter( 'woocommerce_get_settings_advanced', array( $settings_advanced, 'settings' ), 10, 2 );

		add_action( 'woocommerce_admin_field_attempts_per_interval', array( $settings_advanced, 'print_attempts_per_interval_settings_field' ) );

	}

	/**
	 * Register hooks for displaying and dismissing the "plugin is not yet configured" notice.
	 */
	protected function define_admin_hooks(): void {

		$admin = new Admin( $this->settings, $this->logger );

		add_action( 'plugins_loaded', array( $admin, 'init_notices' ) );
		add_action( 'admin_init', array( $admin, 'add_setup_notice' ) );
	}

	/**
	 * Register hook to add a settings link on plugins.php.
	 */
	protected function define_plugins_page_hooks(): void {

		$plugins_page = new Plugins_Page( $this->settings, $this->logger );

		add_filter( "plugin_action_links_{$this->settings->get_plugin_basename()}", array( $plugins_page, 'action_links' ) );
	}
}
