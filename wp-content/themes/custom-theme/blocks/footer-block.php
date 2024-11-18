<?php
function theme_register_footer_block() {
    // Register custom block script and styles
    wp_register_script(
        'footer-block-editor-script',
        get_template_directory_uri() . '/blocks/footer-block/footer-block.js',
        ['wp-blocks', 'wp-editor', 'wp-components', 'wp-element'],
        filemtime(get_template_directory() . '/blocks/footer-block/footer-block.js')
    );

    // Register block
    register_block_type('theme/footer-block', [
        'editor_script' => 'footer-block-editor-script',
        'render_callback' => 'theme_render_footer_block',
    ]);
}
add_action('init', 'theme_register_footer_block');

function theme_render_footer_block($attributes) {
    $content = isset($attributes['content']) ? $attributes['content'] : '';
    return "<div class='footer-block-content'>{$content}</div>";
}

?>
