<?php
/*
Plugin Name: Custom Contact Form Plugin
Description: A plugin that adds a custom contact form and saves data to a custom post type in WordPress.
Version: 1.0
Author: Agus Andri Putra
*/

// Register the custom post type
function create_contact_form_post_type() {
    register_post_type('contact_form_entries', array(
        'labels' => array(
            'name' => __('Contact Form Entries'),
            'singular_name' => __('Contact Form Entry'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
    ));
}
add_action('init', 'create_contact_form_post_type');

// Shortcode to display the contact form
function contact_form_shortcode() {
    ob_start();
    ?>
    <form id="contactForm" class="contact-form" enctype="multipart/form-data">
        <?php wp_nonce_field('contact_form_nonce', 'nonce'); ?>
        <div class="form-group">
            <input type="text" name="name" placeholder="Name*" required>
            <input type="email" name="email" placeholder="E-Mail*" required>
        </div>
        <div class="form-group">
            <input type="tel" name="phone" placeholder="Telefonnummer*" required>
            <select name="services" required>
                <option value="" disabled selected>Services*</option>
                <option value="Service 1">Service 1</option>
                <option value="Service 2">Service 2</option>
                <option value="Service 3">Service 3</option>
            </select>
        </div>
        <textarea name="message" placeholder="Nachricht"></textarea>
        <div class="file-upload">
            <label for="file">
                Dateien hochladen (nur Bilddateien erlaubt)
                <input type="file" id="file" name="attachment" accept="image/*">
            </label>
            <div id="fileNameDisplay" style="margin-top: 10px; font-style: italic; color: #333;"></div>
        </div>
        <p class="disclaimer">
            Mit (<span class="required">*</span>) markierte Felder sind Pflichtfelder.<br>
            Mit dem Absenden bestätige ich die <a href="#" target="_blank">Datenschutzinformation</a> gelesen zu haben und bestätige diese.
        </p>
        <div id="formMessage"></div>
        <button type="submit" class="button-cta">JETZ ANFRAGEN</button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_contact_form', 'contact_form_shortcode');

// Enqueue styles and scripts
function enqueue_contact_form_assets() {
    wp_enqueue_style('contact-form-style', plugin_dir_url(__FILE__) . 'assets/css/contact-form-style.css');
    wp_enqueue_script('contact-form-script', plugin_dir_url(__FILE__) . 'assets/js/contact-form.js', ['jquery'], null, true);
    wp_localize_script('contact-form-script', 'myAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_contact_form_assets');

// Handle AJAX submission
if (!function_exists('handle_contact_form_submission')) {
    function handle_contact_form_submission() {
        error_log('AJAX handler called');
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'contact_form_nonce')) {
            wp_send_json_error(['message' => 'Nonce verification failed.']);
            return;
        }

        error_log(print_r($_POST, true));

        // Validate and sanitize inputs
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $services = sanitize_text_field($_POST['services']);
        $message = sanitize_textarea_field($_POST['message']);

        // Handle file upload
        $attachment_url = '';
        if (!empty($_FILES['attachment']['name'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            $uploaded_file = wp_handle_upload($_FILES['attachment'], array('test_form' => false));

            if (isset($uploaded_file['error'])) {
                wp_send_json_error(['message' => 'File upload error: ' . $uploaded_file['error']]);
            }
            $attachment_url = $uploaded_file['url'];
        }

        // Save data to custom post type
        $post_data = array(
            'post_title'    => $name,
            'post_content'  => $message,
            'post_type'     => 'contact_form_entries',
            'post_status'   => 'publish',
            'meta_input'    => array(
                'email'         => $email,
                'phone'         => $phone,
                'services'      => $services,
                'attachment_url' => $attachment_url,
            ),
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_send_json_error(['message' => 'Failed to save entry.']);
        }

        wp_send_json_success(['message' => 'Form submitted successfully!']);
    }
}

add_action('wp_ajax_handle_contact_form_submission', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_handle_contact_form_submission', 'handle_contact_form_submission');
