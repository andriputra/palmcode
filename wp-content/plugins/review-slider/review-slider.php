<?php
/**
 * Plugin Name: Review Slider
 * Description: A plugin to manage and display reviews in a slider. 
 * Version: 2.0.0
 * Author: Agus Andri Putra
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Add custom meta box for star rating
function ts_add_star_rating_meta_box() {
    add_meta_box(
        'ts_star_rating',           // ID of the meta box
        'Star Rating',              // Title of the meta box
        'ts_star_rating_meta_box',  // Callback function to display the meta box content
        'review',                   // Post type
        'normal',                   // Context (normal, side, advanced)
        'high'                      // Priority
    );
}
add_action( 'add_meta_boxes', 'ts_add_star_rating_meta_box' );

// Display the star rating input field in the meta box
function ts_star_rating_meta_box( $post ) {
    // Get the current star rating value
    $star_rating = get_post_meta( $post->ID, 'star_rating', true );
    $star_rating = !empty( $star_rating ) ? $star_rating : 5; // Default to 5 if no rating exists
    ?>
    <label for="star_rating">Star Rating (1 to 5):</label>
    <input type="number" name="star_rating" id="star_rating" value="<?php echo esc_attr( $star_rating ); ?>" min="1" max="5" step="1" style="width: 100px;" />
    <p class="description">Enter a rating between 1 and 5 stars.</p>
    <?php
}

// Save the star rating when the review post is saved
function ts_save_star_rating_meta( $post_id ) {
    // Check if the 'star_rating' field is set in the $_POST array
    if ( isset( $_POST['star_rating'] ) ) {
        // Sanitize and save the star rating value
        $star_rating = intval( $_POST['star_rating'] );
        
        // Make sure the rating is between 1 and 5
        if ( $star_rating >= 1 && $star_rating <= 5 ) {
            update_post_meta( $post_id, 'star_rating', $star_rating );
        }
    }
}
add_action( 'save_post', 'ts_save_star_rating_meta' );

// Register Custom Post Type for Reviews
function ts_register_review_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Review Slider',
            'singular_name' => 'Review',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Review',
            'edit_item' => 'Edit Review',
            'new_item' => 'New Review',
            'view_item' => 'View Review',
            'search_items' => 'Search Review',
            'not_found' => 'No reviews found',
            'not_found_in_trash' => 'No reviews found in trash',
            'all_items' => 'All Review',
            'archives' => 'Review Archives',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
    );
    register_post_type( 'review', $args );
}
add_action( 'init', 'ts_register_review_post_type' );

// Enqueue necessary styles and scripts
function ts_enqueue_styles() {
    wp_enqueue_style( 'review-slider-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
    wp_enqueue_script( 'review-slider-script', plugin_dir_url( __FILE__ ) . 'assets/js/slider.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'ts_enqueue_styles' );

// Create a shortcode to display the review slider
function ts_review_slider_shortcode() {
    // Query the reviews
    $args = array(
        'post_type' => 'review',
        'posts_per_page' => -1,
    );
    $reviews = new WP_Query( $args );

    // Start the slider HTML structure
    $output = '<div class="review-slider">';
    
    // Add navigation buttons outside of the slider wrapper
    $output .= '<button class="prev"></button>';
    $output .= '<button class="next"></button>';
    
    // Start the slider wrapper
    $output .= '<div class="slider-wrapper">';
    if ( $reviews->have_posts() ) {
        while ( $reviews->have_posts() ) {
            $reviews->the_post();

            // Get the star rating value (e.g., 1 to 5), default to 5 if not set
            $star_rating = get_post_meta(get_the_ID(), 'star_rating', true);
            $star_rating = !empty($star_rating) ? intval($star_rating) : 5; // Default to 5 if no rating exists

            $output .= '<div class="slide">';
            $output .= '<div class="card">';
            if ( has_post_thumbnail() ) {
                $output .= '<div class="review-image">' . get_the_post_thumbnail() . '</div>';
            }
            $output .= '<div class="stars">';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $star_rating) {
                    $output .= '<span class="star filled">&#9733;</span>'; // Filled star
                } else {
                    $output .= '<span class="star">&#9734;</span>'; // Empty star
                }
            }
            $output .= '</div>';
            $output .= '<p>' . get_the_content() . '</p>';
            $output .= '<span class="author">' . get_the_title() . '</span>';
            $output .= '</div>'; 
            $output .= '</div>'; 
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No reviews found.</p>';
    }
    $output .= '</div>'; 
    $output .= '</div>'; 

    return $output;
}

add_shortcode( 'review_slider', 'ts_review_slider_shortcode' );

// Add Admin Menu
function ts_review_menu() {
    add_menu_page( 'Review Slider', 'Review Slider Use', 'manage_options', 'review-slider', 'ts_review_page', 'dashicons-format-quote', 20 );
}
add_action( 'admin_menu', 'ts_review_menu' );

// Review page content for Admin
function ts_review_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline" style="font-size: 2rem; font-weight: 600; margin-bottom: 20px; color: #333;">Review Slider Use</h1>
        
        <div class="ts-admin-description" style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
            <h2 style="font-size: 1.5rem; color: #0073aa; font-weight: bold;">Welcome to Review Slider Settings</h2>
            <p style="font-size: 1.1rem; color: #555; line-height: 1.6;">Here you can easily add and manage your reviews using the custom post type <strong>"Review"</strong>.</p>
            
            <div class="ts-steps" style="margin-top: 20px;">
                <h3 style="font-size: 1.3rem; color: #333;">Steps to get started:</h3>
                <ul style="font-size: 1rem; color: #555; list-style-type: disc; padding-left: 20px;">
                    <li><strong>Step 1:</strong> Add new reviews using the custom post type <strong>"Review"</strong>.</li>
                    <li><strong>Step 2:</strong> Use the shortcode <code>[review_slider]</code> on any page or post to display the reviews.</li>
                    <li><strong>Step 3:</strong> Customize the slider's appearance using your theme's CSS if needed.</li>
                </ul>
            </div>

            <div class="ts-note" style="margin-top: 20px; background-color: #f0f8ff; border-left: 5px solid #0073aa; padding: 15px; font-size: 1rem; color: #333;">
                <strong>Note:</strong> You can adjust the number of visible reviews in the slider and configure the transition speed in the settings of the slider JavaScript file.
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 1rem; color: #777;">
            <p>For more advanced settings and customizations, refer to the plugin documentation or visit the plugin's official page.</p>
        </div>
    </div>
    <?php
}