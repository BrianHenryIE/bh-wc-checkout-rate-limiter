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

		// Don't show it on the settings page itself.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['section'] ) && 'checkout-rate-limiting' === $_GET['section'] ) {
			return;
		}

		$last_activated_times = get_option( Activator::ACTIVATED_TIME_OPTION_KEY, array() );

		$last_activated = intval( array_pop( $last_activated_times ) );

		// If last activation was longer than a week ago, stop annoying users with the admin notice.
		if ( $last_activated < time() - WEEK_IN_SECONDS ) {
			return;
		}

		$last_visited_time = get_option( 'bh_wc_checkout_rate_limiter_visited_settings_time', 0 );

		// If the settings page has been viewed since the plug was activated, do not show it.
		if ( $last_visited_time > $last_activated ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting' );

		$id      = $this->settings->get_plugin_slug() . '-activation-configuration';
		$title   = '';
		$message = '<strong>' . $this->settings->get_plugin_name() . '</strong> ' . __( 'options can be configured under', 'bh-wc-checkout-rate-limiter' ) . " <a href=\"{$settings_url}\">WooCommerce / Settings / Payments / Rate Limiting</a>.";

		$options = array(
			'capability' => 'manage_options',
		);

		$this->notices->add( $id, $title, $message, $options );

	}

}
