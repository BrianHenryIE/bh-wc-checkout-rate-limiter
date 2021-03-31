<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 *
 * @package    BH_WC_Checkout_Rate_Limiter
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'bh_wc_checkout_rate_limiter_activated_time' );
delete_option( 'bh_wc_checkout_rate_limiter_deactivated_time' );

delete_option( 'bh_wc_checkout_rate_limiter_checkout_rate_limiting_enabled' );

delete_option( 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1' );
delete_option( 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2' );
delete_option( 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3' );

delete_option( 'bh_wc_checkout_rate_limiter_log_level' );
