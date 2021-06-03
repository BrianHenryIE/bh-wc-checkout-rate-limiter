<?php
/**
 * Settings page to display in WooCommerce.
 *
 * @see /wp-admin/admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
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
 * @package BrianHenryIE\Checkout_Rate_Limiter\WooCommerce
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
	 * Add the settings section to WordPress/WooCommerce/Settings/Advanced/Rate Limiting
	 *
	 * /wp-admin/admin.php?page=wc-settings&tab=advanced&section=checkout-rate-limiting
	 *
	 * @hooked woocommerce_get_sections_advanced
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
	 * @hooked woocommerce_get_settings_advanced
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

		$settings[] = array(
			'title'   => __( 'Limit checkout attempts', 'bh-wc-checkout-rate-limiter' ),
			'desc'    => __( 'When enabled, each IP address can only make as many attempts at payment as specified below.', 'bh-wc-checkout-rate-limiter' ),
			'id'      => 'bh_wc_checkout_rate_limiter_checkout_rate_limiting_enabled',
			'type'    => 'checkbox',
			'default' => 'no',
		);

		// Attempts per interval.
		$settings[] = array(
			'title' => '',
			'id'    => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1',
			'type'  => 'attempts_per_interval',
		);
		$settings[] = array(
			'title' => '',
			'id'    => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2',
			'type'  => 'attempts_per_interval',
		);
		$settings[] = array(
			'title' => '',
			'id'    => 'bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3',
			'type'  => 'attempts_per_interval',
		);

		$log_levels        = array( 'none', LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG );
		$log_levels_option = array();
		foreach ( $log_levels as $log_level ) {
			$log_levels_option[ $log_level ] = ucfirst( $log_level );
		}

		$settings[] = array(
			'title'    => __( 'Log Level', 'bh-wc-checkout-rate-limiter' ),
			'label'    => __( 'Enable Logging', 'bh-wc-checkout-rate-limiter' ),
			'type'     => 'select',
			'options'  => $log_levels_option,
			'desc'     => __( 'Increasingly detailed logging.', 'bh-wc-checkout-rate-limiter' ),
			'desc_tip' => true,
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
