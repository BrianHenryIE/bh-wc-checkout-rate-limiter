<?php
/**
 * Display an admin notice inviting the user to configure the plugin. Stops displaying after a week.
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Admin;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\Includes\Activator;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use BrianHenryIE\Checkout_Rate_Limiter\WPTRT\AdminNotices\Notices;

/**
 * Checks that:
 * * last activated time is in the past week
 * * current_user_can('manage_options')
 * * plugin is not configured
 *
 * @see https://github.com/wptrt/admin-notices
 */
class Admin {

	use LoggerAwareTrait;

	/**
	 * The plugin's settings.
	 *
	 * @var Settings_Interface
	 */
	protected Settings_Interface $settings;

	/**
	 * WPTRT Notices instance.
	 *
	 * @see https://github.com/wptrt/admin-notices
	 *
	 * @var Notices
	 */
	protected Notices $notices;

	/**
	 * Instantiate.
	 *
	 * @param Settings_Interface $settings The plugin settings.
	 * @param LoggerInterface    $logger PSR logger.
	 */
	public function __construct( Settings_Interface $settings, LoggerInterface $logger ) {
		$this->settings = $settings;
		$this->setLogger( $logger );
	}

	/**
	 * Initialize WPTRT\AdminNotices for presenting notices and handling dismissals.
	 *
	 * Load only on admin screens and AJAX requests.
	 *
	 * @hooked plugins_loaded
	 */
	public function init_notices(): void {

		if ( ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		$this->notices = new Notices();
		$this->notices->boot();

	}

	/**
	 * Checks if we are recently activated and unconfigured, then displays an admin notice to users.
	 *
	 * @hooked admin_init
	 */
	public function add_setup_notice(): void {

		if ( ! is_admin() ) {
			return;
		}

		if ( $this->settings->is_enabled() ) {
			// Already configured.
			return;
		}

		// Don't show it on the settings page itself.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['section'] ) && 'checkout-rate-limiting' === $_GET['section'] ) {
			return;
		}

		$last_activated_times = get_option( Activator::ACTIVATED_TIME_OPTION_KEY, array() );

		$last_activated = intval( array_pop( $last_activated_times ) );

		// If last activation was longer than a week ago, return.
		if ( $last_activated < time() - WEEK_IN_SECONDS ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting' );

		$id      = $this->settings->get_plugin_slug() . '-activation-configuration';
		$title   = '';
		$message = "{$this->settings->get_plugin_name()} needs to be configured. Please <a href=\"{$settings_url}\">visit the settings page</a> to configure and enable.";

		$options = array(
			'capability' => 'manage_options',
		);

		$this->notices->add( $id, $title, $message, $options );

	}

}
