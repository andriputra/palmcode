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

// Form Function
add_action('admin_post_contact_form', 'handle_contact_form_submission');
add_action('admin_post_nopriv_contact_form', 'handle_contact_form_submission');

function handle_contact_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $services = sanitize_text_field($_POST['services']);
        $message = sanitize_textarea_field($_POST['message']);

        // Handle file upload
        if (!empty($_FILES['attachment']['name'])) {
            $uploaded_file = $_FILES['attachment'];
            $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);

            if ($upload && !isset($upload['error'])) {
                $uploaded_file_url = $upload['url'];
            } else {
                wp_die('File upload failed: ' . $upload['error']);
            }
        }

        // Send email (Example)
        $to = get_option('admin_email');
        $subject = 'New Contact Form Submission';
        $body = "Name: $name\nEmail: $email\nPhone: $phone\nService: $services\nMessage:\n$message";
        if (!empty($uploaded_file_url)) {
            $body .= "\nFile: $uploaded_file_url";
        }
        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        if (wp_mail($to, $subject, $body, $headers)) {
            wp_redirect(home_url('/thank-you')); // Redirect to a thank-you page
            exit;
        } else {
            wp_die('Failed to send email.');
        }
    }
}


?>