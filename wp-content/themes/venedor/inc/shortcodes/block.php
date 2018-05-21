<?php
  
// Block
add_shortcode('block', 'venedor_shortcode_block');
function venedor_shortcode_block($atts, $content = null) {
    
    extract(shortcode_atts(array(
        'id' => '',
        'name' => '',
        'animation_type' => '',
        'animation_duration' => 1,
        'animation_delay' => 0,
        'class' => '',
    ), $atts));
    
    if (!($id || $name))
        return;
        
    if ($id)
        $block = get_posts( array( 'include' => $id, 'post_type' => 'block' ) ); 
        
    if ($name)
        $block = get_posts( array( 'name' => $name, 'post_type' => 'block' ) ); 
        
    if (!$block)
        return;
    $addthis_options = get_option('addthis_settings');
    if (defined('ADDTHIS_INIT' && !(isset($addthis_options) && isset($addthis_options['addthis_for_wordpress']) && ($addthis_options['addthis_for_wordpress'] == true))))
        add_filter('addthis_above_content', 'venedor_addthis_remove', 10, 1);

    $block_content = $block[0]->post_content;
    
    ob_start();
    ?>
    <div class="shortcode shortcode-block <?php echo $class ?> <?php if ($animation_type) echo 'animated' ?>"
        <?php if ($animation_type) : ?>
             animation_type="<?php echo $animation_type ?>" animation_duration="<?php echo $animation_duration ?>" animation_delay="<?php echo $animation_delay ?>"
        <?php endif; ?>>
        <?php echo do_shortcode($block_content) ?>
    </div>
    <?php
    $id = $block[0]->ID;
    if ( $id ) {
        $shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
        if ( ! empty( $shortcodes_custom_css ) ) { ?>
            <style type="text/css" data-type="vc_shortcodes-custom-css">
            <?php echo $shortcodes_custom_css ?>
            </style>
        <?php }
    }
    $str = ob_get_contents();
    ob_end_clean();
    
    return $str;
}

// Register Shortcodes in Visual Composer Editor
if (function_exists('vc_set_as_theme')) {

    function venedor_vc_shortcode_block() {
        $vc_icon = venedor_vc_icon().'block.png';
        $animation_type = venedor_vc_animation_type();
        $animation_duration = venedor_vc_animation_duration();
        $animation_delay = venedor_vc_animation_delay();
        $custom_class = venedor_vc_custom_class();

        vc_map( array(
            "name" => "Block",
            "base" => "block",
            "category" => "Venedor",
            "icon" => $vc_icon,
            "params" => array(
                array(
                    "type" => "label",
                    "heading" => "Input block id & name",
                    "param_name" => "label"
                ),
                array(
                    "type" => "textfield",
                    "heading" => "Block ID",
                    "param_name" => "id",
                    "admin_label" => true
                ),
                array(
                    "type" => "textfield",
                    "heading" => "Block Name",
                    "param_name" => "name",
                    "admin_label" => true
                ),
                $animation_type,
                $animation_duration,
                $animation_delay,
                $custom_class
            )
        ) );

        if ( class_exists( 'WPBakeryShortCodes' ) ) {
            class WPBakeryShortCode_Block extends WPBakeryShortCodes {
            }
        }
    }
}

