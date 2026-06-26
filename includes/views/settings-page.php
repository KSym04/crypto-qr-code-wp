<?php
/**
 * Crypto QR Code WP settings page template.
 *
 * @package Crypto QR Code WP
 *
 * Exposed by Crypto_QR_Code_WP_Admin::render_page():
 *
 * @var array  $wallets    Saved wallet rows.
 * @var string $qr_size    Saved QR size.
 * @var array  $appearance Full settings array (color keys read below).
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
				<?php if( function_exists( 'cqcw_get_coins' ) ) : ?>
				<select class="cqcw-coin-pick" aria-label="<?php esc_attr_e( 'Pick a common coin', 'crypto-qr-code-wp' ); ?>">
					<option value=""><?php esc_html_e( 'Pick a coin', 'crypto-qr-code-wp' ); ?></option>
					<?php foreach( cqcw_get_coins() as $cqcw_ticker => $cqcw_coin ) : ?>
						<option value="<?php echo esc_attr( $cqcw_ticker ); ?>"><?php echo esc_html( $cqcw_ticker . ' (' . $cqcw_coin['name'] . ')' ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php endif; ?>
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

// Appearance controls: option key, label, and the CSS variable / QR target the
// live preview should update. Grouped for a clean, basic UI.
$cqcw_groups = array(
	array(
		'title'   => __( 'QR code', 'crypto-qr-code-wp' ),
		'fields'  => array(
			array( 'key' => 'qr_fg', 'label' => __( 'QR color', 'crypto-qr-code-wp' ), 'target' => 'qr-fg' ),
			array( 'key' => 'qr_bg', 'label' => __( 'QR background', 'crypto-qr-code-wp' ), 'target' => 'qr-bg' ),
		),
	),
	array(
		'title'   => __( 'Tooltip', 'crypto-qr-code-wp' ),
		'fields'  => array(
			array( 'key' => 'tip_bg', 'label' => __( 'Background', 'crypto-qr-code-wp' ), 'var' => '--cqcw-bg' ),
			array( 'key' => 'tip_border', 'label' => __( 'Border', 'crypto-qr-code-wp' ), 'var' => '--cqcw-border' ),
			array( 'key' => 'tip_heading', 'label' => __( 'Heading text', 'crypto-qr-code-wp' ), 'var' => '--cqcw-heading' ),
		),
	),
	array(
		'title'   => __( 'Address bar', 'crypto-qr-code-wp' ),
		'fields'  => array(
			array( 'key' => 'addr_bg', 'label' => __( 'Background', 'crypto-qr-code-wp' ), 'var' => '--cqcw-addr-bg' ),
			array( 'key' => 'addr_text', 'label' => __( 'Text', 'crypto-qr-code-wp' ), 'var' => '--cqcw-text' ),
		),
	),
	array(
		'title'   => __( 'Copy button', 'crypto-qr-code-wp' ),
		'fields'  => array(
			array( 'key' => 'copy_bg', 'label' => __( 'Background', 'crypto-qr-code-wp' ), 'var' => '--cqcw-copy-bg' ),
			array( 'key' => 'copy_text', 'label' => __( 'Text', 'crypto-qr-code-wp' ), 'var' => '--cqcw-copy-text' ),
		),
	),
);

// Defaults for the JS "Reset" action.
$cqcw_color_defaults = Crypto_QR_Code_WP_Admin::color_keys();

// Inline CSS variables so the preview renders correctly before any JS runs.
$cqcw_preview_style = '';
$cqcw_var_map = array(
	'tip_bg' => '--cqcw-bg', 'tip_border' => '--cqcw-border', 'tip_heading' => '--cqcw-heading',
	'addr_bg' => '--cqcw-addr-bg', 'addr_text' => '--cqcw-text',
	'copy_bg' => '--cqcw-copy-bg', 'copy_text' => '--cqcw-copy-text',
);
foreach( $cqcw_var_map as $cqcw_k => $cqcw_var ) {
	if( ! empty( $appearance[ $cqcw_k ] ) ) {
		$cqcw_preview_style .= $cqcw_var . ':' . $appearance[ $cqcw_k ] . ';';
	}
}
$cqcw_pv_fg = ! empty( $appearance['qr_fg'] ) ? $appearance['qr_fg'] : '#000000';
$cqcw_pv_bg = ! empty( $appearance['qr_bg'] ) ? $appearance['qr_bg'] : '#ffffff';
?>
<div class="wrap cqcw-wrap">
	<h1 class="cqcw-page-title">
		<span class="dashicons dashicons-grid-view cqcw-page-icon" aria-hidden="true"></span>
		<span class="cqcw-wordmark"><?php esc_html_e( 'Crypto QR Code', 'crypto-qr-code-wp' ); ?></span>
		<span class="cqcw-page-subtitle"><?php esc_html_e( 'Wallet Library', 'crypto-qr-code-wp' ); ?></span>
	</h1>

	<h2 class="nav-tab-wrapper cqcw-tabs" role="tablist">
		<a href="#settings" class="nav-tab nav-tab-active" data-cqcw-tab="settings" role="tab" aria-selected="true"><?php esc_html_e( 'Settings', 'crypto-qr-code-wp' ); ?></a>
		<a href="#appearance" class="nav-tab" data-cqcw-tab="appearance" role="tab" aria-selected="false"><?php esc_html_e( 'Appearance', 'crypto-qr-code-wp' ); ?></a>
	</h2>

	<div class="cqcw-layout">
	<div class="cqcw-main">
	<form method="post" action="options.php" class="cqcw-settings-form">
		<?php settings_fields( Crypto_QR_Code_WP_Admin::OPTION_GROUP ); ?>

		<div class="cqcw-tab-panel" id="cqcw-tab-settings" data-cqcw-panel="settings">
			<p class="cqcw-intro">
				<?php esc_html_e( 'Save your cryptocurrency wallets below. Each wallet gives you a ready made shortcode you can paste into any post, page, or widget to show a click to reveal QR code.', 'crypto-qr-code-wp' ); ?>
			</p>

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
		</div><!-- #cqcw-tab-settings -->

		<div class="cqcw-tab-panel" id="cqcw-tab-appearance" data-cqcw-panel="appearance" hidden>
			<p class="cqcw-intro">
				<?php esc_html_e( 'Design the look of the donation tooltip. The preview updates as you change each option.', 'crypto-qr-code-wp' ); ?>
			</p>

			<div class="cqcw-appearance">
				<div class="cqcw-appearance__controls">
					<div class="cqcw-control-group">
						<h3><?php esc_html_e( 'Size', 'crypto-qr-code-wp' ); ?></h3>
						<p class="cqcw-control">
							<label for="cqcw_qr_size"><?php esc_html_e( 'QR code size', 'crypto-qr-code-wp' ); ?></label>
							<span class="cqcw-control__field">
								<input type="number" id="cqcw_qr_size" name="cqcw_settings[qr_size]" value="<?php echo esc_attr( $qr_size ); ?>" min="80" max="400" step="10" class="small-text cqcw-pv" data-cqcw-target="size" />
								<span class="cqcw-field-suffix"><?php esc_html_e( 'pixels', 'crypto-qr-code-wp' ); ?></span>
							</span>
						</p>
					</div>

					<?php foreach( $cqcw_groups as $cqcw_group ) : ?>
						<div class="cqcw-control-group">
							<h3><?php echo esc_html( $cqcw_group['title'] ); ?></h3>
							<?php foreach( $cqcw_group['fields'] as $cqcw_field ) :
								$cqcw_key = $cqcw_field['key'];
								$cqcw_val = ! empty( $appearance[ $cqcw_key ] ) ? $appearance[ $cqcw_key ] : ( isset( $cqcw_color_defaults[ $cqcw_key ] ) ? $cqcw_color_defaults[ $cqcw_key ] : '#000000' );
								$cqcw_id  = 'cqcw_' . $cqcw_key;
								$cqcw_data = isset( $cqcw_field['var'] )
									? ' data-cqcw-target="var" data-cqcw-var="' . esc_attr( $cqcw_field['var'] ) . '"'
									: ' data-cqcw-target="' . esc_attr( $cqcw_field['target'] ) . '"';
								?>
								<p class="cqcw-control">
									<label for="<?php echo esc_attr( $cqcw_id ); ?>"><?php echo esc_html( $cqcw_field['label'] ); ?></label>
									<span class="cqcw-control__field">
										<input type="color" id="<?php echo esc_attr( $cqcw_id ); ?>" name="cqcw_settings[<?php echo esc_attr( $cqcw_key ); ?>]" value="<?php echo esc_attr( $cqcw_val ); ?>" class="cqcw-pv cqcw-color" data-cqcw-default="<?php echo esc_attr( isset( $cqcw_color_defaults[ $cqcw_key ] ) ? $cqcw_color_defaults[ $cqcw_key ] : '' ); ?>"<?php echo $cqcw_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from esc_attr() above. ?> />
										<code class="cqcw-color-value"><?php echo esc_html( $cqcw_val ); ?></code>
									</span>
								</p>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>

					<p>
						<button type="button" class="button button-secondary cqcw-reset-appearance">
							<span class="dashicons dashicons-image-rotate" aria-hidden="true"></span>
							<?php esc_html_e( 'Reset to defaults', 'crypto-qr-code-wp' ); ?>
						</button>
					</p>
				</div>

				<div class="cqcw-appearance__preview">
					<h3 class="cqcw-preview-title"><?php esc_html_e( 'Live preview', 'crypto-qr-code-wp' ); ?></h3>
					<div class="cqcw-preview-stage">
						<span class="cqcw-block cqcw-preview-block" style="<?php echo esc_attr( $cqcw_preview_style ); ?>">
							<em class="cqcw-block__dialog active">
								<strong class="cqcw-block__dialog-heading"><?php esc_html_e( 'Donate BTC', 'crypto-qr-code-wp' ); ?></strong>
								<span class="cqcw-block__qr cqcw-preview-qr" data-cqcw-address="bc1q097pndekk8pmh8rzrd55dz95wuplc4ndzq2auj" data-cqcw-size="<?php echo esc_attr( $qr_size ); ?>" data-cqcw-fg="<?php echo esc_attr( $cqcw_pv_fg ); ?>" data-cqcw-bg="<?php echo esc_attr( $cqcw_pv_bg ); ?>" role="img" aria-label="<?php esc_attr_e( 'QR code preview', 'crypto-qr-code-wp' ); ?>"></span>
								<strong class="cqcw-block__dialog-content">bc1q097pndekk8pmh8rzrd55dz95wuplc4ndzq2auj</strong>
								<button type="button" class="cqcw-block__copy" disabled>
									<svg class="cqcw-copy-icon" viewBox="0 0 20 20" width="13" height="13" aria-hidden="true" focusable="false"><path fill="currentColor" d="M13 2H6a2 2 0 0 0-2 2v9h2V4h7V2zm3 4H9a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm0 11H9V8h7v9z"></path></svg>
									<span class="cqcw-copy-label"><?php esc_html_e( 'Copy', 'crypto-qr-code-wp' ); ?></span>
								</button>
							</em>
						</span>
					</div>
					<p class="description"><?php esc_html_e( 'This is a sample tooltip. Your real wallets use the same look.', 'crypto-qr-code-wp' ); ?></p>
				</div>
			</div>
		</div><!-- #cqcw-tab-appearance -->

		<?php submit_button( esc_html__( 'Save Changes', 'crypto-qr-code-wp' ) ); ?>
	</form>
	</div><!-- .cqcw-main -->

	<aside class="cqcw-aside">
		<div class="cqcw-box">
			<h2><?php esc_html_e( 'Where to use your wallets', 'crypto-qr-code-wp' ); ?></h2>
			<p><?php esc_html_e( 'Paste a wallet shortcode into any post, page, or text widget, or add the Crypto QR Code WP block or widget. The QR code is generated in the browser, so nothing is stored on your server.', 'crypto-qr-code-wp' ); ?></p>
		</div>
		<div class="cqcw-box cqcw-more">
			<h2><?php esc_html_e( 'More from DopeThemes', 'crypto-qr-code-wp' ); ?></h2>
			<p><?php esc_html_e( 'Tutorials, themes, and more free plugins for WordPress and WooCommerce.', 'crypto-qr-code-wp' ); ?></p>
			<a class="button" href="https://www.dopethemes.com/" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Visit DopeThemes', 'crypto-qr-code-wp' ); ?>
				<span class="dashicons dashicons-external" aria-hidden="true"></span>
			</a>
		</div>
	</aside>
	</div><!-- .cqcw-layout -->
</div>

<script type="text/html" id="cqcw-row-template">
	<?php cqcw_render_wallet_row( '__INDEX__' ); ?>
</script>

<span class="cqcw-next-index" data-next="<?php echo esc_attr( $next_index ); ?>" hidden></span>
