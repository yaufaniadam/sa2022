<?php
/**
 * Theme Options
 **/

// Check that Kirki plugin installed
if ( class_exists( 'Kirki' ) ):

// FOUT/FOIT
$fonts_loading_type = 'FOUT';

if($fonts_loading_type == 'FOIT') {
    add_filter( 'kirki_googlefonts_font_display', function( $font_display ) {
        return 'block'; // Use auto|block|swap|fallback|optional.
    } );
}

// Load all fonts variants for Google Fonts
function inhype_font_add_all_variants() {
    if (class_exists('Kirki_Fonts_Google')) {

        if(get_theme_mod('webfonts_loadallvariants', false)) {
            Kirki_Fonts_Google::$force_load_all_variants = true;
        } else {
            Kirki_Fonts_Google::$force_load_all_variants = false;
        }

    }
}
add_action('init', 'inhype_font_add_all_variants');

// Load custom fonts
function inhype_custom_default_fonts( $standard_fonts ) {

    $fonts = array();

    $fonts['helvetica_neue'] = array(
        'label' => 'Helvetica Neue',
        'variant' => array( 'regular', 'bold' ),
        'stack' => 'Helvetica Neue',
    );

    $fonts['helvetica'] = array(
        'label' => 'Helvetica',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Helvetica',
    );

    $fonts['arial'] = array(
        'label' => 'Arial',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Arial',
    );

    $fonts['helvetica_neue'] = array(
        'label' => 'Times New Roman',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Times New Roman',
    );

    $fonts['verdana'] = array(
        'label' => 'Verdana',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Verdana',
    );

    $fonts['tahome'] = array(
        'label' => 'Tahoma',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Tahoma',
    );

    $fonts['courier_new'] = array(
        'label' => 'Courier New',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Courier New',
    );

    $fonts['georgia'] = array(
        'label' => 'Georgia',
        'variants' => array( 'regular', 'bold' ),
        'stack' => 'Georgia',
    );

    return $fonts;

}

add_filter( 'kirki/fonts/standard_fonts', 'inhype_custom_default_fonts', 0 );

// Update options cache on customizer save
if(!function_exists('inhype_update_options_cache')):
function inhype_update_options_cache() {
    $option_name = 'themeoptions_saved_date';

    $new_value = microtime(true) ;

    if ( get_option( $option_name ) !== false ) {

        // The option already exists, so we just update it.
        update_option( $option_name, $new_value );

    } else {

        // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
        $deprecated = null;
        $autoload = 'no';
        add_option( $option_name, $new_value, $deprecated, $autoload );
    }
}
endif;
add_action( 'customize_save_after', 'inhype_update_options_cache');

// Change default Customizer options, add new logo option
if(!function_exists('inhype_theme_customize_register')):
function inhype_theme_customize_register( $wp_customize ) {
    $wp_customize->remove_section( 'colors' );

    $wp_customize->get_section('header_image')->title = esc_html__( 'Logo', 'inhype' );

    $wp_customize->get_section('title_tagline')->title = esc_html__( 'Site Title and Favicon', 'inhype' );

    $wp_customize->add_setting( 'inhype_header_transparent_logo' , array(
         array ( 'default' => '',
                'sanitize_callback' => 'esc_url_raw'
                ),
        'transport'   => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'inhype_header_transparent_logo', array(
        'label'    => esc_html__( 'Logo for Transparent Header (Light logo)', 'inhype' ),
        'section'  => 'header_image',
        'settings' => 'inhype_header_transparent_logo',
    ) ) );

    // Move header image section to theme settings
    $wp_customize->get_section( 'header_image' )->panel = 'theme_settings_panel';
    $wp_customize->get_section( 'header_image' )->priority = 20;
}
endif;
add_action( 'customize_register', 'inhype_theme_customize_register' );

// Create theme options
Kirki::add_config( 'inhype_theme_options', array(
    'capability'    => 'edit_theme_options',
    'option_type'   => 'theme_mod',
) );

// Create main panel
Kirki::add_panel( 'theme_settings_panel', array(
    'title'       => esc_attr__( 'Theme Settings', 'inhype' ),
    'description' => esc_attr__( 'Manage theme settings', 'inhype' ),
) );

if(get_option('inhype_update') == 1):

Kirki::add_section( 'warning', array(
    'title'          => esc_attr__( 'WARNING: Theme purchase code blocked for illegal theme usage.', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 5,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'blocked_html',
    'label'       => '',
    'section'     => 'warning',
    'default'     => wp_kses_post(__('<strong>WARNING:</strong> Your theme purchase code blocked for illegal theme usage on multiple sites.<br/><br/>Please contact theme support for more information: <a href="https://support.magniumthemes.com" target="_blank">https://support.magniumthemes.com/</a>', 'inhype')),
) );

else:

// Theme Activation
if(get_option( 'inhype_license_key_status', false ) !== 'activated'):

Kirki::add_section( 'activation', array(
    'title'          => esc_attr__( 'Please register theme first', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 5,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'activation_html',
    'label'       => '',
    'section'     => 'activation',
    'default'     => '<p>'.esc_html__('Please register your purchase to get themes updates notifications, import theme demos and get access to premium dedicated support.', 'inhype').'</p><a href="themes.php?page=inhype_activate_theme" class="button button-primary">'.esc_html__('Register theme', 'inhype').'</a>',
) );

endif; // Theme activated

// SECTION: General
Kirki::add_section( 'general', array(
    'title'          => esc_attr__( 'General', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 10,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'animations_css3',
    'label'       => esc_attr__( 'CSS3 animations', 'inhype' ),
    'description' => esc_attr__( 'Enable colors and background colors fade effects.', 'inhype' ),
    'section'     => 'general',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'aos_animation',
    'label'       => esc_attr__( 'Animate posts listing on scroll', 'inhype' ),
    'section'     => 'general',
    'default'     => '',
    'multiple'    => 0,
    'choices'     => array(
        '' => esc_attr__( 'Disable', 'inhype' ),
        'fade-up' => esc_attr__( 'Fade up', 'inhype' ),
        'fade-down' => esc_attr__( 'Fade down', 'inhype' ),
        'zoom-in' => esc_attr__( 'Zoom In', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Animate on scroll feature for post blocks in listings. Does not available with Masonry layout.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'animations_images',
    'label'       => esc_attr__( 'Images on hover animations', 'inhype' ),
    'description' => esc_attr__( 'Enable mouse hover effects on featured images.', 'inhype' ),
    'section'     => 'general',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'style_corners',
    'label'       => esc_attr__( 'Rounded corners for theme elements', 'inhype' ),
    'section'     => 'general',
    'default'     => 'rounded',
    'multiple'    => 0,
    'choices'     => array(
        '' => esc_attr__( 'Disable', 'inhype' ),
        'rounded' => esc_attr__( 'Rounded', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Enable rounded corners for buttons, images, blocks and some other elements in theme.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'thumb_height_proportion',
    'label'       => esc_attr__( 'Thumbnails height proportion (%)', 'inhype' ),
    'description'       => esc_attr__( 'Used for most of all posts thumbnails on site. For ex. if you set 50% - image height will be 1/2 of image width.', 'inhype' ),
    'section'     => 'general',
    'default'     => 64.8648,
    'choices'     => array(
        'min'  => 20,
        'max'  => 300,
        'step' => 1,
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'button_backtotop',
    'label'       => esc_attr__( 'Scroll to top button', 'inhype' ),
    'description' => esc_attr__( 'Show scroll to top button after page scroll.', 'inhype' ),
    'section'     => 'general',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'background',
    'settings'    => 'body_background',
    'label'       => esc_attr__( 'Body background', 'inhype' ),
    'description' => esc_attr__( 'Change your site main background settings.', 'inhype' ),
    'section'     => 'general',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
) );
// END SECTION: General

// SECTION: Logo settings (default WordPress modified)
Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'logo_width',
    'label'       => esc_attr__( 'Logo image width (px)', 'inhype' ),
    'description' => esc_attr__( 'For example: 150', 'inhype' ),
    'section'     => 'header_image',
    'default'     => '162',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'logo_text',
    'label'       => esc_attr__( 'Text logo', 'inhype' ),
    'description' => esc_attr__( 'Use text logo instead of image.', 'inhype' ),
    'section'     => 'header_image',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'     => 'text',
    'settings' => 'logo_text_title',
    'label'    => esc_attr__( 'Text logo title', 'inhype' ),
    'section'  => 'header_image',
    'default'     => '',
    'description'  => esc_attr__( 'Add your site text logo. HTML tags allowed.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'logo_text_font',
    'label'       => esc_attr__( 'Text logo font', 'inhype' ),
    'section'     => 'header_image',
    'default'     => array(
        'font-family'    => 'Cormorant Garamond',
        'font-size'    => '62px',
        'variant'        => 'regular',
        'color'          => '#000000',
    ),
    'output'      => ''
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_tagline',
    'label'       => esc_attr__( 'Header tagline', 'inhype' ),
    'description' => esc_attr__( 'Show text tagline in header.', 'inhype' ),
    'section'     => 'header_image',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'header_tagline_style',
    'label'       => esc_attr__( 'Header tagline text style', 'inhype' ),
    'section'     => 'header_image',
    'default'     => 'regular',
    'choices'     => array(
        'regular'   => esc_attr__( 'Regular', 'inhype' ),
        'uppercase' => esc_attr__( 'UPPERCASE', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change header tagline text transform style.', 'inhype' ),
) );
// END SECTION: Logo settings (default WordPress modified)

// SECTION: Header
Kirki::add_section( 'header', array(
    'title'          => esc_attr__( 'Header', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 30,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'header_layout',
    'label'       => esc_html__( 'Header layout', 'inhype' ),
    'section'     => 'header',
    'default'     => 'menu-in-header',
    'multiple'    => 0,
    'choices'     => array(
        'menu-in-header'   => esc_attr__( '1. Menu in header', 'inhype' ),
        'menu-below-header-left'   => esc_attr__( '2. Menu below header, Left logo', 'inhype' ),
        'menu-below-header-left-border'   => esc_attr__( '3. Menu below header, Left logo, Border', 'inhype' ),
        'menu-below-header-left-border-fullwidth'   => esc_attr__( '4. Menu below header, Left logo, Fullwidth Border', 'inhype' ),
        'menu-below-header-center'   => esc_attr__( '5. Menu below header, Center logo', 'inhype' ),
        'menu-below-header-center-border'   => esc_attr__( '6. Menu below header, Center logo, Border', 'inhype' ),
        'menu-below-header-center-border-fullwidth'   => esc_attr__( '7. Menu below header, Center logo, Fullwidth border', 'inhype' ),
    ),
    'description' => esc_attr__( 'This option completely change site header layout and style.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'header_height',
    'label'       => esc_attr__( 'Header height (px)', 'inhype' ),
    'description' => esc_attr__( 'For example: 140', 'inhype' ),
    'section'     => 'header',
    'default'     => '140',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_sticky',
    'label'       => esc_attr__( 'Sticky header', 'inhype' ),
    'description' => esc_attr__( 'Main Menu fixed to top on scroll.', 'inhype' ),
    'section'     => 'header',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_socialicons',
    'label'       => esc_attr__( 'Social icons (first 5)', 'inhype' ),
    'description' => esc_attr__( 'Enable social icons in header.', 'inhype' ),
    'section'     => 'header',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'search_position',
    'label'       => esc_attr__( 'Search field', 'inhype' ),
    'section'     => 'header',
    'default'     => 'header',
    'choices'     => array(
        'header' => esc_attr__( 'Header', 'inhype' ),
        'fullscreen' => esc_attr__( 'Fullscreen', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Search field type.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_cart',
    'label'       => esc_attr__( 'WooCommerce mini cart', 'inhype' ),
    'description' => esc_attr__( 'Display WooCommerce dropdown cart in header.', 'inhype' ),
    'section'     => 'header',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_center_custom',
    'label'       => esc_attr__( 'Header center custom content', 'inhype' ),
    'description' => esc_attr__( 'Enable to display custom content (e.g. banner) in header center.', 'inhype' ),
    'section'     => 'header',
    'default'     => '0',
    'active_callback'  => array(
        array(
            'setting'  => 'header_layout',
            'operator' => 'in',
            'value'    => array('menu-below-header-left', 'menu-below-header-left-border', 'menu-below-header-left-border-fullwidth'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'header_center_custom_content',
    'label'       => esc_attr__( 'Header center custom content HTML', 'inhype' ),
    'description' => esc_attr__( 'HTML and shortcodes supported.', 'inhype' ),
    'section'     => 'header',
    'default'     => '',
    'active_callback'  => array(
        array(
            'setting'  => 'header_layout',
            'operator' => 'in',
            'value'    => array('menu-below-header-left', 'menu-below-header-left-border', 'menu-below-header-left-border-fullwidth'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_topline',
    'label'       => esc_attr__( 'Top line', 'inhype' ),
    'description' => esc_attr__( 'Enable to display header topline with custom text.', 'inhype' ),
    'section'     => 'header',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'header_topline_content',
    'label'       => esc_attr__( 'Top line text', 'inhype' ),
    'description' => esc_attr__( 'Add top line text here. HTML and shortcodes supported.', 'inhype' ),
    'section'     => 'header',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'header_topline_bgcolor_1',
    'label'       => esc_attr__( 'Top line background color', 'inhype' ),
    'description' => esc_attr__( 'First background color for ', 'inhype' ),
    'section'     => 'header',
    'default'     => '#2568ef',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'header_topline_bgcolor_2',
    'label'       => esc_attr__( 'Top line second background color', 'inhype' ),
    'description' => esc_attr__( 'Second background color for gradient effect.', 'inhype' ),
    'section'     => 'header',
    'default'     => '#2568ef',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'background',
    'settings'    => 'header_topline_background',
    'label'       => esc_attr__( 'Topline background image', 'inhype' ),
    'description' => esc_attr__( 'Change your topline background image settings.', 'inhype' ),
    'section'     => 'header',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_center_mobile_logo',
    'label'       => esc_attr__( 'Center logo on mobile', 'inhype' ),
    'section'     => 'header',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_disable',
    'label'       => esc_attr__( 'Disable entire header', 'inhype' ),
    'description' => esc_attr__( 'This option will disable ALL header (with menu below header, logo, etc). Useful for minimalistic design with left/right sidebar used to show logo and menu.', 'inhype' ),
    'section'     => 'header',
    'default'     => '0',
) );
// END SECTION: Header

// SECTION: Top menu
Kirki::add_section( 'topmenu', array(
    'title'          => esc_attr__( 'Top menu', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 40,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_style',
    'label'       => esc_attr__( 'Top menu style', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => 'menu_white',
    'choices'     => array(
        'menu_white'   => esc_attr__( 'Light', 'inhype' ),
        'menu_black' => esc_attr__( 'Dark', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change colors styling for top menu.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_border_style',
    'label'       => esc_attr__( 'Top menu bottom border style', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => 'menu_border_boxed',
    'choices'     => array(
        'menu_border_boxed'   => esc_attr__( 'Boxed', 'inhype' ),
        'menu_border_fullwidth' => esc_attr__( 'Fullwidth', 'inhype' ),

    ),
    'description'  => esc_attr__( 'Change text transform for top menu.', 'inhype' ),
    'active_callback'  => array(
        array(
            'setting'  => 'topmenu_style',
            'operator' => 'in',
            'value'    => array('menu_white'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_uppercase',
    'label'       => esc_attr__( 'Top menu text tranform', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => 'none',
    'choices'     => array(
        'uppercase'   => esc_attr__( 'UPPERCASE', 'inhype' ),
        'none' => esc_attr__( 'None', 'inhype' ),

    ),
    'description'  => esc_attr__( 'Change text transform for top menu.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_socialicons',
    'label'       => esc_attr__( 'Social icons (first 5)', 'inhype' ),
    'description' => esc_attr__( 'Enable social icons in top menu.', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_socialicons_position',
    'label'       => esc_attr__( 'Social icons position', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => 'left',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change top menu social icons position.', 'inhype' ),
    'active_callback'  => array(
        array(
            'setting'  => 'topmenu_socialicons',
            'operator' => 'in',
            'value'    => array('1'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_icon',
    'label'       => esc_attr__( 'Top menu mobile icon', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => 'regular',
    'choices'     => array(
        'regular'   => esc_attr__( 'Menu icon', 'inhype' ),
        'user' => esc_attr__( 'User icon', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change top menu icon on mobile.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_custom',
    'label'       => esc_attr__( 'Top menu custom content', 'inhype' ),
    'description' => esc_attr__( 'Enable to display custom content (e.g. text) in top menu.', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'topmenu_custom_content',
    'label'       => esc_attr__( 'Top menu custom content HTML', 'inhype' ),
    'description' => esc_attr__( 'HTML and shortcodes supported.', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '',
    'active_callback'  => array(
        array(
            'setting'  => 'topmenu_custom',
            'operator' => 'in',
            'value'    => array('1'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_custom_disable_mobile',
    'label'       => esc_attr__( 'Disable top menu custom content on mobile', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '0',
    'active_callback'  => array(
        array(
            'setting'  => 'topmenu_custom',
            'operator' => 'in',
            'value'    => array('1'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_disable_mobile',
    'label'       => esc_attr__( 'Disable top menu on mobile', 'inhype' ),
    'description' => esc_attr__( 'This option will disable top menu on mobile.', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_disable',
    'label'       => esc_attr__( 'Disable top menu', 'inhype' ),
    'description' => esc_attr__( 'This option will disable top menu.', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'dimension',
    'settings'    => 'topmenu_paddings',
    'label'       => esc_attr__( 'Top menu top/bottom paddings (px)', 'inhype' ),
    'description' => esc_attr__( 'Adjust this value to change menu height. Default: 10px', 'inhype' ),
    'section'     => 'topmenu',
    'default'     => '15px',
) );
// END SECTION: Top menu

// SECTION: Main menu
Kirki::add_section( 'mainmenu', array(
    'title'          => esc_attr__( 'Main menu', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 50,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_style',
    'label'       => esc_attr__( 'Main menu below header style', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 'light',
    'choices'     => array(
        'light'   => esc_attr__( 'Light', 'inhype' ),
        'dark' => esc_attr__( 'Dark', 'inhype' ),
    ),
    'description'  => esc_attr__( 'You can change dark menu background and menu links colors in "Theme settings > Colors" section.', 'inhype'),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_align',
    'label'       => esc_attr__( 'Main menu align', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 'left',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'center' => esc_attr__( 'Center', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_font_decoration',
    'label'       => esc_attr__( 'Main menu font decoration', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 'none',
    'choices'     => array(
        'uppercase'   => esc_attr__( 'UPPERCASE', 'inhype' ),
        'italic' => esc_attr__( 'Italic', 'inhype' ),
        'none' => esc_attr__( 'None', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_font_weight',
    'label'       => esc_attr__( 'Main menu font weight', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 'regularfont',
    'choices'     => array(
        'regularfont'   => esc_attr__( 'Regular', 'inhype' ),
        'boldfont' => esc_attr__( 'Bold', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_arrow_style',
    'label'       => esc_attr__( 'Main menu dropdown arrows', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 'noarrow',
    'choices'     => array(
        'rightarrow'   => esc_attr__( 'Right >', 'inhype' ),
        'downarrow' => esc_attr__( 'Down V', 'inhype' ),
        'noarrow' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'dimension',
    'settings'    => 'mainmenu_paddings',
    'label'       => esc_attr__( 'Main menu top/bottom paddings (px)', 'inhype' ),
    'description' => esc_attr__( 'Adjust this value to change menu height. Default: 10px', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => '10px',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'module_mega_menu',
    'label'       => esc_attr__( 'Mega Menu', 'inhype' ),
    'description' => esc_attr__( 'Enable Mega Menu module for additional menu options.', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'module_megamenu_sidebars',
    'label'       => esc_attr__( 'Mega Menu sidebars count', 'inhype' ),
    'description'       => esc_attr__( 'Additional sidebars for usage in mega menu items.', 'inhype' ),
    'section'     => 'mainmenu',
    'default'     => 1,
    'choices'     => array(
        'min'  => 0,
        'max'  => 100,
        'step' => 1,
    ),
) );

// END SECTION: Main menu

// SECTION: Footer
Kirki::add_section( 'footer', array(
    'title'          => esc_attr__( 'Footer', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 60,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'footer_style',
    'label'       => esc_attr__( 'Footer style', 'inhype' ),
    'section'     => 'footer',
    'default'     => 'white',
    'choices'     => array(
        'white'   => esc_attr__( 'Light', 'inhype' ),
        'black' => esc_attr__( 'Dark', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change colors styling for footer.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_sidebar_homepage',
    'label'       => esc_attr__( 'Footer sidebar only on homepage', 'inhype' ),
    'description' => esc_attr__( 'Disable this option to show footer sidebar on all site pages.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_menu',
    'label'       => esc_attr__( 'Footer menu', 'inhype' ),
    'description' => esc_attr__( 'Disable this option to hide footer menu.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_copyright',
    'label'       => esc_attr__( 'Footer copyright text', 'inhype' ),
    'description' => esc_attr__( 'Change your footer copyright text.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_socialicons',
    'label'       => esc_attr__( 'Social icons in footer (first 5)', 'inhype' ),
    'description' => esc_attr__( 'Disable this option to hide footer social icons.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_shortcodeblock',
    'label'       => esc_attr__( 'Footer shortcode block', 'inhype' ),
    'description' => esc_attr__( 'Boxed block with any shortcode from any plugin or HTML in footer.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_shortcodeblock_homepage',
    'label'       => esc_attr__( 'Footer shortcode block only on homepage', 'inhype' ),
    'description' => esc_attr__( 'Disable this option to show footer shortcode block on all site pages.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_shortcodeblock_html',
    'label'       => esc_attr__( 'Footer shortcode block content', 'inhype' ),
    'description' => esc_attr__( 'Add shortcode from any plugin that you want to display here (you can combine it with HTML), for example: <h1>My title</h1><div>[my_shortcode]</div>', 'inhype' ),
    'section'     => 'footer',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_htmlblock',
    'label'       => esc_attr__( 'Footer HTML block', 'inhype' ),
    'description' => esc_attr__( 'Fullwidth block with any HTML and background image in footer.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_htmlblock_homepage',
    'label'       => esc_attr__( 'Footer HTML block only on homepage', 'inhype' ),
    'description' => esc_attr__( 'Disable this option to show footer HTML block on all site pages.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'background',
    'settings'    => 'footer_htmlblock_background',
    'label'       => esc_attr__( 'Footer HTML block background', 'inhype' ),
    'description' => esc_attr__( 'Upload your footer HTML Block background image (1600x1200px JPG recommended). Remove image to remove background.', 'inhype' ),
    'section'     => 'footer',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'no-repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'footer_htmlblock_color_text',
    'label'       => esc_attr__( 'Footer HTML block text color', 'inhype' ),
    'description' => esc_attr__( 'Change text color in footer HTML block', 'inhype' ),
    'section'     => 'footer',
    'default'     => '#ffffff',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_htmlblock_html',
    'label'       => esc_attr__( 'Footer HTML block content', 'inhype' ),
    'description' => esc_attr__( 'You can use any HTML and shortcodes here to display any content in your footer block.', 'inhype' ),
    'section'     => 'footer',
    'default'     => '',
) );
// END SECTION: Footer

// SECTION: Blog
Kirki::add_section( 'blog', array(
    'title'          => esc_attr__( 'Blog: Listing', 'inhype' ),
    'description'    => esc_attr__( 'This settings affect your blog list display (homepage, archive, search).', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 70,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_layout',
    'label'       => esc_html__( 'Blog layout', 'inhype' ),
    'section'     => 'blog',
    'default'     => 'standard',
    'multiple'    => 0,
    'choices'     => array(

        'large-grid'   => esc_attr__( 'First large then grid', 'inhype' ),
        'overlay-grid'   => esc_attr__( 'First large overlay then grid', 'inhype' ),
        'large-list'   => esc_attr__( 'First large then list', 'inhype' ),
        'overlay-list'   => esc_attr__( 'First large overlay then list', 'inhype' ),
        'mixed-overlays'   => esc_attr__( 'Mixed overlays', 'inhype' ),
        'grid'   => esc_attr__( 'Grid', 'inhype' ),
        'list'   => esc_attr__( 'List', 'inhype' ),
        'standard'   => esc_attr__( 'Classic', 'inhype' ),
        'overlay'   => esc_attr__( 'Grid overlay', 'inhype' ),
        'mixed-large-grid'   => esc_attr__( 'Mixed large and grid', 'inhype' ),
        'masonry'   => esc_attr__( 'Masonry', 'inhype' ),

    ),
    'description' => esc_attr__( 'This option completely change blog listing layout and posts display.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_posts_excerpt',
    'label'       => esc_html__( 'Blog posts short content display', 'inhype' ),
    'section'     => 'blog',
    'default'     => 'excerpt',
    'multiple'    => 0,
    'choices'     => array(
        'content'   => esc_attr__('Full content (You will add <!--more--> tag manually)', 'inhype'),
        'excerpt' => esc_attr__('Excerpt (Auto crop by words)', 'inhype'),
        'none'  => esc_attr__('Disable short content and Continue reading button', 'inhype'),
    ),
    'description' => wp_kses_post(__( 'Change short post content display in blog listing.<br/><a href="https://en.support.wordpress.com/more-tag/" target="_blank">Read more</a> about &#x3C;!--more--&#x3E; tag.', 'inhype' )),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'blog_posts_excerpt_limit',
    'label'       => esc_attr__( 'Post excerpt length (words)', 'inhype' ),
    'description' => esc_attr__( 'Used by WordPress for post shortening. Default: 35', 'inhype' ),
    'section'     => 'blog',
    'default'     => '22',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_date_hide',
    'label'       => esc_attr__( 'Hide posts dates', 'inhype' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_author',
    'label'       => esc_attr__( 'Author name ("by author")', 'inhype' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_read_time',
    'label'       => esc_attr__( 'Read time', 'inhype' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_review',
    'label'       => esc_attr__( 'Review rating (%)', 'inhype' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_related',
    'label'       => esc_attr__( 'Related posts', 'inhype' ),
    'description' => esc_attr__( 'Display related posts after every post in posts list. Does not available in Masonry layout and 2 column layout.', 'inhype' ),
    'section'     => 'blog',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_posts_related_by',
    'label'       => esc_html__( 'Show related posts by', 'inhype' ),
    'section'     => 'blog',
    'default'     => 'tags',
    'multiple'    => 0,
    'choices'     => array(
        'tags'   => esc_attr__('Tags', 'inhype'),
        'categories' => esc_attr__('Categories', 'inhype'),
    ),
    'description' => wp_kses_post(__( 'Related posts can be fetched by the same tags or same categories from original post.', 'inhype' )),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_header_width',
    'label'       => esc_attr__( 'Blog archive header width', 'inhype' ),
    'section'     => 'blog',
    'default'     => 'fullwidth',
    'choices'     => array(
        'fullwidth'   => esc_attr__( 'Fullwidth', 'inhype' ),
        'boxed' => esc_attr__( 'Boxed', 'inhype' ),

    ),
) );

$blog_exclude_categories = Kirki_Helper::get_terms( 'category' );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'blog_exclude_categories',
    'label'       => esc_attr__( 'Exclude categories from blog listing', 'inhype' ),
    'description' => esc_attr__( 'You can exclude posts from some categories in your homepage Blog Listing block if you already display it in another blocks.', 'inhype' ),
    'section'     => 'blog',
    'default'     => '',
    'choices'     => $blog_exclude_categories,
) );

// END SECTION: Blog

// SECTION: Blog Single Post
Kirki::add_section( 'blog_post', array(
    'title'          => esc_attr__( 'Blog: Single post', 'inhype' ),
    'description'    => esc_attr__( 'This settings affect your blog single post display.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 80,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_post_header_layout',
    'label'       => esc_attr__( 'Blog post header layout and position', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => 'incontent2',
    'choices'     => array(
        'inheader'   => esc_attr__( 'In header - Style 1 (Info box)', 'inhype' ),
        'inheader2'   => esc_attr__( 'In header - Style 2 (Image)', 'inhype' ),
        'inheader3'   => esc_attr__( 'In header - Style 3 (2 column)', 'inhype' ),
        'incontent' => esc_attr__( "In content - Style 1 (Info box)", 'inhype' ),
        'incontent2' => esc_attr__( "In content - Style 2 (Title above image)", 'inhype' ),
        'incontent3' => esc_attr__( "In content - Style 3 (Title below image)", 'inhype' ),
    ),
    'description'  => esc_attr__( 'Change position of single post title and header image. You can upload Header image in post settings (separately from Featured image). If you use layout "In header" you must upload post header image to see image in header.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_post_header_image_type',
    'label'       => esc_attr__( 'Blog post header image', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => 'header',
    'choices'     => array(
        'header'   => esc_attr__( 'Post header image', 'inhype' ),
        'thumb'   => esc_attr__( 'Post featured image', 'inhype' ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'blog_post_header_layout',
            'operator' => 'in',
            'value'    => array('inheader', 'inheader2'),
        ),
    ),
    'description'  => esc_attr__( 'Use this if you want to display post featured image as post header image without uploading new images for your existing posts.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_caption',
    'label'       => esc_attr__( 'Show post featured image caption text', 'inhype' ),
    'description' => esc_attr__( 'Enable this option to show caption text that you defined for post featured image in Media.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_post_header_width',
    'label'       => esc_attr__( 'Blog post header width', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => 'boxed',
    'choices'     => array(
        'fullwidth'   => esc_attr__( 'Fullwidth', 'inhype' ),
        'boxed' => esc_attr__( 'Boxed', 'inhype' ),

    ),
    'active_callback'  => array(
        array(
            'setting'  => 'blog_post_header_layout',
            'operator' => 'in',
            'value'    => array('inheader', 'inheader2', 'inheader3'),
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_transparent_header',
    'label'       => esc_attr__( 'Transparent header', 'inhype' ),
    'description' => esc_attr__( 'This feature make your header transparent and will show it above post header image. You need to upload light logo version to use this feature and assign header image for posts where you want to see this feature. Transparent header for post available only with "In header - Style 1" blog post header layout option.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'active_callback'  => array(
        array(
            'setting'  => 'blog_post_header_layout',
            'operator' => '==',
            'value'    => 'inheader',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_smallwidth',
    'label'       => esc_attr__( 'Small content width', 'inhype' ),
    'description' => esc_attr__( 'This option add left/right margins on all posts without sidebars to make your content width smaller.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_author',
    'label'       => esc_attr__( 'Author details', 'inhype' ),
    'description' => esc_attr__( 'Show post author details with avatar after post content. You need to fill your post author biography details and social links in "Users" section in WordPress.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_author_email',
    'label'       => esc_attr__( 'Author email', 'inhype' ),
    'description' => esc_attr__( 'Display author email in author details social profiles.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_read_time',
    'label'       => esc_attr__( 'Read time', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_featured_image',
    'label'       => esc_attr__( 'Featured image', 'inhype' ),
    'description' => esc_attr__( 'Disable to hide post featured image on single post page (Globally on all site).', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_dropcaps',
    'label'       => esc_attr__( 'Drop caps (first big letter)', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_reading_progress',
    'label'       => esc_attr__( 'Reading progress bar', 'inhype' ),
    'description' => esc_attr__( 'Show reading progress bar in fixed header.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_share_fixed',
    'label'       => esc_attr__( 'Vertical fixed share buttons', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_info_bottom',
    'label'       => esc_attr__( 'Bottom post info', 'inhype' ),
    'description' => esc_attr__( 'Show post info box with tags, comments count, views and post share buttons after post content.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_tags',
    'label'       => esc_attr__( 'Tags', 'inhype' ),
    'description' => esc_attr__( 'Disable to hide post tags on single post page.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_comments',
    'label'       => esc_attr__( 'Comments counter', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_views',
    'label'       => esc_attr__( 'Views counter', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_likes',
    'label'       => esc_attr__( 'Likes counter', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_share',
    'label'       => esc_attr__( 'Share buttons', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_review',
    'label'       => esc_attr__( 'Review rating (%) in post header', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_related',
    'label'       => esc_attr__( 'Related posts', 'inhype' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_nav',
    'label'       => esc_attr__( 'Navigation links', 'inhype' ),
    'description' => esc_attr__( 'Previous/next posts navigation links below post content.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_subscribe',
    'label'       => esc_attr__( 'Subscribe form', 'inhype' ),
    'description' => esc_attr__( 'Show subscribe form on single blog post page.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_worthreading',
    'label'       => esc_attr__( 'Worth reading post', 'inhype' ),
    'description' => esc_attr__( 'Show one from selected suggested posts in fly-up fixed block in right bottom corner. Posts can be selected in your Post settings.', 'inhype' ),
    'section'     => 'blog_post',
    'default'     => '0',
) );

// END SECTION: Blog Single Post

// SECTION: Homepage
Kirki::add_section( 'homepage', array(
    'title'          => esc_attr__( 'Home: Blocks manager', 'inhype' ),
    'description'    => wp_kses_post(__('Here you can manage your homepage layout settings - add and order blocks. When you add new block you can configure its options depending on block type (displayed in [ ] brackets). You need to ignore options that does not related to this block. For example if you adding "[POSTS] Posts Line" you need to configure only options in "POSTS block settings" section. Ignore other sections options, because it does not related to your block. Blocks with [MISC] category can be configured independently in its own sections in customizer. You can find full configuration guide in <a href="http://magniumthemes.com/go/inhype-docs/" target="_blank">Theme Documentation</a>.', 'inhype' )),
    'panel'          => 'theme_settings_panel',
    'priority'       => 90,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'home_block_title_align',
    'label'       => esc_attr__( 'Blocks title align', 'inhype' ),
    'section'     => 'homepage',
    'default'     => 'left',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'center' => esc_attr__( 'Center', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'home_block_title_style',
    'label'       => esc_attr__( 'Blocks title style', 'inhype' ),
    'section'     => 'homepage',
    'default'     => 'regular',
    'choices'     => array(
        'regular'   => esc_attr__( 'Regular', 'inhype' ),
        'border' => esc_attr__( 'Border light', 'inhype' ),
        'border-dark' => esc_attr__( 'Border dark', 'inhype' ),
        'doubleborder' => esc_attr__( 'Double border', 'inhype' ),
        'thickborder' => esc_attr__( 'Thick border', 'inhype' )
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'home_block_subtitle_style',
    'label'       => esc_attr__( 'Blocks subtitle style', 'inhype' ),
    'section'     => 'homepage',
    'default'     => 'uppercase',
    'choices'     => array(
        'regular'   => esc_attr__( 'Regular', 'inhype' ),
        'uppercase' => esc_attr__( 'UPPERCASE', 'inhype' ),
    ),
) );

add_action( 'init', function() {

    if ( class_exists( 'WooCommerce' ) ) {
        $wc_categories = Kirki_Helper::get_terms( 'product_cat' );
        $wc_categories['0'] = esc_html__('All categories', 'inhype');
    } else {
        $wc_categories = array();
    }

    $wp_categories = Kirki_Helper::get_terms( 'category' );
    $wp_tags = Kirki_Helper::get_terms( 'post_tag' );

    $home_blocks_manager_fields = array(
        'type'        => 'repeater',
        'label'       => esc_attr__( 'Homepage layouts', 'inhype' ),
        'section'     => 'homepage',
        'row_label' => array(
            'type' => 'field',
            'value' => esc_attr__('Homepage block', 'inhype' ),
            'field' => 'block_type',
        ),
        'description' => esc_attr__('Choose and sort blocks used on your homepage to build its layout.', 'inhype' ),
        'button_label' => esc_attr__('Add block to homepage', 'inhype' ),
        'settings'     => 'homepage_blocks',
        'default'      => '',
        'fields' => array(
            'block_type' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Block to display', 'inhype' ),
                'description' => '',
                'choices'     => inhype_blocks_list(),
                'default'     => 'postsgrid1',
            ),
            'block_title' => array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Block title (optional)', 'inhype' ),
                'description' => esc_attr__( 'Add block title to display on site.', 'inhype' ),
                'default'     => '',
            ),
            'block_subtitle' => array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Block subtitle (optional)', 'inhype' ),
                'description' => esc_attr__( 'Add block subtitle to display on site.', 'inhype' ),
                'default'     => '',
            ),
            'block_description' => array(
                'type'        => 'textarea',
                'label'       => esc_attr__( 'Block description', 'inhype' ),
                'description' => esc_attr__( 'This content will be added below block title, above block content.', 'inhype' ),
                'sanitize_callback' => 'inhype_sanitize',
                'default'     => '',
            ),
            'block_hide' => array(
                'type'        => 'select',
                'choices'     => array(
                    'yes'   => esc_attr__( 'Yes', 'inhype' ),
                    'no' => esc_attr__( 'No', 'inhype' ),
                ),
                'label'       => esc_attr__( 'Hide block on blog pagination', 'inhype' ),
                'description' => esc_attr__( 'If your want to hide this block on next/prev blog posts listing pages enable this option.', 'inhype' ),
                'default'     => 'yes',
            ),

            // POST BLOCK settings
            'block_postsblock_options' => array(
                'type'        => 'custom',
                'label'       => '',
                'default'     => '<strong style="color: black;">POSTS block settings:</strong>',
            ),
            'block_posts_type' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Posts type', 'inhype' ),
                'description' => esc_attr__( 'Use this option if you added block with posts. Ignore it if you added content block (for ex. banner, subscribe form, etc).', 'inhype' ),
                'choices'     => inhype_post_types_list(),
                'default'     => 'latest',
            ),
            'block_categories' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Categories', 'inhype' ),
                'multiple'    => 12,
                'description' => esc_attr__( 'You can limit your posts by some categories in addition to post type.', 'inhype' ),
                'default'     => '',
                'choices'     => $wp_categories,
            ),
            'block_tags' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Tags', 'inhype' ),
                'multiple'    => 13,
                'description' => esc_attr__( 'You can limit your posts by some tags in addition to post type.', 'inhype' ),
                'default'     => '',
                'choices'     => $wp_tags,
            ),
            'block_posts_limit' => array(
                'type'        => 'number',
                'label'       => esc_attr__( 'Posts limit / Posts added with Load more', 'inhype' ),
                'description' => esc_attr__( 'If your posts block support posts limit you can specify it here. If you enabled "Load more" button this will change how many posts will be added from it.', 'inhype' ),
                'default'     => '3',
            ),
            'block_posts_loadmore' => array(
                'type'        => 'select',
                'choices'     => array(
                    'yes'   => esc_attr__( 'Yes', 'inhype' ),
                    'no' => esc_attr__( 'No', 'inhype' ),
                ),
                'label'       => esc_attr__( 'Load more button', 'inhype' ),
                'description' => esc_attr__( 'If your posts block support "Load more" button you can enable it here.', 'inhype' ),
                'default'     => 'no',
            ),
            'block_posts_offset' => array(
                'type'        => 'number',
                'label'       => esc_attr__( 'Posts offset', 'inhype' ),
                'description' => esc_attr__( 'Number of first posts to skip in posts query for this block. Using this option will disable "Load more" button.', 'inhype' ),
                'default'     => '',
            ),
            'block_fullwidth' => array(
                'type'        => 'select',
                'choices'     => array(
                    '1'   => esc_attr__( 'Yes', 'inhype' ),
                    '0' => esc_attr__( 'No', 'inhype' ),
                ),
                'label'       => esc_attr__( 'Fullwidth background', 'inhype' ),
                'description' => esc_attr__( 'Display fullwidth background for this block.', 'inhype' ),
                'default'     => '0',
            ),

            // SHOP BLOCK settings
            'block_wc_options' => array(
                'type'        => 'custom',
                'label'       => '',
                'default'     => '<strong style="color: black;">SHOP block settings:</strong>',
            ),
            'block_wc_type' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Products type', 'inhype' ),
                'description' => esc_attr__( 'Use this option if you added block with products.', 'inhype' ),
                'choices'     => inhype_product_types_list(),
                'default'     => 'latest',
            ),
            'block_wc_categories' => array(
                'type'        => 'select',
                'label'       => esc_attr__( 'Products category', 'inhype' ),
                'description' => esc_attr__( 'You can limit your products by some category in addition to post type.', 'inhype' ),
                'default'     => '',
                'choices'     => $wc_categories,
            ),
            'block_wc_limit' => array(
                'type'        => 'number',
                'label'       => esc_attr__( 'Products limit / Products added with Load more', 'inhype' ),
                'description' => esc_attr__( 'If your Shop block support posts limit you can specify it here. If you enabled "Load more" button this will change how many posts will be added from it.', 'inhype' ),
                'default'     => '3',
            ),
            'block_wc_loadmore' => array(
                'type'        => 'select',
                'choices'     => array(
                    'yes'   => esc_attr__( 'Yes', 'inhype' ),
                    'no' => esc_attr__( 'No', 'inhype' ),
                ),
                'label'       => esc_attr__( 'Load more button', 'inhype' ),
                'description' => esc_attr__( 'If your Shop block support "Load more" button you can enable it here.', 'inhype' ),
                'default'     => 'no',
            ),
            'block_wc_offset' => array(
                'type'        => 'number',
                'label'       => esc_attr__( 'Products offset', 'inhype' ),
                'description' => esc_attr__( 'Number of first products to skip in products query for this block. Using this option will disable "Load more" button.', 'inhype' ),
                'default'     => '',
            ),

            // CONTENT BLOCK settings
            'block_content_options' => array(
                'type'        => 'custom',
                'label'       => '',
                'default'     => '<strong style="color: black;">HTML block settings:</strong>',
            ),
            'block_html' => array(
                'type'        => 'textarea',
                'label'       => esc_attr__( 'Block HTML content', 'inhype' ),
                'description' => esc_attr__( 'Add any HTML content, videos, images here. For example you can add banners with this block.', 'inhype' ),
                'sanitize_callback' => 'inhype_sanitize',
                'default'     => '',
            ),

            // MISC BLOCK settings
            'block_misc_options' => array(
                'type'        => 'custom',
                'label'       => '',
                'default'     => '<strong style="color: black;">MISC block settings:</strong><p>You can configure MISC blocks settings in its own sections in Customizer.</p>',
            ),

        )
    );

    // Don't display WC options if WC not installed
    if ( !class_exists( 'WooCommerce' ) ) {
        unset($home_blocks_manager_fields['fields']['block_wc_options']);
        unset($home_blocks_manager_fields['fields']['block_wc_type']);
        unset($home_blocks_manager_fields['fields']['block_wc_categories']);
        unset($home_blocks_manager_fields['fields']['block_wc_limit']);
        unset($home_blocks_manager_fields['fields']['block_wc_loadmore']);
        unset($home_blocks_manager_fields['fields']['block_wc_offset']);
    }

    Kirki::add_field( 'inhype_theme_options', $home_blocks_manager_fields );

});
// END SECTION: Homepage

// SECTION: Blog Slider
Kirki::add_section( 'slider', array(
    'title'          => esc_attr__( 'Home: Header blog slider', 'inhype' ),
    'description'    => esc_attr__( 'Settings for homepage slider located in header.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 100,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_enable',
    'label'       => esc_attr__( 'Header blog slider', 'inhype' ),
    'description' => esc_attr__( 'Enable posts slider in header.', 'inhype' ),
    'section'     => 'slider',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_homepage',
    'label'       => esc_attr__( 'Show slider only on homepage', 'inhype' ),
    'description' => esc_attr__( 'Disable to show posts slider on all pages.', 'inhype' ),
    'section'     => 'slider',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_custom',
    'label'       => esc_attr__( 'Custom slider', 'inhype' ),
    'description' => esc_attr__( 'You can use third party slider plugins instead of theme slider. IMPORTANT: All theme slider options BELOW will NOT WORK if you enabled custom slider, use your slider plugin settings instead. You must specify your third party slider shortcode below.', 'inhype' ),
    'section'     => 'slider',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'text',
    'settings'    => 'slider_custom_shortcode',
    'label'       => esc_attr__( 'Custom slider shortcode', 'inhype' ),
    'description' => esc_attr__( 'Add your custom slider shortcode here (ignore this option if you use theme slider). For example: [your-slider]', 'inhype' ),
    'section'     => 'slider',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'slider',
    'settings'    => 'slider_height',
    'label'       => esc_attr__( 'Slider image height (px)', 'inhype' ),
    'description' => esc_attr__( 'Drag to change value. Default: 400', 'inhype' ),
    'section'     => 'slider',
    'default'     => 420,
    'choices'     => array(
        'min'  => '300',
        'max'  => '800',
        'step' => '5',
    ),
    'section'     => 'slider',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'slider_posts_type',
    'label'       => esc_attr__( 'Slider posts type', 'inhype' ),
    'section'     => 'slider',
    'default'     => 'featured',
    'choices'     => array(
        'featured'   => esc_attr__( 'Featured', 'inhype' ),
        'editorspicks' => esc_attr__( "Editor's picks", 'inhype' ),
        'promoted' => esc_attr__( "Promoted", 'inhype' ),
        'latest' => esc_attr__( 'Latest', 'inhype' ),
        'popular' => esc_attr__( 'Popular', 'inhype' ),
    ),
    'description'  => esc_attr__( 'Select posts to be displayed in your posts slider.', 'inhype' ),
) );

// remove first array element 'All categories'
$slider_categories = Kirki_Helper::get_terms( 'category' );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'slider_categories',
    'label'       => esc_attr__( 'Slider categories', 'inhype' ),
    'description' => esc_attr__( 'You can limit your posts by some category in addition to post type.', 'inhype' ),
    'section'     => 'slider',
    'default'     => '',
    'choices'     => $slider_categories,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'number',
    'settings'    => 'slider_limit',
    'label'       => esc_attr__( 'Slider posts limit', 'inhype' ),
    'description' => esc_attr__( 'Limit posts in slider. For example: 10', 'inhype' ),
    'section'     => 'slider',
    'default'     => '30',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'slider_autoplay',
    'label'       => esc_attr__( 'Slider autoplay (sec)', 'inhype' ),
    'description' => '',
    'section'     => 'slider',
    'default'     => '0',
    'choices'     => array(
        '0'   => esc_attr__( 'Disable', 'inhype' ),
        '10000' => '10',
        '5000' => '5',
        '3000' => '3',
        '2000' => '2',
        '1000' => '1',
    ),
) );

Kirki::add_field( 'florian_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_arrows',
    'label'       => esc_attr__( 'Navigation arrows', 'inhype' ),
    'description' => '',
    'section'     => 'slider',
    'default'     => '0',
) );

// END SECTION: Blog Slider

// SECTION: Subscribe block
Kirki::add_section( 'subscribeblock', array(
    'title'          => esc_attr__( 'Home: Subscribe block', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 110,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'subscribeblock_html',
    'label'       => esc_attr__( 'Subscribe block content', 'inhype' ),
    'description' => esc_attr__( 'Add shortcode from any plugin that you want to display here (you can combine it with HTML), for example: <h5>My title</h5><div>[my_shortcode]</div>', 'inhype' ),
    'section'     => 'subscribeblock',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'subscribeblock_example',
    'label'       => '',
    'section'     => 'subscribeblock',
    'default'     => 'Example subscribe block HTML code for Mailchimp WP plugin (change form id):<br><br><i>&#x3C;div class=&#x22;row&#x22;&#x3E;
&#x3C;div class=&#x22;col-md-12&#x22;&#x3E;
&#x3C;h5&#x3E;Sign up for our newsletter and
stay informed&#x3C;/h5&#x3E;
[mc4wp_form id=&#x22;10&#x22;]
&#x3C;/div&#x3E;
&#x3C;/div&#x3E;</i><br><br>Please check <a href="'.esc_url('http://magniumthemes.com/go/inhype-docs/').'" target="_blank">theme documentation</a> for more information about this option configuration.',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'background',
    'settings'    => 'subscribeblock_background',
    'label'       => esc_attr__( 'Subscribe block background image', 'inhype' ),
    'description' => esc_attr__( 'Change your subscribe block background image settings.', 'inhype' ),
    'section'     => 'subscribeblock',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
) );

// END SECTION: Subscribe block

// SECTION: Featured categories
Kirki::add_section( 'featured_categories', array(
    'title'          => esc_attr__( 'Home: Featured categories', 'inhype' ),
    'description'    => esc_attr__( 'Homepage block with selected categories boxes.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 120,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'featured_categories',
    'label'       => esc_attr__( 'Featured categories', 'inhype' ),
    'description' => esc_attr__( 'Select featured categories for display in [MISC] Featured categories homepage block. You need to upload categories header background images in every featured category settings page.', 'inhype' ),
    'section'     => 'featured_categories',
    'default'     => '',
    'choices'     => Kirki_Helper::get_terms( 'category' ),
) );

if ( class_exists( 'WooCommerce' ) ) {
    add_action( 'init', function() {

        $wc_categories = Kirki_Helper::get_terms( 'product_cat' );

        Kirki::add_field( 'inhype_theme_options', array(
            'type'        => 'multicheck',
            'settings'    => 'wc_featured_categories',
            'label'       => esc_attr__( 'Shop featured categories', 'inhype' ),
            'description' => esc_attr__( 'Select WooCommerce featured categories for display in [Shop] Featured categories homepage block. You need to upload categories header background images in every WooCommerce category settings page.', 'inhype' ),
            'section'     => 'featured_categories',
            'default'     => '',
            'choices'     => $wc_categories
        ) );

    });
}

// END SECTION: Featured categories

// SECTION: Blog Single Post
Kirki::add_section( 'page', array(
    'title'          => esc_attr__( 'Page: Single page', 'inhype' ),
    'description'    => esc_attr__( 'This settings affect your pages display.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 130,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'page_header_width',
    'label'       => esc_attr__( 'Page header width', 'inhype' ),
    'section'     => 'page',
    'default'     => 'boxed',
    'choices'     => array(
        'fullwidth'   => esc_attr__( 'Fullwidth', 'inhype' ),
        'boxed' => esc_attr__( 'Boxed', 'inhype' ),
    ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_page_smallwidth',
    'label'       => esc_attr__( 'Small content width', 'inhype' ),
    'description' => esc_attr__( 'This option add left/right margins on all pages without sidebars to make your content width smaller.', 'inhype' ),
    'section'     => 'page',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_page_transparent_header',
    'label'       => esc_attr__( 'Transparent header', 'inhype' ),
    'description' => esc_attr__( 'This feature make your header transparent and will show it above page header image. You need to upload light logo version to use this feature and assign header image for pages where you want to see this feature.', 'inhype' ),
    'section'     => 'page',
    'default'     => '0',
    'active_callback'  => array(
        array(
            'setting'  => 'page_header_width',
            'operator' => '==',
            'value'    => 'fullwidth',
        )
    )
) );

// SECTION: Sidebars
Kirki::add_section( 'sidebars', array(
    'title'          => esc_attr__( 'Sidebars', 'inhype' ),
    'description'    => esc_attr__( 'Choose your sidebar positions for different WordPress pages.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 140,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'sidebar_sticky',
    'label'       => esc_attr__( 'Sticky sidebar', 'inhype' ),
    'description' => esc_attr__( 'Enable sticky sidebar feature for all sidebars. Supported by Edge, Safari, Firefox, Google Chrome and other modern browsers.', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_blog',
    'label'       => esc_attr__( 'Blog listing', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_post',
    'label'       => esc_attr__( 'Single Post', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => esc_attr__( 'You can override sidebar position for every post in post settings.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_page',
    'label'       => esc_attr__( 'Single page', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => esc_attr__( 'You can override sidebar position for every page in page settings.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_archive',
    'label'       => esc_attr__( 'Archive', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_search',
    'label'       => esc_attr__( 'Search', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_woocommerce',
    'label'       => esc_attr__( 'WooCommerce pages', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_woocommerce_product',
    'label'       => esc_attr__( 'WooCommerce product page', 'inhype' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'inhype' ),
        'right' => esc_attr__( 'Right', 'inhype' ),
        'disable' => esc_attr__( 'Disable', 'inhype' ),
    ),
    'description'  => '',
) );
// END SECTION: Sidebars

// SECTION: Social icons
Kirki::add_section( 'social', array(
    'title'          => esc_attr__( 'Social icons', 'inhype' ),
    'description'    => esc_attr__( 'Add your social icons and urls. Social icons can be used in several site areas, sidebars widgets and shortcodes.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 150,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'repeater',
    'label'       => esc_attr__( 'Social icons (your profiles)', 'inhype' ),
    'section'     => 'social',
    'row_label' => array(
        'type' => 'field',
        'value' => esc_attr__('Social icon', 'inhype' ),
        'field' => 'social_type',
    ),
    'button_label' => esc_attr__('Add social icon', 'inhype' ),
    'settings'     => 'social_icons',
    'default'      => '',
    'fields' => array(
        'social_type' => array(
            'type'        => 'select',
            'label'       => esc_attr__( 'Social web', 'inhype' ),
            'description' => '',
            'choices'     => inhype_social_services_list(),
            'default'     => 'facebook',
        ),
        'social_url' => array(
            'type'        => 'text',
            'label'       => esc_attr__( 'Your profile url (including https://)', 'inhype' ),
            'description' => '',
            'default'     => '',
        ),
        'social_description' => array(
            'type'        => 'text',
            'label'       => esc_attr__( 'Additional text (for example "1500 Subscribers"). Displayed in social widget.', 'inhype' ),
            'description' => '',
            'default'     => '',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_facebook',
    'label'       => esc_attr__( 'Social share - Facebook', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_twitter',
    'label'       => esc_attr__( 'Social share - Twitter', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_linkedin',
    'label'       => esc_attr__( 'Social share - Linkedin', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_pinterest',
    'label'       => esc_attr__( 'Social share - Pinterest', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '1',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_vk',
    'label'       => esc_attr__( 'Social share - VKontakte', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_whatsapp',
    'label'       => esc_attr__( 'Social share - WhatsApp', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_telegram',
    'label'       => esc_attr__( 'Social share - Telegram', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_reddit',
    'label'       => esc_attr__( 'Social share - Reddit', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_email',
    'label'       => esc_attr__( 'Social share - Email', 'inhype' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'inhype' ),
    'section'     => 'social',
    'default'     => '0',
) );
// END SECTION: Social icons

// SECTION: Fonts
Kirki::add_section( 'fonts', array(
    'title'          => esc_attr__( 'Fonts', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 160,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'headers_font',
    'label'       => esc_attr__( 'Headers font', 'inhype' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Nunito',
        'variant'        => '800',
    ),
    'description' => esc_attr__( 'Font used in headers (H1-H6 tags).', 'inhype' ),
    'output'      => ''
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'body_font',
    'label'       => esc_attr__( 'Body font', 'inhype' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Rubik',
        'variant'        => 'regular',
        'font-size'      => '15px',
    ),
    'description' => esc_attr__( 'Font used in text elements.', 'inhype' ),
    'output'      => ''
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'additional_font',
    'label'       => esc_attr__( 'Additional font', 'inhype' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Nunito',
        'variant'        => '600',
    ),
    'description' => esc_attr__( 'Decorative font used in buttons, menus and some other elements.', 'inhype' ),
    'output'      => ''
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'webfonts_loadallvariants',
    'label'       => esc_attr__( 'Load all Google Fonts variants', 'inhype' ),
    'description' => esc_attr__( 'Enable to load all available variants and subsets for fonts that you selected.', 'inhype' ),
    'section'     => 'fonts',
    'default'     => '0',
) );

// END SECTION: Fonts

// SECTION: Colors
Kirki::add_section( 'colors', array(
    'title'          => esc_attr__( 'Colors', 'inhype' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 170,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'select',
    'settings'    => 'color_skin',
    'label'       => esc_html__( 'Color skin', 'inhype' ),
    'section'     => 'colors',
    'default'     => 'none',
    'multiple'    => 0,
    'choices'     => array(
        'none'   => esc_attr__( 'Custom colors (show selectors)', 'inhype' ),
        'default' => esc_attr__( 'Default', 'inhype' ),
        'dark' => esc_attr__('Dark (use with option below)', 'inhype'),
        'black' => esc_attr__('Black', 'inhype'),
        'grey' => esc_attr__('Grey', 'inhype'),
        'lightblue' => esc_attr__('Light blue', 'inhype'),
        'blue' => esc_attr__('Blue', 'inhype'),
        'red' => esc_attr__('Red', 'inhype'),
        'green' => esc_attr__('Green', 'inhype'),
        'orange' => esc_attr__('Orange', 'inhype'),
        'redorange' => esc_attr__('RedOrange', 'inhype'),
        'brown' => esc_attr__('Brown', 'inhype'),
    ),
    'description' => esc_attr__( 'Select one of predefined skins or set your own custom colors.', 'inhype' ),
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'color_darktheme',
    'label'       => esc_attr__('Enable dark theme', 'inhype' ),
    'description' => esc_html__('Use this option if you set dark backgrounds and light colors for texts. You need to set dark Header and Body backgrounds colors manually.', 'inhype'),
    'section'     => 'colors',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_main',
    'label'       => esc_attr__( 'Main theme color', 'inhype' ),
    'description' => esc_attr__( 'Used in multiple theme areas (links, some buttons, etc).', 'inhype' ),
    'section'     => 'colors',
    'default'     => '#2568ef',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_main_alt',
    'label'       => esc_attr__( 'Alternative theme color', 'inhype' ),
    'description' => esc_attr__( 'Used in some theme areas (some buttons, image hover effects).', 'inhype' ),
    'section'     => 'colors',
    'default'     => '#FF3366',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_button',
    'label'       => esc_attr__( 'Buttons background color', 'inhype' ),
    'description' => esc_attr__( 'Used in buttons', 'inhype' ),
    'section'     => 'colors',
    'default'     => '#2568ef',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_button_hover',
    'label'       => esc_attr__( 'Buttons hover background color', 'inhype' ),
    'description' => esc_attr__( 'Used in alternative buttons, buttons hover, that does not use main theme color.', 'inhype' ),
    'section'     => 'colors',
    'default'     => '#48494b',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'color_bodybg_html',
    'label'       => '',
    'section'     => 'colors',
    'default'     => '<div class="kirki-input-container"><label><span class="customize-control-title">Body background color</span><div>You can change it in Theme Settings > General.</div></label></div>',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_text',
    'label'       => esc_attr__( 'Body text color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#333333',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_topmenu_bg',
    'label'       => esc_attr__( 'Top menu background color (light menu)', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#FFFFFF',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_topmenu_dark_bg',
    'label'       => esc_attr__( 'Top menu background color (dark menu)', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#121212',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_link',
    'label'       => esc_attr__( 'Mainmenu link color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_link_hover',
    'label'       => esc_attr__( 'Mainmenu link hover color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#2568ef',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_dark_bg',
    'label'       => esc_attr__( 'Mainmenu dark background color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#121212',
    'description' => esc_attr__( 'Use if you selected dark main menu style.', 'inhype' ),
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_dark_bg_grad',
    'label'       => esc_attr__( 'Mainmenu dark background second color (for gradient)', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#121212',
    'description' => esc_attr__( 'Use if you selected dark main menu style and want to have horizontal gradient in your menu.', 'inhype' ),
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_bg',
    'label'       => esc_attr__( 'Mainmenu submenu background color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#ffffff',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_link',
    'label'       => esc_attr__( 'Mainmenu submenu link color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_link_hover',
    'label'       => esc_attr__( 'Mainmenu submenu link hover color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#2568ef',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_footer_bg',
    'label'       => esc_attr__( 'Footer background color (light footer)', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#FFFFFF',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_footer_dark_bg',
    'label'       => esc_attr__( 'Footer background color (dark footer)', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#13181C',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_post_reading_progressbar',
    'label'       => esc_attr__( 'Post reading progress bar color', 'inhype' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

// END SECTION: Colors

// SECTION: Ads management
Kirki::add_section( 'banners', array(
    'title'          => esc_attr__( 'Banners management', 'inhype' ),
    'description' => esc_attr__( 'You can add any HTML, JavaScript and WordPress shortcodes in this blocks content to show your advertisement. Switch to Text editor mode to add HTML/JavaScript code.', 'inhype' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 180,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_header',
    'label'       => esc_attr__( 'Header banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_header_content',
    'label'       => esc_attr__( 'Header banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed in site header below posts slider.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_inside_header',
    'label'       => esc_attr__( 'Inside Header banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_inside_header_content',
    'label'       => esc_attr__( 'Inside Header banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed inside site header (instead of main menu). Disable Main Menu if you want to use this banner.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_below_header',
    'label'       => esc_attr__( 'Below header banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_below_header_content',
    'label'       => esc_attr__( 'Below header banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed below site header.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_above_footer',
    'label'       => esc_attr__( 'Above footer banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_above_footer_content',
    'label'       => esc_attr__( 'Above footer banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed above site footer.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_footer',
    'label'       => esc_attr__( 'Footer banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_footer_content',
    'label'       => esc_attr__( 'Footer banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed in site footer.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_below_top_menu',
    'label'       => esc_attr__( 'Below header top menu', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_below_top_menu_content',
    'label'       => esc_attr__( 'Below header top menu banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed on homepage below top menu.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_posts_loop_middle',
    'label'       => esc_attr__( 'Posts list middle banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_posts_loop_middle_content',
    'label'       => esc_attr__( 'Posts list middle banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed at the middle between posts on all posts listing pages (Homepage, Archives, Search, etc). This banner does not available in Masonry and Two column blog layouts.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_posts_loop_bottom',
    'label'       => esc_attr__( 'Posts list bottom banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_posts_loop_bottom_content',
    'label'       => esc_attr__( 'Posts list bottom banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed at the bottom after all posts on posts listing pages (Homepage, Archives, Search, etc).', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_single_post_top',
    'label'       => esc_attr__( 'Single post top banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_single_post_top_content',
    'label'       => esc_attr__( 'Single post top banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed on single blog post page between post content and featured image.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_single_post_bottom',
    'label'       => esc_attr__( 'Single post bottom banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_single_post_bottom_content',
    'label'       => esc_attr__( 'Single post bottom banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed on single blog post page after post content.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_404',
    'label'       => esc_attr__( '404 page banner', 'inhype' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_404_content',
    'label'       => esc_attr__( '404 page banner HTML', 'inhype' ),
    'description' => esc_attr__( 'Displayed on 404 not found page.', 'inhype' ),
    'section'     => 'banners',
    'default'     => '',
) );

// SECTION: Support and updates
Kirki::add_section( 'about', array(
    'title'          => esc_attr__( 'Documentation & Support', 'inhype' ),
    'description' => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 300,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'about_support',
    'label'       => '',
    'section'     => 'about',
    'default'     => '<div class="documentation-icon"><a href="http://magniumthemes.com/" target="_blank"><img src="'.get_template_directory_uri().'/img/developer-icon.png" alt="MagniumThemes"/></a></div><p>We recommend you to read <a href="http://magniumthemes.com/go/inhype-docs/" target="_blank">Theme Documentation</a> before you will start using our theme to building your website. It covers all steps for site configuration, demo content import, theme features usage and more.</p>
<p>If you have face any problems with our theme feel free to use our <a href="http://support.magniumthemes.com/" target="_blank">Support System</a> to contact us and get help for free.</p>
<a class="button button-primary" href="http://magniumthemes.com/go/inhype-docs/" target="_blank">Documentation</a> <a class="button button-primary" href="http://support.magniumthemes.com/" target="_blank">Support System</a>
<p><strong>Theme developed by <a href="http://magniumthemes.com/" target="_blank">MagniumThemes</a>.</strong><br/>All rights reserved.</p>
<a class="button button-primary" href="http://magniumthemes.com/themes/" target="_blank">Our WordPress themes</a>',
) );
// END SECTION: Support and updates

// SECTION: Additional JavaScript
Kirki::add_section( 'customjs', array(
    'title'          => esc_attr__( 'Additional JavaScript', 'inhype' ),
    'description'    => '',
    'panel'          => '',
    'priority'       => 200,
) );

Kirki::add_field( 'inhype_theme_options', array(
    'type'        => 'code',
    'settings'    => 'custom_js_code',
    'label'       => esc_attr__( 'Custom JavaScript code', 'inhype' ),
    'description' => esc_attr__( 'This code will run in header, do not add &#x3C;script&#x3E;...&#x3C;/script&#x3E; tags here, this tags will be added automatically. You can use JQuery code here.', 'inhype' ),
    'section'     => 'customjs',
    'default'     => '',
    'choices'     => array(
        'language' => 'js',
    ),
) );

// END SECTION: Additional JavaScript

endif; // check for 'inhype_update'

// Kirki plugin not installed
else:
    add_action( 'admin_notices', 'inhype_kirki_warning' );
endif;

/*
*   Kirki not installed warning display
*/
if (!function_exists('inhype_kirki_warning')) :
function inhype_kirki_warning() {

    $message_html = '<div class="notice notice-error"><p><strong>WARNING:</strong> Please <a href="'.esc_url( admin_url( 'themes.php?page=install-required-plugins&plugin_status=install' ) ).'">install and activate InHype Theme Settings (Kirki Customizer Framework)</a> required plugin, <strong>theme settings will not work without it</strong>.</p></div>';

    echo wp_kses_post($message_html);

}
endif;
