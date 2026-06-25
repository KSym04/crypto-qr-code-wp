<?php
/**
 * Crypto QR Code WP settings page template.
 *
 * @package Crypto QR Code WP
 *
 * Exposed by Crypto_QR_Code_WP_Admin::render_page():
 *
 * @var array  $wallets Saved wallet rows.
 * @var string $qr_size Saved QR size.
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template-scope variables.

// Render one wallet row. Used for existing rows and the JS template row.
if( ! function_exists( 'cqcw_render_wallet_row' ) ) {
	function cqcw_render_wallet_row( $index, $wallet = array() ) {
		$label     = isset( $wallet['label'] ) ? $wallet['label'] : '';
		$address   = isset( $wallet['address'] ) ? $wallet['address'] : '';
		$heading   = isset( $wallet['heading'] ) ? $wallet['heading'] : '';
		$base      = 'cqcw_settings[wallets][' . $index . ']';
		$shortcode = Crypto_QR_Code_WP_Admin::build_shortcode( $wallet );
		?>
		<tr class="cqcw-row">
			<td data-label="<?php esc_attr_e( 'Label', 'crypto-qr-code-wp' ); ?>">
				<input type="text" class="cqcw-field cqcw-field-label" name="<?php echo esc_attr( $base . '[label]' ); ?>" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php esc_attr_e( 'BTC', 'crypto-qr-code-wp' ); ?>" />
			</td>
			<td data-label="<?php esc_attr_e( 'Wallet address', 'crypto-qr-code-wp' ); ?>">
				<input type="text" class="cqcw-field cqcw-field-address" name="<?php echo esc_attr( $base . '[address]' ); ?>" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php esc_attr_e( 'Public key address', 'crypto-qr-code-wp' ); ?>" />
			</td>
			<td data-label="<?php esc_attr_e( 'Heading', 'crypto-qr-code-wp' ); ?>">
				<input type="text" class="cqcw-field cqcw-field-heading" name="<?php echo esc_attr( $base . '[heading]' ); ?>" value="<?php echo esc_attr( $heading ); ?>" placeholder="<?php esc_attr_e( 'Donate', 'crypto-qr-code-wp' ); ?>" />
			</td>
			<td class="cqcw-shortcode-cell" data-label="<?php esc_attr_e( 'Shortcode', 'crypto-qr-code-wp' ); ?>">
				<input type="text" class="cqcw-shortcode code" readonly value="<?php echo esc_attr( $shortcode ); ?>" onfocus="this.select();" />
				<button type="button" class="button cqcw-copy" title="<?php esc_attr_e( 'Copy shortcode', 'crypto-qr-code-wp' ); ?>">
					<span class="dashicons dashicons-clipboard" aria-hidden="true"></span>
				</button>
			</td>
			<td class="cqcw-remove-cell">
				<button type="button" class="button-link cqcw-remove" aria-label="<?php esc_attr_e( 'Remove wallet', 'crypto-qr-code-wp' ); ?>">
					<span class="dashicons dashicons-trash" aria-hidden="true"></span>
				</button>
			</td>
		</tr>
		<?php
	}
}

$next_index = count( $wallets );
?>
<div class="wrap cqcw-wrap">
	<h1 class="cqcw-page-title">
		<span class="dashicons dashicons-grid-view cqcw-page-icon" aria-hidden="true"></span>
		<span class="cqcw-wordmark"><?php esc_html_e( 'Crypto QR Code', 'crypto-qr-code-wp' ); ?></span>
		<span class="cqcw-page-subtitle"><?php esc_html_e( 'Wallet Library', 'crypto-qr-code-wp' ); ?></span>
	</h1>

	<p class="cqcw-intro">
		<?php esc_html_e( 'Save your cryptocurrency wallets below. Each wallet gives you a ready made shortcode you can paste into any post, page, or widget to show a click to reveal QR code.', 'crypto-qr-code-wp' ); ?>
	</p>

	<form method="post" action="options.php" class="cqcw-settings-form">
		<?php settings_fields( Crypto_QR_Code_WP_Admin::OPTION_GROUP ); ?>

		<div class="cqcw-settings-section">
			<h2><?php esc_html_e( 'Wallets', 'crypto-qr-code-wp' ); ?></h2>

			<table class="cqcw-wallets widefat striped">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Label', 'crypto-qr-code-wp' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Wallet Address', 'crypto-qr-code-wp' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Heading', 'crypto-qr-code-wp' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Shortcode', 'crypto-qr-code-wp' ); ?></th>
						<th scope="col" class="cqcw-remove-cell"><span class="screen-reader-text"><?php esc_html_e( 'Remove', 'crypto-qr-code-wp' ); ?></span></th>
					</tr>
				</thead>
				<tbody class="cqcw-wallets-body">
					<?php
					if( empty( $wallets ) ) {
						cqcw_render_wallet_row( 0 );
					} else {
						foreach( $wallets as $i => $wallet ) {
							cqcw_render_wallet_row( $i, $wallet );
						}
					}
					?>
				</tbody>
			</table>

			<p>
				<button type="button" class="button button-secondary cqcw-add-wallet">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'Add Wallet', 'crypto-qr-code-wp' ); ?>
				</button>
			</p>
		</div>

		<div class="cqcw-settings-section">
			<h2><?php esc_html_e( 'Display', 'crypto-qr-code-wp' ); ?></h2>
			<table class="form-table cqcw-form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="cqcw_qr_size"><?php esc_html_e( 'QR code size', 'crypto-qr-code-wp' ); ?></label></th>
						<td>
							<input type="number" id="cqcw_qr_size" name="cqcw_settings[qr_size]" value="<?php echo esc_attr( $qr_size ); ?>" min="80" max="400" step="10" class="small-text" />
							<span class="cqcw-field-suffix"><?php esc_html_e( 'pixels', 'crypto-qr-code-wp' ); ?></span>
							<p class="description"><?php esc_html_e( 'The width and height of the generated QR code, between 80 and 400 pixels.', 'crypto-qr-code-wp' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php submit_button( esc_html__( 'Save Changes', 'crypto-qr-code-wp' ) ); ?>
	</form>

	<div class="cqcw-settings-section cqcw-help">
		<h2><?php esc_html_e( 'How to use', 'crypto-qr-code-wp' ); ?></h2>
		<p><?php esc_html_e( 'There are two ways to display a wallet:', 'crypto-qr-code-wp' ); ?></p>
		<ol>
			<li><?php esc_html_e( 'Copy the shortcode for any wallet above and paste it into a post or page.', 'crypto-qr-code-wp' ); ?></li>
			<li>
				<?php
				printf(
					/* translators: %s: widget name. */
					esc_html__( 'Add the %s block or widget and enter the label and address.', 'crypto-qr-code-wp' ),
					'<strong>' . esc_html__( 'Crypto QR Code WP', 'crypto-qr-code-wp' ) . '</strong>'
				);
				?>
			</li>
		</ol>
		<p class="description">
			<?php esc_html_e( 'Shortcode parameters: heading (the tooltip title), label (such as BTC), and address (your public wallet key). The QR code is generated in the browser, so nothing is stored on your server.', 'crypto-qr-code-wp' ); ?>
		</p>
	</div>
</div>

<script type="text/html" id="cqcw-row-template">
	<?php cqcw_render_wallet_row( '__INDEX__' ); ?>
</script>

<span class="cqcw-next-index" data-next="<?php echo esc_attr( $next_index ); ?>" hidden></span>
