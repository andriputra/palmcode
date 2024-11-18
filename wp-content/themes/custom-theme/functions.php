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
        'name'          => __('Footer Widget Area', 'textdomain'),
        'id'            => 'footer-widgets',
        'description'   => __('Widgets added here will appear in the footer.', 'textdomain'),
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('after_setup_theme', 'custom_theme_setup');

function custom_theme_enqueue_scripts() {
    wp_enqueue_style('custom-style', get_stylesheet_uri());
    wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);
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


function register_contact_submission_post_type() {
    register_post_type('contact_submission', [
        'labels' => [
            'name' => 'Contact Submissions',
            'singular_name' => 'Contact Submission',
            'add_new_item' => 'Add New Contact Submission',
            'edit_item' => 'Edit Contact Submission',
            'all_items' => 'All Contact Submissions',
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_rest' => true, // If you want it to be available in the block editor
    ]);
}
add_action('init', 'register_contact_submission_post_type');


add_action('wp_enqueue_scripts', function () {
    
    wp_localize_script('contact-form-script', 'myAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
});


// Form Function
add_action('wp_ajax_nopriv_submit_contact_form', 'handle_contact_form'); // for non-logged-in users
add_action('wp_ajax_submit_contact_form', 'handle_contact_form'); // for logged-in users

function handle_contact_form() {
    // Validate Required Fields
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $services = sanitize_text_field($_POST['services']);
    $message = sanitize_textarea_field($_POST['message']);

    if (empty($name) || empty($email) || empty($phone) || empty($services)) {
        wp_send_json_error(['message' => 'Bitte füllen Sie alle Pflichtfelder aus.']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        wp_send_json_error(['message' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.']);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'contact_form_nonce')) {
        wp_send_json_error(['message' => 'Ungültige Anfrage.']);
    }

    // Handle File Upload
    if (!empty($_FILES['attachment']['name'])) {
        $uploaded_file = $_FILES['attachment'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    
        if (!in_array($uploaded_file['type'], $allowed_types)) {
            wp_send_json_error(['message' => 'Nur Bilddateien (JPEG, PNG, GIF) sind erlaubt.']);
        }
    
        $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);
        if (isset($upload['error'])) {
            wp_send_json_error(['message' => 'Datei-Upload fehlgeschlagen: ' . $upload['error']]);
        }
    
        $attachment_url = $upload['url'];
        $file_path = $upload['file']; // Get the full file path
    
        // Insert the image as an attachment
        $attachment = array(
            'guid' => $upload['url'], 
            'post_mime_type' => $uploaded_file['type'],
            'post_title' => basename($uploaded_file['name']),
            'post_content' => '',
            'post_status' => 'inherit',
        );
        $attachment_id = wp_insert_attachment($attachment, $file_path);
    
        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
    
        // Attach to post
        $attachment_url = wp_get_attachment_url($attachment_id); // Get the URL of the uploaded file
    } else {
        $attachment_url = '';
    }    

    // Create Custom Post Type Entry
    $post_id = wp_insert_post([
        'post_type' => 'contact_submission',
        'post_title' => 'Kontaktanfrage von ' . $name,
        'post_content' => $message,
        'post_status' => 'publish',
        'meta_input' => [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'services' => $services,
            'attachment' => $attachment_url,
        ],
    ]);

    if ($post_id) {
        wp_send_json_success(['message' => 'Ihre Anfrage wurde erfolgreich gesendet.']);
    } else {
        wp_send_json_error(['message' => 'Beim Speichern Ihrer Anfrage ist ein Fehler aufgetreten.']);
    }
}

?>