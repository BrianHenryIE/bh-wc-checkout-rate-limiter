<?php
/**
 * @package           BH_WC_Checkout_Rate_Limiter
 */

$GLOBALS['project_root_dir']   = $project_root_dir  = dirname( __FILE__, 2 );
$GLOBALS['plugin_root_dir']    = $plugin_root_dir   = $project_root_dir . '/src';
$GLOBALS['plugin_name']        = $plugin_name       = basename( $project_root_dir );
$GLOBALS['plugin_name_php']    = $plugin_name_php   = $plugin_name . '.php';
$GLOBALS['plugin_path_php']    = $plugin_root_dir . '/' . $plugin_name_php;
$GLOBALS['plugin_basename']    = $plugin_name . '/' . $plugin_name_php;
$GLOBALS['wordpress_root_dir'] = $project_root_dir . '/wordpress';



// Delete the logs before running tests.
// delete *.log.

$logs_dir = $project_root_dir . '/wp-content/uploads/logs/';
array_map( 'unlink', glob( "$logs_dir*.log" ) );

$wc_logs_dir = $project_root_dir . '/wp-content/uploads/wc-logs/';
array_map( 'unlink', glob( "$wc_logs_dir*.log" ) );
