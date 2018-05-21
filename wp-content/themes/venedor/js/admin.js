
// Uploading files
var file_frame;

jQuery(document).on( 'click', '.button_upload_image', function( event ){

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
        file_frame.open();
        return;
    }
    
    var clickedID = jQuery(this).attr('id');    
    
    // Create the media frame.
    file_frame = wp.media.frames.downloadable_file = wp.media({
        title: 'Choose an image',
        button: {
            text: 'Use image',
        },
        multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
        attachment = file_frame.state().get('selection').first().toJSON();

        jQuery('#' + clickedID).val( attachment.url );
    });

    // Finally, open the modal.
    file_frame.open();
});

jQuery(document).on( 'click', '.button_remove_image', function( event ){
    
    var clickedID = jQuery(this).attr('id');
    jQuery('#' + clickedID).val( '' );
    
    return false;
});

jQuery(document).ajaxComplete(function(event, xhr, settings) {
    setTimeout(function() {
        initVenedorMenu();
    }, 200);
});

function initVenedorMenu() {
    jQuery('.menu-item').unbind('click').click(function() {
        var id = jQuery(this).attr('id');

        id = id.replace('menu-item-', '');

        if (jQuery(this).hasClass('menu-item-depth-0')) {
            jQuery(this).find('.edit-menu-item-block-'+id).hide().find('select, input, textarea').each(function() {
                jQuery(this).removeAttr('name');
            });
            jQuery(this).find('.edit-menu-item-popup-'+id).show().find('select, input, textarea').each(function() {
                jQuery(this).attr('name', jQuery(this).attr('data-name'));
            });
        } else {
            if (jQuery(this).hasClass('menu-item-depth-1')) {
                jQuery(this).find('.edit-menu-item-block-'+id).show().find('select, input, textarea').each(function() {
                    jQuery(this).attr('name', jQuery(this).attr('data-name'));
                });
                jQuery(this).find('.edit-menu-item-popup-'+id).hide().find('select, input, textarea').each(function() {
                    jQuery(this).removeAttr('name');
                });
            } else {
                jQuery(this).find('.edit-menu-item-block-'+id).hide().find('select, input, textarea').each(function() {
                    jQuery(this).removeAttr('name');
                });
                jQuery(this).find('.edit-menu-item-popup-'+id).hide().find('select, input, textarea').each(function() {
                    jQuery(this).removeAttr('name');
                });
            }
        }
    });

    jQuery('.menu-item').find('select, textarea, input[type="text"]').unbind('change').change(function() {
        value = jQuery(this).val();
        if (value) {
            jQuery(this).attr('name', jQuery(this).attr('data-name'));
        } else {
            jQuery(this).removeAttr('name');
        }
    });

    jQuery('.menu-item').find('input[type="checkbox"]').unbind('change').change(function() {
        value = jQuery(this).is(':checked');
        if (value) {
            jQuery(this).attr('name', jQuery(this).attr('data-name'));
        } else {
            jQuery(this).removeAttr('name');
        }
    });
}

jQuery(function($) {
    initVenedorMenu();
});

