<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           BH_WC_Checkout_Rate_Limiter
 *
 * @wordpress-plugin
 * Plugin Name:       BH WC Checkout Rate Limiter
 * Plugin URI:        http://github.com/username/bh-wc-checkout-rate-limiter/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            BrianHenryIE
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bh-wc-checkout-rate-limiter
 * Domain Path:       /languages
 */

namespace BH_WC_Checkout_Rate_Limiter;

use BH_WC_Checkout_Rate_Limiter\Includes\Activator;
use BH_WC_Checkout_Rate_Limiter\Includes\Deactivator;
use BH_WC_Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BH_WC_CHECKOUT_RATE_LIMITER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_bh_wc_checkout_rate_limiter(): void {

	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_bh_wc_checkout_rate_limiter(): void {

	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'BH_WC_Checkout_Rate_Limiter\activate_bh_wc_checkout_rate_limiter' );
register_deactivation_hook( __FILE__, 'BH_WC_Checkout_Rate_Limiter\deactivate_bh_wc_checkout_rate_limiter' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function instantiate_bh_wc_checkout_rate_limiter(): BH_WC_Checkout_Rate_Limiter {

	$plugin = new BH_WC_Checkout_Rate_Limiter();

	return $plugin;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and frontend-facing site hooks.
 */
$GLOBALS['bh_wc_checkout_rate_limiter'] = $bh_wc_checkout_rate_limiter = instantiate_bh_wc_checkout_rate_limiter();
