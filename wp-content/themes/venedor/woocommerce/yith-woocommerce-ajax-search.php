<?php
/**
 * YITH WooCommerce Ajax Search template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly


wp_enqueue_script('yith_wcas_jquery-autocomplete' );
global $yith_ajax_searchform_count;
if (!isset($yith_ajax_searchform_count))
    $yith_ajax_searchform_count = 0;
$yith_ajax_searchform_count++;
?>
<div class="yith-ajaxsearchform-container_<?php echo $yith_ajax_searchform_count ?>">
<form role="search" method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/'  ) ) ?>" class="searchform">
    <fieldset>
        <span class="text"><input type="search" value="<?php echo get_search_query() ?>" name="s" id="yith-s_<?php echo $yith_ajax_searchform_count ?>" placeholder="<?php echo __('Search here', 'venedor'); ?>" autocomplete="off" /></span>
        <span class="button-wrap"><button id="yith-searchsubmit" class="btn btn-special" title="<?php echo __('Search', 'venedor'); ?>" type="submit"><span class="fa fa-search"></span></button></span>
        <input type="hidden" name="post_type" value="product" />
    </fieldset>
</form>
</div>
<script type="text/javascript">
jQuery(function($){
    var search_loader_url = <?php echo apply_filters('yith_wcas_ajax_search_icon', 'woocommerce_params.ajax_loader_url') ?>;

    $('#yith-s_<?php echo $yith_ajax_searchform_count ?>').autocomplete({
        minChars: <?php echo get_option('yith_wcas_min_chars') * 1; ?>,
        appendTo: '.yith-ajaxsearchform-container_<?php echo $yith_ajax_searchform_count ?>',
        serviceUrl: woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
        onSearchStart: function(){
            $(this).css({
                'background-image': 'url('+search_loader_url+')',
                'background-repeat': 'no-repeat'
            });
        },
        onSearchComplete: function(){
            $(this).css({
                'background-image': 'none',
                'background-repeat': 'no-repeat'
            });
        },
        onSelect: function (suggestion) {
            if( suggestion.id != -1 ) {
                window.location.href = suggestion.url;
            }
        }
    });
});
</script>