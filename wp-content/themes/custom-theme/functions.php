<?php
function custom_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'custom-theme'),
    ]);
    add_theme_support('custom-logo', [
        'height'      => 100,    
        'width'       => 300,    
        'flex-height' => true,   
        'flex-width'  => true,  
    ]);
    register_sidebar([
        'name'          => __('Footer Widget Area', 'custom-theme'),
        'id'            => 'footer-widget-area',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
}
add_action('after_setup_theme', 'custom_theme_setup');

function custom_theme_enqueue_scripts() {
    wp_enqueue_style('custom-style', get_stylesheet_uri());
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_enqueue_scripts');

function custom_theme_styles() {
    wp_enqueue_style('custom-theme-style', get_template_directory_uri() . '/assets/css/style.css');
    
    $custom_css = "
        .jumbotron {
            background: url('" . get_template_directory_uri() . "/assets/images/content-boxes.png') no-repeat center center;
            background-size: cover;
        }
    ";
    wp_add_inline_style('custom-theme-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'custom_theme_styles');
?>
