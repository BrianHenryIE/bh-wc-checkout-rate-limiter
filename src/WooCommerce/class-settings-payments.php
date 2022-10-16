<?php
/**
 * Settings page to display in WooCommerce.
 *
 * @see /wp-admin/admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use WC_Admin_Settings;

/**
 * * Adds the settings section to WooCommerce, under Payments.
 * * Provides the list of settings.
 * * Contains a custom setting type for printing two integer input boxes alongside each other.
 *
 * Class Settings_Payments
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */
class Settings_Payments {

	use LoggerAwareTrait;

	/**
	 * The plugin's settings.
	 *
	 * @var Settings_Interface
	 */
	protected Settings_Interface $settings;

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
	 * Record the last visited time of the settings page so the admin notice can be hidden.
	 *
	 * @hooked current_screen
	 */
	public function record_page_visit_time(): void {

		if ( ! function_exists( 'wc_get_current_admin_url' ) ) {
			return;
		}

		$wc_admin_url = wc_get_current_admin_url();

		if ( empty( $wc_admin_url ) ) {
			return;
		}

		$url_parts = wp_parse_url( $wc_admin_url );

		if ( empty( $url_parts['query'] ) ) {
			return;
		}

		$query_parts = array();
		wp_parse_str( $url_parts['query'], $query_parts );

		if ( ! isset( $query_parts['section'] ) || 'checkout-rate-limiting' !== $query_parts['section'] ) {
			return;
		}

		update_option( 'bh_wc_checkout_rate_limiter_visited_settings_time', time() );
	}

	/**
	 * Add the settings section to WordPress/WooCommerce/Settings/Advanced/Rate Limiting
	 *
	 * /wp-admin/admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting
	 *
	 * @hooked woocommerce_get_sections_checkout
	 * @see \WC_Settings_Advanced::get_sections()
	 *
	 * @param array<string, string> $sections The horizontal subsections in the WooCommerce settings.
	 * @return array<string, string>
	 */
	public function add_section( array $sections ): array {

		$sections['checkout-rate-limiting'] = 'Rate Limiting';

		return $sections;
	}

	/**
	 * Adds the settings:
	 * * Title + description
	 * * Enable/disable
	 * * Rate limits: attempts per interval
	 * * Log level
	 *
	 * * Empty cart?!
	 *
	 * @hooked woocommerce_get_settings_checkout
	 * @see \WC_Settings_Advanced::get_settings()
	 *
	 * @param array<int|string, array<string, mixed>> $settings WC_Settings_API settings fields.
	 * @param string                                  $current_section The slug of the current horizontal sub-section.
	 *
	 * @return array<int|string, array<string, mixed>>
	 */
	public function settings( array $settings, string $current_section ): array {

		if ( 'checkout-rate-limiting' !== $current_section ) {
			return $settings;
		}

		$settings[] = array(
			'title' => 'Checkout Rate-Limiting',
			'type'  => 'title',
			'desc'  => 'Each time a customer clicks "Place Order", their IP address is checked to see how many times they have already tried to place an order recently.',
			'id'    => 'checkout-rate-limiting',
		);

		// TODO: Add link to GitHub. Add link to logs.

		$settings['bh_wc_checkout_rate_limiter_checkout_rate_limiting_enabled'] = array(
			'title'   => __( 'Limit checkout attempts', 'bh-wc-checkout-rate-limiter' ),
			'desc'    => __( 'When enabled, each IP address can only make as many attempts at payment as specified below.', 'bh-wc-checkout-rate-limiter' ),
			'id'      => 'bh_wc_checkout_rate_limiter_checkout_rate_limiting_enabled',
			'type'    => 'checkbox',
			'default' => 'yes',
		);

		// Attempts per interval.
		$settings['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1'] = array(
			'title'   => '',
			'id'      => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1',
			'type'    => 'attempts_per_interval',
			'default' => array(
				'interval' => 60,
				'attempts' => 2,
			),
		);
		$settings['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2'] = array(
			'title'   => '',
			'id'      => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2',
			'type'    => 'attempts_per_interval',
			'default' => array(
				'interval' => 120,
				'attempts' => 3,
			),
		);
		$settings['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3'] = array(
			'title'   => '',
			'id'      => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3',
			'type'    => 'attempts_per_interval',
			'default' => array(
				'interval' => 300,
				'attempts' => 5,
			),
		);

		$log_levels        = array( 'none', LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG );
		$log_levels_option = array();
		foreach ( $log_levels as $log_level ) {
			$log_levels_option[ $log_level ] = ucfirst( $log_level );
		}

		$settings['bh_wc_checkout_rate_limiter_log_level'] = array(
			'title'    => __( 'Log Level', 'bh-wc-checkout-rate-limiter' ),
			'label'    => __( 'Enable Logging', 'bh-wc-checkout-rate-limiter' ),
			'type'     => 'select',
			'options'  => $log_levels_option,
			'desc'     => __( 'Increasingly detailed levels of logs. ', 'bh-wc-checkout-rate-limiter' ) . '<a href="' . admin_url( 'admin.php?page=bh-wc-checkout-rate-limiter-logs' ) . '">View Logs</a>',
			'desc_tip' => false,
			'default'  => 'notice',
			'id'       => 'bh_wc_checkout_rate_limiter_log_level',
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'checkout-rate-limiting',
		);

		return $settings;
	}

	/**
	 *
	 * // TODO: Is there a better name than $value here? (since it's an array with a "value" element).
	 *
	 * @see \WC_Admin_Settings::output_fields()
	 *
	 * @hooked woocommerce_admin_field_attempts_per_interval
	 * @param array<string, mixed> $value The template data to output.
	 */
	public function print_attempts_per_interval_settings_field( array $value ): void {

		$option_value = $value['value'];

		if ( ! isset( $option_value['attempts'] ) ) {
			$option_value['attempts'] = '';
		}

		if ( ! isset( $option_value['interval'] ) ) {
			$option_value['interval'] = '';
		}

		// Description handling... copied from WooCommerce WC_Admin_Settings.
		$field_description = WC_Admin_Settings::get_field_description( $value );
		$description       = $field_description['description'];
		$tooltip_html      = $field_description['tooltip_html'];

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php
					// Already escaped in WC_Admin_Settings::get_field_description().
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $tooltip_html;
				?>
					</label>
			</th>
			<td class="forminp">
				<input
					name="<?php echo esc_attr( $value['id'] ); ?>[attempts]"
					id="<?php echo esc_attr( $value['id'] ); ?>"
					type="number"
					style="width: 80px;"
					value="<?php echo esc_attr( $option_value['attempts'] ); ?>"
					class="<?php echo esc_attr( $value['class'] ); ?>"
					step="1"
					min="1"
				/>&nbsp;attempts per&nbsp;
				<input
					name="<?php echo esc_attr( $value['id'] ); ?>[interval]"
					id="<?php echo esc_attr( $value['id'] ); ?>"
					type="number"
					style="width: 80px;"
					value="<?php echo esc_attr( $option_value['interval'] ); ?>"
					class="<?php echo esc_attr( $value['class'] ); ?>"
					step="1"
					min="0"
				/>&nbspseconds.
			</td>
		</tr>
		<?php
	}
}
