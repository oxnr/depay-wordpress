<?php defined( 'ABSPATH' ) or die; ?>
<div class="wrap wrap-depay-widget">
<?php settings_errors(); ?>
<h1><?php _e( 'DePay Widget - Options', 'depay-widget' ); ?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'depay_widget_optsgroup' ); ?>
		<?php do_settings_sections( 'depay_widget_optsgroup' ); ?>
		<table class="form-table">
			<tr>
				<th><?php _e( 'Amount', 'depay-widget' ); ?></th>
				<td>
					<input type="number" name="depay_widget_options[amount]" value="<?php esc_attr_e( $this->get_option( 'amount' ) ); ?>" class="regular-text" step="any" min="0">
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Token', 'depay-widget' ); ?></th>
				<td>
					<?php echo $this->get_tokens_dropdown( 'depay_widget_options[token]', $this->get_option( 'token' ) ); ?>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Receiver', 'depay-widget' ); ?></th>
				<td>
					<input type="text" name="depay_widget_options[receiver]" value="<?php esc_attr_e( $this->get_option( 'receiver' ) ); ?>" class="regular-text">
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>