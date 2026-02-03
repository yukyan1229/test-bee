<?php
// Custom Footer with WP Menu and Copyright
?>
<footer class="custom-footer">
    <!-- Footer Menu -->
    <?php
    wp_nav_menu(array(
        'theme_location' => 'footer-custom-menu',
        'container' => 'nav',
        'container_class' => 'footer-menu-container',
        'menu_class' => 'footer-menu-items',
        'fallback_cb' => false, // Do not show default page list if no menu assigned
    ));
    ?>

    <!-- Copyright -->
    <div class="footer-copyright">
        &copy;
        <?php echo date('Y'); ?> bee-nice.
    </div>
</footer>