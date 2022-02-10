<?php
/**
 * Post template: List Small
 */

?>
<?php

$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'inhype-blog-thumb-grid');

if(has_post_thumbnail( $post->ID )) {
    $image_bg ='background-image: url('.esc_url($image[0]).');';
    $post_class = '';
}
else {
    $image_bg = '';
    $post_class = ' inhype-post-no-image';
}

$categories_list = inhype_get_the_category_list( $post->ID );

// Show post format
$current_post_format = get_post_format($post->ID) ? get_post_format($post->ID) : 'standard';

$post_class .= ' format-'.$current_post_format;

echo '<div class="sa inhype-list-post inhype-list-small-post inhype-post'.esc_attr($post_class).'"'.inhype_add_aos(false).'>';

if(has_post_thumbnail( $post->ID )) {
    echo '<div class="inhype-post-image-wrapper">';

    echo '<a href="'.esc_url(get_permalink($post->ID)).'"><div class="inhype-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
}

// Post details
echo '<div class="inhype-post-details">';

echo '<div class="post-categories">'.wp_kses($categories_list, inhype_esc_data()).'</div>';

echo '<h3 class="post-title entry-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.wp_kses_post($post->post_title).'</a></h3>';

if(get_theme_mod('blog_posts_author', false)):
?>
<div class="post-author">
    <span class="vcard">
        <?php echo esc_html__('By', 'inhype'); ?> <span class="fn"><?php echo get_the_author_posts_link(); ?></span>
    </span>
</div>
<div class="post-info-dot"></div>
<?php
endif;

echo '<div class="post-date">'.inhype_get_post_date($post->ID).'</div>';
?>
</div>

</div>
