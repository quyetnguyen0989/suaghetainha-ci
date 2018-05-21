<?php
class wcva_register_style_scripts {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array(&$this,'wcva_register_my_scripts' ));
    }

    public function wcva_register_my_scripts() {
        global $post,$product;
        $displaytypenumber = 0;
        $product           = get_product($post->ID);
        if (is_product()) {
            $displaytypenumber = wcva_return_displaytype_number($product,$post);
        }
        wp_register_style( 'wcva-frontend', wcva_PLUGIN_URL . 'css/front-end.css' );

        $goahead=1;

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }

        if ((preg_match('/(?i)msie [5-8]/', $agent)) || strpos($agent, 'Trident/7.0; rv:11.0') !== false) {
            $goahead=0;
        }

        if (($displaytypenumber >0) && ($goahead == 1) ) {
            wp_deregister_script('wc-add-to-cart-variation');
            wp_dequeue_script ('wc-add-to-cart-variation');
            wp_register_script( 'wc-add-to-cart-variation', wcva_PLUGIN_URL . 'js/manage-variation-selection.js' ,array( 'jquery'), false, true);
        }

        if ( is_product() ) {
            if (($displaytypenumber >0) && ($goahead == 1)) {
                wp_enqueue_script('wc-add-to-cart-variation');
            }
        }
        wp_enqueue_style('wcva-frontend');
    }

}

new wcva_register_style_scripts();



?>
