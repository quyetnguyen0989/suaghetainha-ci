<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<?php wc_print_notices(); ?>

<form action="" method="post">

    <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="form-row form-row-first input-field">
		<label for="account_first_name"><span class="fa fa-user"></span><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="form-row form-row-last input-field">
		<label for="account_last_name"><span class="fa fa-user"></span><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<p class="form-row form-row-wide input-field">
		<label for="account_email"><span class="fa fa-envelope"></span><?php _e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

    <fieldset>
        <legend><?php _e( 'Password Change', 'woocommerce' ); ?></legend>

        <p class="form-row form-row-wide input-field">
            <label for="password_current"><span class="fa fa-lock"></span><?php _e( 'Current', 'venedor' ); ?></label>
            <input type="password" class="input-text" name="password_current" id="password_current" placeholder="<?php _e( 'Current Password (leave blank to leave unchanged)', 'woocommerce' ); ?>" />
        </p>
        <p class="form-row form-row-wide input-field">
            <label for="password_1"><span class="fa fa-lock"></span><?php _e( 'New', 'venedor' ); ?></label>
            <input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php _e( 'New Password (leave blank to leave unchanged)', 'woocommerce' ); ?>" />
        </p>
        <p class="form-row form-row-wide input-field">
            <label for="password_2"><span class="fa fa-lock"></span><?php _e( 'Confirm', 'venedor' ); ?></label>
            <input type="password" class="input-text" name="password_2" id="password_2" placeholder="<?php _e( 'Confirm New Password', 'woocommerce' ); ?>" />
        </p>
    </fieldset>
    <div class="clear"></div>

    <?php do_action( 'woocommerce_edit_account_form' ); ?>

    <p>
        <?php wp_nonce_field( 'save_account_details' ); ?>
        <input type="submit" class="button btn-lg" name="save_account_details" value="<?php _e( 'Save changes', 'woocommerce' ); ?>" />
        <input type="hidden" name="action" value="save_account_details" />
    </p>

    <?php do_action( 'woocommerce_edit_account_form_end' ); ?>

</form>