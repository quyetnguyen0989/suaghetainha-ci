<?php
/**
 * variable template
 * Originally Modified from Woocommerce Core
 * @author 		WooThemes
 * @package 	WooCommerce/templates/single-product/add-to-cart/variable.php
 * @version     2.1.6
 */



global $woocommerce, $product, $post;
$woo_version =  wcva_get_woo_version_number();
$_coloredvariables = get_post_meta( $post->ID, '_coloredvariables', true );

?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>


    <script type="text/javascript">
        var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
    </script>


    <form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
        <?php if ( ! empty( $available_variations ) ) : ?>

            <table class="variations" cellspacing="0">
                <tbody>
                <?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++;
                    if (isset( $_coloredvariables[$name]['display_type'])) {
                        $attribute_display_type  = $_coloredvariables[$name]['display_type'];
                    }
                    $taxonomies = array($name);
                    $args = array(
                        'hide_empty' => 0
                    );
                    $newvalues = get_terms( $taxonomies, $args);
                    if (isset($_coloredvariables[$name]['label'])) {
                        $labeltext=$_coloredvariables[$name]['label'];
                    } else {


                        if ($woo_version <2.1) {
                            $labeltext=$woocommerce->attribute_label( $name );
                        } else {
                            $labeltext=wc_attribute_label( $name );
                        }


                    }
                    ?>
                    <tr>
                        <td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php if (isset($labeltext) && ($labeltext != '')) { echo $labeltext; } else { echo wc_attribute_label( $name ); } ?></label></td>
                        <td class="value"> <?php

                            if ( is_array( $options ) ) {

                                if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
                                    $selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
                                } elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
                                    $selected_value = $selected_attributes[ sanitize_title( $name ) ];
                                } else {
                                    $selected_value = '';
                                }
                            }


                            if (isset($attribute_display_type) && ($attribute_display_type  == "colororimage"))	{
                                wcva_load_colored_select($name,$options,$_coloredvariables,$newvalues,$selected_value);
                                if ( sizeof($attributes) == $loop )
                                    echo '<br /><a class="reset_variations" href="#reset">' . __( 'Clear selection', 'wcva' ) . '</a>';
                            } elseif (isset($attribute_display_type) && ($attribute_display_type  == "radio"))  {
                                wcva_load_radio_select($name,$options,$selected_value);
                                if ( sizeof($attributes) == $loop )
                                    echo '<br /><a class="reset_variations" href="#reset">' . __( 'Clear selection', 'wcva' ) . '</a>';
                            } else {
                                wcva_load_dropdown_select($name,$options,$selected_value);
                                if ( sizeof($attributes) == $loop )
                                    echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'wcva' ) . '</a>';
                            }
                            ?></td>
                    </tr>

                <?php endforeach;?>
                </tbody>
            </table>

            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

            <div class="single_variation_wrap" style="display:none;">
                <?php do_action( 'woocommerce_before_single_variation' ); ?>

                <div class="single_variation"></div>

                <div class="variations_button">
                    <?php woocommerce_quantity_input(); ?>
                    <button type="submit" class="single_add_to_cart_button button alt">
                        <?php

                        if ($woo_version <2.1) {
                            echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type);
                        } else {
                            echo $product->single_add_to_cart_text();
                        }

                        ?>
                    </button>
                </div>

                <input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
                <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
                <input type="hidden" name="variation_id" value="" />

                <?php do_action( 'woocommerce_after_single_variation' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

        <?php else : ?>

            <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'wcva' ); ?></p>

        <?php endif; ?>

    </form>

<?php do_action( 'woocommerce_after_add_to_cart_form' );



/*
 * load default dropdown select
 * @param - $name
 * @param - $options
 */
function wcva_load_dropdown_select($name,$options,$selected_value) {
    wcva_load_radio_select($name,$options,$selected_value,$hidden=true);
    ?> <select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>">
        <option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
        <?php


        // Get terms if this is a taxonomy - ordered
        if ( taxonomy_exists( $name ) ) {

            $orderby = wc_attribute_orderby( $name );

            switch ( $orderby ) {
                case 'name' :
                    $args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
                    break;
                case 'id' :
                    $args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false );
                    break;
                case 'menu_order' :
                    $args = array( 'menu_order' => 'ASC', 'hide_empty' => false );
                    break;
            }

            $terms = get_terms( $name, $args );

            foreach ( $terms as $term ) {
                if ( ! in_array( $term->slug, $options ) )
                    continue;

                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
            }
        } else {

            foreach ( $options as $option ) {
                echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
            }

        }

        ?>
    </select>

<?php }

/*
 * Load radio select
 * since 1.0.2
 */
function wcva_load_radio_select($name,$options,$selected_value,$hidden=null) { ?>
    <fieldset style="<?php if (isset($hidden)) { echo 'display:none;';} ?>">

        <?php
        if ( is_array( $options ) ) {


            if ( taxonomy_exists( sanitize_title( $name ) ) ) {

                $terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );

                foreach ( $terms as $term ) {
                    if ( ! in_array( $term->slug, $options ) ) continue;
                    echo '<label for="attribute_'.sanitize_title($name).'_'.sanitize_title($term->slug).'"><input type="radio" class="wcva_attribute_radio" value="' . strtolower($term->slug) . '" ' . checked( strtolower ($selected_value), strtolower ($term->slug), false ) . '  id="attribute_'.sanitize_title($name).'_'.sanitize_title($term->slug).'" name="attribute_'. sanitize_title($name).'"> ' . apply_filters( 'woocommerce_variation_option_name', $term->name ).'</label><br />';
                }
            } else {
                foreach ( $options as $option )
                    echo '<label for="attribute_'.sanitize_title($name).'_'.sanitize_title($option).'"><input type="radio" class="wcva_attribute_radio" value="' .esc_attr( sanitize_title( $option ) ) . '" ' . checked( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . ' id="attribute_'.sanitize_title($name).'_'.sanitize_title($option).'" name="attribute_'. sanitize_title($name).'"> ' . apply_filters( 'woocommerce_variation_option_name', $option ) . '</label><br />';
            }
        }
        ?>
    </fieldset>
<?php


}
/*
 * Load colored select
 * since 1.0.0
 */
function wcva_load_colored_select($name,$options,$_coloredvariables,$newvalues,$selected_value) {
    ?>
    <fieldset>

        <?php
        if ( is_array( $options ) ) {



            if ( taxonomy_exists( sanitize_title( $name ) ) ) {

                $terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );

                foreach ( $terms as $term ) {
                    if ( ! in_array( $term->slug, $options ) ) continue; {
                        wcva_display_image_select_block($selected_value,$name,$term->slug,$_coloredvariables,$newvalues);
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    wcva_display_image_select_block($selected_value,$name,$option,$_coloredvariables,$newvalues);
                }
            }
        }
        ?>
    </fieldset>
<?php

}

/*
 * Get Image display
 * since 1.0.2
 */
function wcva_display_image_select_block($selected_value,$name,$option,$_coloredvariables,$newvalues){

    $globalthumbnail_id = '';
    $globaldisplay_type = 'Color';
    $globalcolor        =  'grey';


    foreach ($newvalues as $newvalue):
        if (isset($newvalue->name) && (strtolower($newvalue->name) == strtolower($option))) :

            $globalthumbnail_id 	= absint( get_woocommerce_term_meta( $newvalue->term_id, 'thumbnail_id', true ) );
            $globaldisplay_type 	= get_woocommerce_term_meta($newvalue->term_id, 'display_type', true );
            $globalcolor 	    = get_woocommerce_term_meta($newvalue->term_id, 'color', true );
        endif;
    endforeach;

    if ((isset($_coloredvariables[$name]['values'])) && (isset($_coloredvariables[$name]['values'][$option]['image']))) {
        $thumb_id = $_coloredvariables[$name]['values'][$option]['image']; $url = wp_get_attachment_thumb_url( $thumb_id );
    } elseif (isset($globalthumbnail_id)) {
        $thumb_id=$globalthumbnail_id; $url = wp_get_attachment_thumb_url( $globalthumbnail_id );
    }

    if ((isset($_coloredvariables[$name]['values'])) && (isset($_coloredvariables[$name]['values'][$option]['type']))) {
        $attrdisplaytype = $_coloredvariables[$name]['values'][$option]['type'];
    } elseif (isset($globaldisplay_type)) {
        $attrdisplaytype = $globaldisplay_type;
    }

    if ((isset($_coloredvariables[$name]['values'])) && (isset($_coloredvariables[$name]['values'][$option]['color']))) {
        $attrcolor = $_coloredvariables[$name]['values'][$option]['color'];
    } elseif (isset($globalcolor)) {
        $attrcolor = $globalcolor;
    }


    ?>
    <label class="wcvaswatches" style="display:inline;" for="attribute_<?php echo  sanitize_title($name); ?>_<?php echo  sanitize_title($option); ?>">
        <input type="radio" class="wcva_attribute_radio" value="<?php echo esc_attr( sanitize_title( $option ) ); ?>" <?php echo checked( sanitize_title( $selected_value ), sanitize_title( $option ), false ); ?> id="attribute_<?php echo  sanitize_title($name); ?>_<?php echo  sanitize_title($option); ?>" name="attribute_<?php echo  sanitize_title($name); ?>">
        <?php
        if (isset($_coloredvariables[$name]['size'])) {
            $thumbsize   = $_coloredvariables[$name]['size'];
            $displaytype = $_coloredvariables[$name]['displaytype'];
        } else {
            $thumbsize   = 'small';
            $displaytype = 'square';
        }
        $imageheight = wcva_get_image_height($thumbsize); $imagewidth = wcva_get_image_width($thumbsize);




        switch($attrdisplaytype) {
            case "Color":
                ?>
                <a  title="<?php echo $option; ?>" class="<?php if ($displaytype == "round") { echo 'wcvaround'; } ?>" style="display: inline-block; background-color:<?php if (isset($attrcolor)) { echo $attrcolor; } else { echo '#ffffff'; } ?>; width:<?php echo $imagewidth;?>px; height:<?php echo $imageheight;?>px; "></a>
                <?php
                break;
            case "Image":
                ?>
                <a title="<?php echo $option; ?>" class="<?php if ($displaytype == "round") { echo 'wcvaround'; } ?>" style="display: inline-block; width:<?php echo $imagewidth;?>px; height:<?php echo $imageheight;?>px; " ><img src="<?php if (isset($url)) { echo $url; } ?>" class="<?php if ($displaytype == "round") { echo 'wcvaround'; } ?>" style="width:<?php echo $imagewidth;?>px; height:<?php echo $imageheight;?>px; "></a>
                <?php
                break;
        }


        ?>
    </label>
<?php }

/*
 * Get Image Height
 * since 1.0.0
 */
function wcva_get_image_height($thumbsize) {
    $height=32;
    switch($thumbsize) {

        case "small":
            $height=32;
            break;


        case "extrasmall":
            $height=22;
            break;

        case "medium":
            $height=40;
            break;

        case "big":
            $height=60;
            break;

        case "extrabig":
            $height=90;
            break;

        default :
            $height=32;
            break;

    }
    return $height;
}

/*
 * Get Image Width
 * since 1.0.0
 */
function wcva_get_image_width($thumbsize) {
    $width=32;

    switch($thumbsize) {

        case "small":
            $width=32;
            break;

        case "extrasmall":
            $width=22;
            break;

        case "medium":
            $width=40;
            break;

        case "big":
            $width=60;
            break;

        case "extrabig":
            $width=90;
            break;

        default :
            $width=32;
            break;
    }

    return $width;
}