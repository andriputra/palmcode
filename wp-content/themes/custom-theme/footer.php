    <footer>
        <?php if (is_active_sidebar('footer-widget-area')) : ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-widget-area'); ?>
            </div>
        <?php endif; ?>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
