<?php
add_action('wp_enqueue_scripts', 'inhype_enqueue_styles');
function inhype_enqueue_styles()
{
  wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
  wp_enqueue_style('inhype-parent-style', get_template_directory_uri() . '/style.css', array('bootstrap'));
  wp_enqueue_style('inhype-child-style', get_stylesheet_directory_uri() . '/style.css', array('bootstrap'));
}

/**
 * Theme homepage blocks list
 */
if (!function_exists('inhype_blocks_list')) :
  function inhype_blocks_list()
  {

    $inhype_blocks_array = array(
      'saheadline' => esc_html__('[SA] Headline', 'inhype'),
      'sapostbycatads2' => esc_html__('[SA] Posts By Cats 2 Col+ads', 'inhype'),
      'sapostbycat' => esc_html__('[SA] Posts By Cats 3 Columns', 'inhype'),
      'sapostbycatads' => esc_html__('[SA] Posts By Cats 3 Col+ads', 'inhype'),      
      'sacarousel' => esc_html__( '[SA] Posts Carousel', 'inhype' ),      
      'sacarouselgreen' => esc_html__( '[SA] Posts Carousel (Green)', 'inhype' ),      
      'sacarouselgray' => esc_html__( '[SA] Posts Carousel (Gray)', 'inhype' ),      
      'postsline' => esc_html__('[POSTS] Posts Line #1', 'inhype'),
      'postsline2' => esc_html__('[POSTS] Posts Line #2', 'inhype'),
      'largepostsslider' => esc_html__('[POSTS] Large Posts Slider', 'inhype'),
      'largefullwidthpostsslider' => esc_html__('[POSTS] Large Fullwidth Posts Slider', 'inhype'),
      'fullwidthpostsslider' => esc_html__('[POSTS] Fullwidth Posts Slider', 'inhype'),
      'carousel' => esc_html__('[POSTS] Posts Carousel', 'inhype'),
      'carousel2' => esc_html__('[POSTS] Posts Cards Carousel', 'inhype'),
      'posthighlight' => esc_html__('[POSTS] Post Highlight #1 Slider', 'inhype'),
      'posthighlight2' => esc_html__('[POSTS] Post Highlight #2 Slider', 'inhype'),
      'postsgrid1' => esc_html__('[POSTS] Posts Grid #1', 'inhype'),
      'postsgrid2' => esc_html__('[POSTS] Posts Grid #2', 'inhype'),
      'postsgrid3' => esc_html__('[POSTS] Posts Grid #3', 'inhype'),
      'postsgrid4' => esc_html__('[POSTS] Posts Grid #4', 'inhype'),
      'postsgrid5' => esc_html__('[POSTS] Posts Grid #5', 'inhype'),
      'postsgrid6' => esc_html__('[POSTS] Posts Grid #6', 'inhype'),
      'postsgrid7' => esc_html__('[POSTS] Posts Grid #7', 'inhype'),
      'postsgrid8' => esc_html__('[POSTS] Posts Grid #8', 'inhype'),
      'postsgrid9' => esc_html__('[POSTS] Posts Grid #9', 'inhype'),
      'postsgrid10' => esc_html__('[POSTS] Posts Grid #10', 'inhype'),
      'postsgrid11' => esc_html__('[POSTS] Posts Grid #11', 'inhype'),
      'postsmasonry1' => esc_html__('[POSTS] Posts Masonry #1', 'inhype'),
      'postsmasonry2' => esc_html__('[POSTS] Posts Masonry #2', 'inhype'),
      'postsmasonry3' => esc_html__('[POSTS] Posts Masonry #3', 'inhype'),
      'postsmasonry4' => esc_html__('[POSTS] Posts Masonry #4', 'inhype'),
      'showcase1' => esc_html__('[POSTS] Showcase #1', 'inhype'),
      'showcase2' => esc_html__('[POSTS] Showcase #2', 'inhype'),
      'showcase3' => esc_html__('[POSTS] Showcase #3', 'inhype'),
      'showcase4' => esc_html__('[POSTS] Showcase #4', 'inhype'),
      'showcase5' => esc_html__('[POSTS] Showcase #5', 'inhype'),
      'showcase6' => esc_html__('[POSTS] Showcase #6', 'inhype'),
      'showcase7' => esc_html__('[POSTS] Showcase #7', 'inhype'),
      'showcase8' => esc_html__('[POSTS] Showcase #8', 'inhype'),
      'showcase9' => esc_html__('[POSTS] Showcase #9', 'inhype'),
      'html' => esc_html__('[HTML] HTML Block', 'inhype'),
      'blog' => esc_html__('[MISC] Blog Listing', 'inhype'),
      'subscribe' => esc_html__('[MISC] Subscribe Block', 'inhype'),
      'categories' => esc_html__('[MISC] Categories Block', 'inhype'),
      'instagram' => esc_html__('[MISC] Instagram Block', 'inhype')
    );

    if (class_exists('WooCommerce')) {
      $inhype_wc_blocks_array = array(
        'wcgrid1' => esc_html__('[SHOP] Products Grid #1', 'inhype'),
        'wccategories' => esc_html__('[SHOP] Categories Block', 'inhype'),
      );

      $inhype_blocks_array = array_merge($inhype_blocks_array, $inhype_wc_blocks_array);
    }

    return $inhype_blocks_array;
  }
endif;



/**
 * SA HEADLINE #1 block display
 */

if (!function_exists('inhype_block_saheadline_display')) :
  function inhype_block_saheadline_display($settings = array())
  {
    inhype_posts_block_renderer_sa('showcase3', $settings);
  }
endif;

/**
 * SA POST BY CAT 3 col #1 block display per category
 */

if (!function_exists('inhype_block_sapostbycat_display')) :
  function inhype_block_sapostbycat_display($settings = array())
  {
    inhype_posts_block_renderer_sapostpercat('3cols', $settings);
  }
endif;

/**
 * SA POST BY CAT 2 col + ads on right #1 block display per category
 */

if (!function_exists('inhype_block_sapostbycatads2_display')) :
  function inhype_block_sapostbycatads2_display($settings = array())
  {
    inhype_posts_block_renderer_sapostpercat('2cols-ads', $settings);
  }
endif;

/**
 * SA POST BY CAT 3 col + ads on right #1 block display per category
 */

if (!function_exists('inhype_block_sapostbycatads_display')) :
  function inhype_block_sapostbycatads_display($settings = array())
  {
    inhype_posts_block_renderer_sapostpercat('3cols-ads', $settings);
  }
endif;

/**
 * SA POST BY CAT 3 col + ads on right #1 block display per category
 */

if (!function_exists('inhype_block_sacarousel_display')) :
  function inhype_block_sacarousel_display($settings = array())
  {
    inhype_posts_block_renderer_sacarousel('default', $settings);
  }
endif;

/**
 * SA POST BY CAT 3 col + ads on right #1 block display per category
 */

if (!function_exists('inhype_block_sacarouselgreen_display')) :
  function inhype_block_sacarouselgreen_display($settings = array())
  {
    inhype_posts_block_renderer_sacarousel('green', $settings);
  }
endif;

/**
 * SA POST BY CAT 3 col + ads on right #1 block display per category
 */

if (!function_exists('inhype_block_sacarouselgray_display')) :
  function inhype_block_sacarouselgray_display($settings = array())
  {
    inhype_posts_block_renderer_sacarousel('gray', $settings);
  }
endif;


/**
 * Helper function to render posts blocks output SA
 */
if (!function_exists('inhype_posts_block_renderer_sa')) :
  function inhype_posts_block_renderer_sa($block_id = '', $settings = array(), $fullwidth = false)
  {

    // Blocks with custom title position, disable regular title
    $custom_title = array('showcase3');

    $args = inhype_get_wpquery_args($settings);

    $posts_query = new WP_Query;
    $posts = $posts_query->query($args);

    // Disable load more if specified offset
    if (!empty($settings['block_posts_offset'])) {
      $settings['block_posts_loadmore'] = 'no';
    }

    if ($posts_query->have_posts()) {

      $unique_block_id = rand(10000, 900000);

      echo '<div class="inhype-' . esc_attr($block_id) . '-block-wrapper inhype-' . esc_attr($block_id) . '-block-wrapper-' . esc_html($unique_block_id) . ' inhype-block">';

      if (!empty($settings['block_fullwidth']) && $settings['block_fullwidth']) {
        echo '<div class="inhype-block-wrapper-bg">';
      }

      if (!empty($settings['block_title']) && !in_array($block_id, $custom_title)) {
        echo '<div class="container container-title">';
        echo '<div class="row">';
        echo '<div class="inhype-block-title">';
        echo '<h3>' . esc_html($settings['block_title']) . '</h3>';
        if (!empty($settings['block_subtitle'])) {
          echo '<h4>' . esc_html($settings['block_subtitle']) . '</h4>';
        }
        echo '</div>';
        if (!empty($settings['block_description'])) {
          echo '<div class="inhype-block-description">' . do_shortcode($settings['block_description']) . '</div>';
        }
        echo '</div>';
        echo '</div>';
      }

      echo '<div class="container container-content">';
      echo '<div class="row">';

      $i = 0;
      $post_template = $block_id;

      while ($posts_query->have_posts()) {
        $posts_query->the_post();

        $i++;

        // Mixed templates

        if ($block_id == 'showcase3') {

          // Showcase 3
          if ($i == 1) {

            echo '<div class="col-md-7">';

            get_template_part('inc/templates/post/content', 'overlay-large');

            echo '</div>';

            if (!empty($settings['block_title'])) {
              echo '<div class="col-md-5">';

              echo '<div class="container-title">';
              echo '<div class="row">';
              echo '<div class="inhype-block-title">';
              echo '<h3>' . esc_html($settings['block_title']) . '</h3>';
              if (!empty($settings['block_subtitle'])) {
                echo '<h4>' . esc_html($settings['block_subtitle']) . '</h4>';
              }
              echo '</div>';
              echo '</div>';
              echo '</div>';

              echo '</div>';
            }
          } else {

            if ($i == 2) {
              echo '<div class="col-md-5">';
            }

            get_template_part('inc/part/content-list', 'smallsa');

            if ($i == $posts_query->post_count) {
              echo '</div>';
            }
          }
        } elseif ($block_id == 'postsgrid7') {

          echo '<div class="' . esc_attr(inhype_get_postsgrid_col($block_id)) . '">';

          $post_template = 'list-small';

          get_template_part('inc/templates/post/content', $post_template);

          echo '</div>';
        }
      }

      wp_reset_postdata();
    }
    echo '</div>';
    echo '</div>';

    if (!empty($settings['block_fullwidth']) && $settings['block_fullwidth']) {
      echo '</div>'; // .inhype-block-wrapper-bg
    }

    echo '</div>';
  }
endif;

/**
 * Helper function to render posts blocks output SA
 */
if (!function_exists('inhype_posts_block_renderer_sapostpercat')) :
  function inhype_posts_block_renderer_sapostpercat($block_id = '',$settings = array(), $fullwidth = false)
  {

    // Blocks with custom title position, disable regular title
    $custom_title = array('3cols');

    // Disable load more if specified offset
    if (!empty($settings['block_posts_offset'])) {
      $settings['block_posts_loadmore'] = 'no';
    }


    $unique_block_id = rand(10000, 900000);

    echo '<div class="inhype-' . esc_attr($block_id) . '-block-wrapper inhype-' . esc_attr($block_id) . '-block-wrapper-' . esc_html($unique_block_id) . ' inhype-block">';

    if (!empty($settings['block_fullwidth']) && $settings['block_fullwidth']) {
      echo '<div class="inhype-block-wrapper-bg">';
    }

    echo '<div class="container container-content">';
    echo '<div class="row">';

    if ($block_id == '2cols-ads') {
      $col_post = 6;
    } else if($block_id == '3cols-ads') {
      $col_post = 4;
    } else {
      $col_post = 4;
    }
 

    foreach ($settings['block_categories'] as $category) {
    
      $args = array(
        'post_status' => 'publish',
        'cat' => ($category) ? $category :  '',
        'posts_per_page' => $settings['block_posts_limit'],
      );

      $posts_query = new WP_Query;
      $posts = $posts_query->query($args);

      echo '<div class="col-md-' . $col_post . ' postbycat"> ';

      if($category) {
        echo '<div class="inhype-block-title">
                <h3>' . get_cat_name( $category ) . '</h3>
              </div>';
      }

      if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
          $posts_query->the_post();

   
          get_template_part('inc/part/content-list', 'postbycat');

          
        }

        wp_reset_postdata();
      }

      echo '</div>';
    }

    // jika pake ads, maka category yg dipilih harus 2 maksimalnya
    if( ($block_id  == '2cols-ads' ) || ($block_id  == '3cols-ads' )  ) {    
      
      $col_ads = ($block_id == '2cols-ads' ) ? '6' : '4';
      echo '<div class="col-md-' . $col_ads . ' postbycat"> ';  
        //membaca block html di post by category
        echo $settings['block_html'];

      echo '</div>';
    }



    echo '</div>';
    echo '</div>';

    if (!empty($settings['block_fullwidth']) && $settings['block_fullwidth']) {
      echo '</div>'; // .inhype-block-wrapper-bg
    }

    echo '</div>';
  }
endif;


/**
 * Posts Carousel block display
 */
if(!function_exists('inhype_posts_block_renderer_sacarousel')):
  function inhype_posts_block_renderer_sacarousel($color = '', $settings = array()) {

    $args = inhype_get_wpquery_args($settings);
  
    $posts_query = new WP_Query;
    $posts = $posts_query->query($args);
  
    $total_posts = count($posts);
  
    if($posts_query->have_posts()) {
  
      $unique_block_id = rand(10000, 900000);
      ?>
      <div class="inhype-carousel-block-wrapper inhype-block sa-carousel-wrapper <?= $color; ?>">
        <?php
        if(!empty($settings['block_title'])) {
          echo '<div class="container container-title">';
          echo '<div class="row">';
          echo '<div class="inhype-block-title">';
          echo '<h3>'.esc_html($settings['block_title']).'</h3>';
          if(!empty($settings['block_subtitle'])) {
            echo '<h4>'.esc_html($settings['block_subtitle']).'</h4>';
          }
          echo '</div>';
          if(!empty($settings['block_description'])) {
            echo '<div class="inhype-block-description">'.do_shortcode($settings['block_description']).'</div>';
          }
          echo '</div>';
          echo '</div>';
        }
        ?>
        <div class="container container-content">
          <div class="row">
            <div class="inhype-carousel-block inhype-carousel-block-<?php echo esc_attr($unique_block_id); ?> inhype-block">
              <div class="inhype-carousel-block-inside">
                <div class="owl-carousel">
                <?php
                while ($posts_query->have_posts()) {
                  $posts_query->the_post();
  
                  get_template_part( 'inc/templates/post/content', 'grid-short' );
  
                }
                wp_reset_postdata();
                ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
        if($total_posts > 1) {
          wp_add_inline_script( 'inhype-script', '(function($){
          $(document).ready(function() {
  
              var owlpostslider = $(".inhype-carousel-block.inhype-carousel-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                  loop: true,
                  items: 4,
                  autoplay: true,
                  autowidth: false,
                  autoplaySpeed: 500,
                  navSpeed: 500,
                  margin: 30,
                  nav: false,
                  dots: false,
                  navText: false,
                  slideBy: 4,
                  responsive: {
                      1199:{
                          items:4,
                          slideBy: 4
                      },
                      979:{
                          items:4,
                          slideBy: 4
                      },
                      768:{
                          items:2,
                          slideBy: 1
                      },
                      479:{
                          items:1,
                          slideBy: 1
                      },
                      0:{
                          items:1,
                          slideBy: 1
                      }
                  }
              });
  
              AOS.refresh();
  
          });})(jQuery);');
        } else {
          wp_add_inline_script( 'inhype-script', '(function($){
              $(document).ready(function() {
  
                "use strict";
  
                 $(".inhype-carousel-block.inhype-carousel-block-'.esc_js($unique_block_id).' .owl-carousel").show();
  
                 AOS.refresh();
  
              });})(jQuery);');
        }
  
      } // have_posts
  
      wp_reset_postdata();
  
  }
  endif;


  remove_action('tgmpa_register', 'inhype_register_required_plugins');

  
if(!function_exists('inhype_register_required_plugins')):
  function inhype_register_required_plugins() {
  
      /**
       * Array of plugin arrays. Required keys are name and slug.
       */
      $plugins = array(
          array(
              'name'                  => esc_html__('InHype Custom Metaboxes', 'inhype'),
              'slug'                  => 'cmb2',
              'required'              => true,
          ),
          array(
              'name'                  => esc_html__('InHype Theme Settings (Kirki Customizer Framework)', 'inhype'),
              'slug'                  => 'kirki',
              'required'              => true,
          ),
          array(
              'name'                  => esc_html__('InHype Theme Addons', 'inhype'),
              'slug'                  => 'inhype-theme-addons',
              'source'                => get_template_directory() . '/inc/plugins/inhype-theme-addons.zip',
              'required'              => true,
              'version'               => '1.2.3',
          ),
          // array(
          //     'name'                  => esc_html__('InHype AMP - Accelerated Mobile Pages support', 'inhype'),
          //     'slug'                  => 'amp',
          //     'required'              => false,
          // ),
          array(
              'name'                  => esc_html__('Envato Market - Automatic theme updates', 'inhype'),
              'slug'                  => 'envato-market',
              'source'                => get_template_directory() . '/inc/plugins/envato-market.zip',
              'required'              => false,
              'version'               => '2.0.6',
          ),
          array(
              'name'                  => esc_html__('InHype Page Navigation', 'inhype'),
              'slug'                  => 'wp-pagenavi',
              'required'              => false,
          ),
          // array(
          //     'name'                  => esc_html__('InHype Login and Registration Popup', 'inhype'),
          //     'slug'                  => 'ajax-login-and-registration-modal-popup',
          //     'source'                => get_template_directory() . '/inc/plugins/ajax-login-and-registration-modal-popup.zip',
          //     'required'              => false,
          // ),
          // array(
          //     'name'                  => esc_html__('InHype Translation Manager', 'inhype'),
          //     'slug'                  => 'loco-translate',
          //     'required'              => false,
          // ),
          // array(
          //     'name'                  => esc_html__('Instagram Feed', 'inhype'),
          //     'slug'                  => 'instagram-feed',
          //     'required'              => false,
          // ),
          // array(
          //     'name'                  => esc_html__('MailChimp for WordPress', 'inhype'),
          //     'slug'                  => 'mailchimp-for-wp',
          //     'required'              => false,
          // ),
          // array(
          //     'name'                  => esc_html__('WordPress LightBox', 'inhype'),
          //     'slug'                  => 'responsive-lightbox',
          //     'required'              => false
          // ),
          // array(
          //     'name'                  => esc_html__('Contact Form 7', 'inhype'),
          //     'slug'                  => 'contact-form-7',
          //     'required'              => false,
          // ),
          // array(
          //     'name'                  => esc_html__('Regenerate Thumbnails', 'inhype'),
          //     'slug'                  => 'regenerate-thumbnails',
          //     'required'              => false,
          // )
  
      );
  
      /**
       * Array of configuration settings.
       */
      $config = array(
          'domain'            => 'inhype',
          'default_path'      => '',
          'menu'              => 'install-required-plugins',
          'has_notices'       => true,
          'dismissable'  => true,
          'is_automatic'      => false,
          'message'           => ''
      );
  
      tgmpa( $plugins, $config );
  
  }
  endif;
  add_action('tgmpa_register', 'inhype_register_required_plugins');