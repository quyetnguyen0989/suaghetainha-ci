<?php
/**
 * Lost password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;

?>

<?php wc_print_notices(); ?>

<form method="post" class="lost_reset_password">

	<?php if( 'lost_password' == $args['form'] ) : ?>

    <p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>

    <p class="form-row form-row-first input-field"><label for="user_login"><span class="fa fa-envelope"></span><?php _e( 'Username', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="user_login" id="user_login" placeholder="<?php _e( 'Username or email', 'woocommerce' ); ?>" /></p>

	<?php else : ?>

    <p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>

    <p class="form-row form-row-first input-field">
        <label for="password_1"><span class="fa fa-lock"></span><?php _e( 'New', 'venedor' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php _e( 'New password', 'woocommerce' ); ?>" />
    </p>
    <p class="form-row form-row-last input-field">
        <label for="password_2"><span class="fa fa-lock"></span><?php _e( 'Re-enter', 'venedor' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password_2" id="password_2" placeholder="<?php _e( 'Re-enter new password', 'woocommerce' ); ?>" />
    </p>

    <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
    <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

	<?php endif; ?>

    <div class="clear"></div>

    <p class="form-row button-row"><input type="submit" class="button btn-lg" name="wc_reset_password" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" /></p>
	<?php wp_nonce_field( $args['form'] ); ?>

</form>