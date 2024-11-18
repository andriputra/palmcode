<?php if (is_active_sidebar('footer-widgets')) : ?>
    <div class="footer-widget-area">
        <div class="footer-widgets-container">
            <?php dynamic_sidebar('footer-widgets'); ?>
        </div>
        <div class="copyright">
            <span>Impressum</span>
            <span>•</span>
            <span>Datenschutz</span>
        </div>
    </div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>