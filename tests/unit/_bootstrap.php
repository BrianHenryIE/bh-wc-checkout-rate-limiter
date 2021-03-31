<?php
/**
 * PHPUnit bootstrap file for WP_Mock.
 *
 * @package           BH_WC_Checkout_Rate_Limiter
 */

global $plugin_root_dir;
require_once $plugin_root_dir . '/autoload.php';

WP_Mock::bootstrap();
