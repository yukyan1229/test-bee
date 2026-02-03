<?php
/*
Template Name: Simple Page (Privacy Policy, etc.)
*/

// Set Layout/Theme Color to Gray (Top Page Theme Color)
$color = 'gray';
?>
<?php get_header(); ?>

<style>
    :root {
        /* Set content color to match Top Page Theme (Gray) */
        --page-color: var(--color-<?php echo $color; ?>);
    }

    /* Force Header to match the theme color for this template */
    .custom-header {
        background-color: var(--page-color) !important;
    }
</style>

<!-- Increased top padding to account for fixed header, but less than Detail Page which has orbit -->
<main style="max-width: 1000px; margin: 0 auto; padding-bottom: 4rem; padding-top: 8rem;">

    <!-- Content Loop -->
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>

            <div class="detail-content">
                <!-- Title Section -->
                <div style="text-align: left; margin-bottom: 2rem; color:#666666;">
                    <!-- Main Title (Simple H1, no background) -->
                    <h1
                        style="font-size: 2rem; margin: 1rem 0; padding: 0.5rem 0; color: #333; font-weight: bold; border: none; background: none;">
                        <?php the_title(); ?>
                    </h1>
                </div>

                <!-- Post Content -->
                <?php the_content(); ?>
            </div>
        <?php endwhile;
    endif; ?>

</main>

<?php get_template_part('tmp-user/footer', 'custom'); ?>

<?php wp_footer(); ?>
</body>

</html>