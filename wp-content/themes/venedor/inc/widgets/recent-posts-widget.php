<?php
add_action('widgets_init', 'recent_posts_load_widgets');

function recent_posts_load_widgets()
{
    register_widget('Recent_Posts_Widget');
}

class Recent_Posts_Widget extends WP_Widget {
    
    function Recent_Posts_Widget()
    {
        $widget_ops = array('classname' => 'recent-posts', 'description' => __('Recent posts from the post.', 'venedor'));

        $control_ops = array('id_base' => 'recent_posts-widget');

        add_shortcode('recent_posts', array($this, 'shortcode'));

        $this->WP_Widget('recent_posts-widget', __('Venedor: Recent Posts', 'venedor'), $widget_ops, $control_ops);
    }
    
    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $number = $instance['number'];
        $items = $instance['items'];
        $cat = $instance['cat'];
        $show_title = $instance['show_title'];
        $show_excert = $instance['show_excert'];
        $show_meta = $instance['show_meta'];

        echo $before_widget;

        if($title) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="recent-posts-slider content-slider owl-carousel notitle clearfix">
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $number
        );
        if ($cat)
            $args['cat'] = $cat;
        $posts = new WP_Query($args);
        $count = 0;
        if($posts->have_posts()): ?>
        <?php while($posts->have_posts()): $posts->the_post(); 
        global $post; global $previousday; unset($previousday); ?>
        <?php if ($count % $items == 0) : ?><div class="slide"><?php endif; ?>
        <div class="post-item">
            <?php if(has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail('post-related'); ?>
            </a>
            <?php endif; ?>
            <?php if ($show_title) : ?>
            <div class="post-title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
            </div>
            <?php endif; ?>
            <?php if ($show_excert) echo '<p>'.venedor_excerpt(15, false).'</p>'; ?>
            <?php if ($show_meta) : ?>
            <div class="entry-meta clearfix">
                <div class="left">
                    <a class="read-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More', 'venedor') ?></a>
                </div>
                <div class="right">
                    <span class="meta-date"><?php echo get_the_date('', $post) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($count % $items == $items - 1) : ?></div><?php endif; ?>
        <?php $count++; endwhile; endif; ?>
        <?php if ($count % $items != 0) : ?></div><?php endif; ?>
        </div>

        <?php echo $after_widget;
        wp_reset_postdata();
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = $new_instance['number'];
        $instance['items'] = $new_instance['items'];
        $instance['cat'] = $new_instance['cat'];
        $instance['show_title'] = $new_instance['show_title'];
        $instance['show_excert'] = $new_instance['show_excert'];
        $instance['show_meta'] = $new_instance['show_meta'];
        
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => __('Recent Posts', 'venedor'), 'number' => 6, 'items' => 1, 'cat' => '', 'show_title' => 'on', 'show_excert' => 'on', 'show_meta' => 'on');
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'venedor') ?>:</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php echo __('Number of items to show', 'venedor') ?>:</label>
            <input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('items'); ?>"><?php echo __('Number of items per slide', 'venedor') ?>:</label>
            <input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('items'); ?>" name="<?php echo $this->get_field_name('items'); ?>" value="<?php echo $instance['items']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('cat'); ?>"><?php echo __('Category IDs', 'venedor') ?>:</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" value="<?php echo $instance['cat']; ?>" />
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_title'], 'on'); ?> id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php echo __('Show title', 'venedor') ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_excert'], 'on'); ?> id="<?php echo $this->get_field_id('show_excert'); ?>" name="<?php echo $this->get_field_name('show_excert'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_excert'); ?>"><?php echo __('Show excert', 'venedor') ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_meta'], 'on'); ?> id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_meta'); ?>"><?php echo __('Show meta', 'venedor') ?></label>
        </p>
    <?php
    }

    function shortcode($atts, $content) {
        extract(shortcode_atts(array(
            'title' => '',
            'desc' => '',
            'cat' => '',
            'show_title' => 'true',
            'show_excert' => 'true',
            'show_meta' => 'true',
            'items' => 6,
            'arrow_pos' => '',
            'animation_type' => '',
            'animation_duration' => 1,
            'animation_delay' => 0,
            'class' => ''
        ), $atts));

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $items
        );

        if ($cat)
            $args['cat'] = $cat;

        global $venedor_settings;

        $posts = new WP_Query($args);
        if ($posts->have_posts()) :
            $count = 0;
            ob_start(); ?>
            <?php while ($posts->have_posts()) : $posts->the_post();
            global $post; global $previousday; unset($previousday); ?>
            <?php if (has_post_thumbnail()) : $count++; ?>
                <div class="post-item">
                    <div class="inner">
                        <div class="post-image">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('post-related'); ?></a>
                            <?php if ($venedor_settings['post-zoom']) : ?>
                            <div class="figcaption">
                                <a class="btn btn-inverse zoom-button" href="<?php $thumbs = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); echo $thumbs[0]; ?>"><span class="fa fa-search"></span></a>
                                <a class="btn btn-inverse link-button" href="<?php the_permalink(); ?>"><span class="fa fa-link fa-rotate-90"></span></a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($show_title == 'true') : ?>
                        <div class="post-title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
                        </div>
                        <?php endif; ?>

                        <?php if ($show_excert == 'true') : ?>
                            <?php echo '<p>'.venedor_excerpt(15, false).'</p>'; ?>
                        <?php endif; ?>

                        <?php if ($show_meta == 'true') : ?>
                        <div class="entry-meta clearfix">
                            <div class="left">
                                <a class="read-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More', 'venedor') ?></a>
                            </div>
                            <div class="right">
                                <span class="meta-date"><?php echo get_the_date('', $post) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endwhile; ?>

            <?php
            $html = ob_get_contents();
            ob_end_clean();

            ob_start();
            if ($count) : ?>
            <div class="entry-related related-slider <?php echo $class ?><?php if (!$title) echo ' notitle' ?> <?php if ($arrow_pos) echo $arrow_pos ?> <?php if ($desc) echo ' with-desc'; ?> <?php if ($animation_type) echo 'animated' ?>"
                <?php if ($animation_type) : ?>
                 animation_type="<?php echo $animation_type ?>" animation_duration="<?php echo $animation_duration ?>" animation_delay="<?php echo $animation_delay ?>"
                <?php endif; ?>>
                <?php if ($title) : ?><h2 class="entry-title"><?php echo $title; ?></h2><?php endif; ?>
                <?php if ($desc) : ?><div class="slider-desc"><?php echo $desc; ?></div><?php endif; ?>
                <div class="row"><div class="post-carousel owl-carousel">
                    <?php echo $html ?>
                </div></div>
            </div>
            <?php endif; ?>

        <?php
        endif;
        wp_reset_postdata();
        ?>

        <?php
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }
}

// Register Shortcodes in Visual Composer Editor
if (function_exists('vc_set_as_theme')) {

    function venedor_vc_shortcode_recent_posts() {
        $vc_icon = venedor_vc_icon().'recent_posts.png';
        $animation_type = venedor_vc_animation_type();
        $animation_duration = venedor_vc_animation_duration();
        $animation_delay = venedor_vc_animation_delay();
        $custom_class = venedor_vc_custom_class();

        vc_map( array(
            "name" => __("Recent Posts", "venedor"),
            "base" => "recent_posts",
            "category" => "Venedor",
            "icon" => $vc_icon,
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", "venedor"),
                    "param_name" => "title",
                    "admin_label" => true
                ),
                array(
                    "type" => "textarea",
                    "heading" => __("Description", "venedor"),
                    "param_name" => "desc"
                ),
                array(
                    "type" => "boolean",
                    "heading" => __("Show Post Title", "venedor"),
                    "param_name" => "show_title",
                    "value" => "true"
                ),
                array(
                    "type" => "boolean",
                    "heading" => __("Show Post Excert", "venedor"),
                    "param_name" => "show_excert",
                    "value" => "true"
                ),
                array(
                    "type" => "boolean",
                    "heading" => __("Show Post Meta", "venedor"),
                    "param_name" => "show_meta",
                    "value" => "true"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Posts Count", "venedor"),
                    "param_name" => "items",
                    "value" => "6",
                    "admin_label" => true
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Category IDs", "venedor"),
                    "param_name" => "cat",
                    "value" => "",
                    "admin_label" => true
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => "Arrow Position",
                    'param_name' => 'arrow_pos',
                    'value' => array("" => "", "Top" => "arrow-top", "Bottom" => "arrow-bottom"),
                    'description' => ''
                ),
                $animation_type,
                $animation_duration,
                $animation_delay,
                $custom_class
            )
        ) );

        if ( class_exists( 'WPBakeryShortCodes' ) ) {
            class WPBakeryShortCode_Recent_Posts extends WPBakeryShortCodes {
            }
        }
    }
}

?>