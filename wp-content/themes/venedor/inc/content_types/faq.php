<?php

// Register FAQ Content Type
add_action('init', 'venedor_faq_init');

function venedor_faq_init() {
    
    register_post_type(         
        'faq',
        array(
            'labels' => venedor_labels('FAQ', 'FAQs'),
            'exclude_from_search' => true,
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'faq-items'),
            'supports' => array('title', 'editor'),
            'can_export' => true
        )
    );

    register_taxonomy(
        'faq_cat', 
        'faq', 
        array(
            'hierarchical' => true, 
            'labels' => venedor_labels_tax('FAQ Category', 'FAQ Categories'),
            'query_var' => true, 
            'rewrite' => true
        )
    );
}

?>
