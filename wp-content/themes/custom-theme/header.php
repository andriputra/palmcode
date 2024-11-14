<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header id="main-header">
        <div class="container">
            <div class="navigation-area">
                <?php
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
                    <?php
                }
                ?>
                <button class="hamburger" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="menu">
                    <?php wp_nav_menu(['theme_location' => 'primary']); ?>
                </nav>
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const menu = document.querySelector('.menu');
            const header = document.getElementById('main-header');
            
            // Buka/tutup menu saat hamburger di-klik
            hamburger.addEventListener('click', function() {
                menu.classList.toggle('menu-open');
                hamburger.classList.toggle('is-active');
            });

            // Ubah background header saat scroll
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        });
    </script>
</body>
</html>
