<?php

/*
 * returns displytypenumber - which decide weather to replace variable.php template or not
 * @param $product-global product variable
 * @param $post-global post variable
 */

function wcva_return_displaytype_number($product,$post) {
    $displaytypenumber = 0;
    $product           = get_product($post->ID);
    $_coloredvariables = get_post_meta( $product->id, '_coloredvariables', true );

    if ( $product->product_type == 'variable' ) :
        $product = new WC_Product_Variable( $post->ID );
        $attributes = $product->get_variation_attributes();
    endif;

    if ((!empty($attributes)) && (sizeof($attributes) >0)) {
        foreach ($attributes as $key=>$values) {
            $displaytype = '';
            if (isset($_coloredvariables[$key]['display_type'])) {
                $displaytype=$_coloredvariables[$key]['display_type'];
            }
            if (($displaytype == "colororimage") || ($displaytype == "radio"))  {
                $displaytypenumber++;
            }
        } }

    return $displaytypenumber;
}

?>