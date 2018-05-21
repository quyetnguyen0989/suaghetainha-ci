<?php global $product; ?>
<li class="item clearfix">
    <a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" class="product-image" title="<?php echo esc_attr( $product->get_title() ); ?>">
        <?php echo $product->get_image(); ?>
    </a>
    <div class="product-details">
        <a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" class="product-name" title="<?php echo esc_attr( $product->get_title() ); ?>">
            <?php echo $product->get_title(); ?>
        </a>
        <?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
        <?php echo $product->get_price_html(); ?>
    </div>
</li>