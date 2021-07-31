<?php
/**
 * The logger was using Klogger and causing a problem when unable to write to that directory.
 *
 * @see https://github.com/BrianHenryIE/bh-wc-checkout-rate-limiter/issues/8
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Logger\Logger;

class Logging_Integration_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * First delete all existing logs, then log something and verify the log output is the correct
	 * destination.
	 *
	 * The log file should be in the wc-logs directory.
	 */
	public function test_logger_output() {

		$logger = Logger::instance();

		$log_message = 'where am I? ' . time();

		$logger->error( $log_message );

		do_action( 'plugins_loaded' );

		global $project_root_dir;
		$date = date( 'Y-m-d' );

		// KLogger
		$this->assertFileNotExists( $project_root_dir . "/wp-content/uploads/logs/bh-wc-checkout-rate-limiter-{$date}.log" );

		// WC_Logger

		global $project_root_dir;
		$logs_dir  = $project_root_dir . '/wp-content/uploads/wc-logs/';
		$log_files = glob( "$logs_dir/bh-wc-checkout-rate-limiter-{$date}-*.log" );

		$this->assertNotEmpty( $log_files );

		$log_file_contents = file_get_contents( $log_files[0] );

		$this->assertStringContainsString( $log_message, $log_file_contents );

	}
}
