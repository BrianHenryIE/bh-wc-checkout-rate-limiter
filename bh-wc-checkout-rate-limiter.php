<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://BrianHenryIE.com
 * @since             1.0.0
 * @package           BH_WC_Checkout_Rate_Limiter
 *
 * @wordpress-plugin
 * Plugin Name:       Checkout Rate Limiter
 * Plugin URI:        http://github.com/brianhenryie/bh-wc-checkout-rate-limiter/
 * Description:       Rate limit the WooCommerce checkout to prevent card attacks.
 * Version:           1.3.1
 * Requires PHP:      7.4
 * Author:            BrianHenryIE
 * Author URI:        https://BrianHenry.IE
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bh-wc-checkout-rate-limiter
 * Domain Path:       /languages
 *
 * GitHub Plugin URI: https://github.com/BrianHenryIE/bh-wc-checkout-rate-limiter
 * Release Asset:     true
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Logger\Logger;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Includes\Activator;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Includes\Deactivator;
use Error;
use Exception;
use Throwable;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	throw new Exception( 'WPINC not defined' );
}

// If the GitHub repo was installed without running `composer install` to add the dependencies, the autoload will fail.
try {
	require_once plugin_dir_path( __FILE__ ) . 'autoload.php';
} catch ( Throwable $error ) {
	$display_download_from_releases_error_notice = function() {
		echo '<div class="notice notice-error"><p><b>Checkout Rate Limiter missing dependencies.</b> Please <a href="https://github.com/BrianHenryIE/bh-wc-checkout-rate-limiter/releases">install the distribution archive from the GitHub Releases page</a>. It appears you downloaded the GitHub repo and installed that as the plugin.</p></div>';
	};
	add_action( 'admin_notices', $display_download_from_releases_error_notice );
	return;
}

define( 'BH_WC_CHECKOUT_RATE_LIMITER_VERSION', '1.3.1' );
define( 'BH_WC_CHECKOUT_RATE_LIMITER_BASENAME', plugin_basename( __FILE__ ) );

register_activation_hook( __FILE__, array( Activator::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Deactivator::class, 'deactivate' ) );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function instantiate_bh_wc_checkout_rate_limiter(): void {

	$settings = new Settings();
	$logger   = Logger::instance( $settings );

	new BH_WC_Checkout_Rate_Limiter( $settings, $logger );

	if ( class_exists( BH_Checkout_Rate_Limiter_SLSWC_Client::class ) ) {
		\BH_Checkout_Rate_Limiter_SLSWC_Client::get_instance( 'https://bhwp.ie/', __FILE__ );
	}
}

instantiate_bh_wc_checkout_rate_limiter();

