(function($j) {

    // Unbind any existing events
    $j('form.variations_form').find('.reset_variations').unbind('click');
    $j('form.variations_form').find('.variations input:radio').unbind('change');
    $j('form.variations_form').find('.variations select').unbind('change');
    $j('form.variations_form').unbind( 'if_match_found validate_variations update_variation_values' );

    $j('form.variations_form').on( 'click', '.reset_variations', function(){
        $jmain_form = $j(this).closest('form.variations_form');
        $jmain_form.find(".wcva_attribute_radio").prop('checked', false);
        $jmain_form.find('.variations select').val('');
        $jmain_form.find("input[name=variation_id]").val('');

        var $jproduct = $j(this).closest( '.product' );
        var productimage =  $jproduct.find(".attachment-shop_single").attr('data-o_src');
        var imagetitle   =  $jproduct.find(".attachment-shop_single").attr('data-o_title');
        var imagehref    =  $jproduct.find(".woocommerce-main-image").attr('data-o_href');
        $jproduct.find('.attachment-shop_single').attr('src', productimage);
        $jproduct.find('.attachment-shop_single').attr('title', imagetitle);
        $jproduct.find('.woocommerce-main-image').attr('href', imagehref);
        $jproduct.find('.woocommerce-main-image').attr('title', imagetitle);

        $jmain_form.find('.single_variation_wrap').hide();

        $j(this).hide();
        var is_selected             = false;
        $j(this).css('visibility','hidden').hide();

        var $sku = $j( this ).closest( '.product' ).find( '.sku' ),
            $weight = $j( this ).closest( '.product' ).find( '.product_weight' ),
            $dimensions = $j( this ).closest( '.product' ).find( '.product_dimensions' );

        if ( $sku.attr( 'data-o_sku' ) )
            $sku.text( $sku.attr( 'data-o_sku' ) );

        if ( $weight.attr( 'data-o_weight' ) )
            $weight.text( $weight.attr( 'data-o_weight' ) );

        if ( $dimensions.attr( 'data-o_dimensions' ) )
            $dimensions.text( $dimensions.attr( 'data-o_dimensions' ) );

        $jmain_form.trigger( 'validate_variations', [ '', false ] );

        return false;
    });

    $j('form.variations_form').on( 'change', '.variations input:radio', function( event ) {
        $jmain_form = $j(this).closest('form.variations_form');
        $jmain_form.find("input[name=variation_id]").val('').change();
        $jmain_form.trigger( 'validate_variations', [ '', false ] );
    } );

    $j('form.variations_form').on( 'validate_variations', function( event, exclude, focus ) {
        var if_variable_set             = false;
        var is_selected                 = false;
        var format            = {};
        var $jmain_form                 = $j(this);
        var $jreset_variations          = $jmain_form.find('.reset_variations');

        $jmain_form.find('.variations input:radio:checked').each( function() {

            if ( $j(this).val().length == 0 ) {
                if_variable_set = false;
            } else {
                is_selected = true;
                if_variable_set = true;
            }

            if ( exclude && $j(this).attr('name') == exclude ) {

                if_variable_set = false;
                format[$j(this).attr('name')] = '';

            } else {

                value = $j(this).val().replace(/&/g, '&');
                value = $j(this).val().replace(/"/g, '"');
                value = $j(this).val().replace(/'/g, "'");
                value = $j(this).val().replace(/</g, '<');
                value = $j(this).val().replace(/>/g, '>');

                if_variable_set = true;

                format[ $j(this).attr('name') ] = value;

            }

        });

        var pid                    = parseInt( $jmain_form.attr( 'data-product_id' ) );
        var all_variations         = window[ "product_variations_" + pid ];
        var available_matches      = get_variation_match( all_variations, format );
        var $jproduct              = $j(this).closest( '.product' );
        var $jshop_single_image    = $jproduct.find( 'div.images img:eq(0)' );
        var $jshop_single_link     = $jproduct.find( 'div.images a.zoom:eq(0)' );
        var productimage =  $jproduct.find(".attachment-shop_single").attr('data-o_src');
        var imagetitle   =  $jproduct.find(".attachment-shop_single").attr('data-o_title');
        var imagehref    =  $jproduct.find(".woocommerce-main-image").attr('data-o_href');


        if ( if_variable_set ) {

            var variation = available_matches.pop();

            if ( variation ) {
                if ( ! exclude ) {
                    $jmain_form.find('.single_variation_wrap').slideDown('200');
                }

                $jmain_form
                    .find('input[name=variation_id]')
                    .val( variation.variation_id )
                    .change();

                $jmain_form.trigger( 'if_match_found', [ variation ] );

            } else {

                if ( ! exclude ) {
                    $jmain_form.find('.single_variation_wrap').slideUp('200');
                }

            }

        } else {

            $jmain_form.trigger( 'update_variation_values', [ available_matches ] );

            if ( ! focus )
                if ( ! exclude ) {
                    $jmain_form.find('.single_variation_wrap').slideUp('200');
                }
        }

        if ( is_selected ) {

            if ( $jreset_variations.css('visibility') == 'hidden' )
                $jreset_variations.css('visibility','visible').hide().fadeIn();

        } else {

            $jreset_variations.css('visibility','hidden').hide();

        }

        $jmain_form.trigger( 'reset_image', [] );

    } );

    $j('form.variations_form').on( 'if_match_found', function( event, variation ) {
        var $jmain_form = $j(this);

        var $jproduct              = $j(this).closest( '.product' );
        var $jshop_single_image    = $jproduct.find( 'div.images img:eq(0)' );
        var $jshop_single_link     = $jproduct.find( 'div.images a.zoom:eq(0)' );
        var productimage           =  $jproduct.find(".attachment-shop_single").attr('data-o_src');
        var imagetitle             =  $jproduct.find(".attachment-shop_single").attr('data-o_title');
        var imagehref              =  $jproduct.find(".woocommerce-main-image").attr('data-o_href');

        var variation_image = variation.image_src;
        var variation_link = variation.image_link;
        var variation_title = variation.image_title;

        $jmain_form.find('.variations_button').show();
        $jmain_form.find('.single_variation').html( variation.price_html + variation.availability_html );

        if ( ! productimage ) {
            productimage = ( ! $jshop_single_image.attr('src') ) ? '' : $jshop_single_image.attr('src');
            $jshop_single_image.attr('data-o_src', productimage );
        }

        if ( ! imagehref ) {
            imagehref = ( ! $jshop_single_link.attr('href') ) ? '' : $jshop_single_link.attr('href');
            $jshop_single_link.attr('data-o_href', imagehref );
        }

        if ( ! imagetitle ) {
            imagetitle = ( ! $jshop_single_image.attr('title') ) ? '' : $jshop_single_image.attr('title');
            $jshop_single_image.attr('data-o_title', imagetitle );
        }

        if ( variation_image && variation_image.length > 1 ) {

            $jshop_single_image.attr( 'src', variation_image )
            $jshop_single_image.attr( 'alt', variation_title )
            $jshop_single_image.attr( 'title', variation_title );
            $jshop_single_link.attr( 'href', variation_link );
        } else {

            $jshop_single_image.attr( 'src', productimage )
            $jshop_single_image.attr( 'alt', imagetitle )
            $jshop_single_image.attr( 'title', imagetitle );
            $jshop_single_link.attr( 'href', imagehref );
        }

        var $jsingle_variation_wrap = $jmain_form.find('.single_variation_wrap');

        var $sku = $jproduct.find('.sku'),
            $weight = $jproduct.find('.product_weight'),
            $dimensions = $jproduct.find('.product_dimensions');

        if ( ! $sku.attr( 'data-o_sku' ) )
            $sku.attr( 'data-o_sku', $sku.text() );

        if ( ! $weight.attr( 'data-o_weight' ) )
            $weight.attr( 'data-o_weight', $weight.text() );

        if ( ! $dimensions.attr( 'data-o_dimensions' ) )
            $dimensions.attr( 'data-o_dimensions', $dimensions.text() );

        if ( variation.sku )
            $sku.text( variation.sku );
        else
            $sku.text( $sku.attr( 'data-o_sku' ) );

        if ( variation.weight ) {
            $weight.text( variation.weight );
        } else {
            $weight.text( $weight.attr( 'data-o_weight' ) );
        }

        if ( variation.dimensions ) {
            $dimensions.text( variation.dimensions );
        } else {
            $dimensions.text( $dimensions.attr( 'data-o_dimensions' ) );
        }

        $jsingle_variation_wrap.find('.quantity').show();

        if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
            $jmain_form.find('.variations_button').hide();
        }

        if ( ! variation.variation_is_visible ) {
            $jmain_form.find( '.single_variation' ).html( '<p>' + wc_add_to_cart_variation_params.i18n_unavailable_text + '</p>' );
        }

        if ( variation.min_qty )
            $jsingle_variation_wrap.find('input[name=quantity]').attr( 'data-min', variation.min_qty ).val( variation.min_qty );
        else
            $jsingle_variation_wrap.find('input[name=quantity]').removeAttr('data-min');

        if ( variation.max_qty )
            $jsingle_variation_wrap.find('input[name=quantity]').attr('data-max', variation.max_qty);
        else
            $jsingle_variation_wrap.find('input[name=quantity]').removeAttr('data-max');

        if ( variation.is_sold_individually == 'yes' ) {
            $jsingle_variation_wrap.find('input[name=quantity]').val('1');
            $jsingle_variation_wrap.find('.quantity').hide();
        }

        $jsingle_variation_wrap.slideDown('200').trigger( 'show_variation', [ variation ] );

    } );

    /*
     * find matching variation
     */
    function get_variation_match( product_variations, format ) {
        var matching = [];

        for (var i = 0; i < product_variations.length; i++) {
            var variation    = product_variations[i];
            var variation_id = variation.variation_id;
            var attrs1       = variation.attributes;
            var attrs2       = format;

            if ( validate_variations( attrs1, attrs2 ) ) {
                matching.push(variation);
            }
        }
        return matching;
    }

    var loop=0;

    function validate_variations( attrs1, attrs2 ) {
        var match = true;
        for ( attr_name in attrs1 ) {
            var attribute1="";
            var attribute2="";
            if(loop>1)
            {
                attribute1 = String(attrs1[ attr_name ]).toLowerCase();
                attribute2 = String(attrs2[ attr_name ]).toLowerCase();
            }
            else
            {
                attribute1 = attrs1[ attr_name ];
                attribute2 = attrs2[ attr_name ];
                loop++;
            }

            if ( attribute1 !== undefined && attribute2 !== undefined && attribute1.length != 0 && attribute2.length != 0 && attribute1 != attribute2 ) {
                match = false;
            }
        }
        return match;
    }

    $j('form.variations_form').on( 'change', '.variations select', function( event ) {
        var id =  $j(this).attr('id');
        $jmain_form = $j(this).closest('form.variations_form');
        $jmain_form.find("input[name='attribute_"+id+"'][value='"+this.value+"']").attr("checked","checked");
        $jmain_form.find("input[name=variation_id]").val('').change();
        $jmain_form.trigger( 'validate_variations', [ '', false ] );
    } );

    $j('form.variations_form .variations input:radio').change();
    $j('form.variations_form .variations select').change();

})(jQuery);