<?php
/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $venedor_settings;

$flag = true;
if (!isset($product)) { $flag = false; global $product; }

$class = '';
?>
<div class="add-links-wrap">

    <?php
    $handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

    if ($venedor_settings['product-addcart']) {
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button cart-links %s product_type_%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->id ),
                esc_attr( $product->get_sku() ),
                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                esc_attr( $product->product_type ),
                esc_html( $product->add_to_cart_text() )
            ),
        $product );
    }

    $wishlist = (in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && $venedor_settings['product-wishlist']);
    $compare = (class_exists( 'YITH_Woocompare_Frontend' ) && $venedor_settings['product-compare']);

    if ( $wishlist || $compare ) : ?>
        <div class="add-links<?php if ( $wishlist && $compare ) echo ' show-all' ?>">
        <?php
        // Add Wishlist
        if ( $wishlist ) { 
            echo '<div class="add-links-item">'.do_shortcode('[yith_wcwl_add_to_wishlist]').'</div>';
        }

        // Add Compare
        if ( $compare && !$flag ) {
            global $yith_woocompare;
            echo '<div class="add-links-item">';
            $yith_woocompare->obj->add_compare_link();
            echo '</div>';
        }
        ?>
        </div>
    <?php endif;
?>
</div>