<?php
// page.php - Detail Page Template
// Handles single static pages (Live Details, Streaming, etc.)

$slug = '';
$cat_name = '';

// Determine Context (Category or Slug)
$cats = get_the_category();
if (!empty($cats)) {
    $slug = $cats[0]->slug;
    $cat_name = $cats[0]->name; // Use Category Name as main title? Or Post Title?
} else {
    $post = get_post();
    $slug = $post->post_name;
    // Special check for 'streaming'
    if ($slug === 'streaming') {
        $cat_name = '配信';
    } else {
        $cat_name = get_the_title();
    }
}

// Color Mapping
$colors = [
    'talk' => 'orange',
    'sakura' => 'pink',
    'nomaki' => 'blue',
    'koishikiuchi' => 'yellow',
    'streaming' => 'green',
];
$color = isset($colors[$slug]) ? $colors[$slug] : 'gray';

// Override Title if it's a known category page acting as detail
$titles = [
    'talk' => 'トークライブ',
    'sakura' => '桜の会',
    'nomaki' => 'の巻',
    'koishikiuchi' => 'コイシキウチ',
    'streaming' => '配信',
];
if (isset($titles[$slug])) {
    $cat_name = $titles[$slug];
}

?>
<?php get_header(); ?>

<style>
    :root {
        --page-color: var(--color-<?php echo $color; ?>);
    }
</style>

<main style="max-width: 1000px; margin: 0 auto; padding-bottom: 4rem; padding-top: 10rem;">

    <!-- Content Loop -->
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>

            <div class="detail-content">
                <!-- Title Section -->
                <div style="text-align: left; margin-bottom: 2rem; color:#666666;">
                    <!-- Optional: NEW Mark logic -->
                    <?php
                    $latest_info = cocoon_child_get_latest_content_info();
                    $latest_id = $latest_info ? $latest_info['id'] : 0;
                    if (get_the_ID() === $latest_id):
                        ?>
                        <span class="new-mark">NEW</span>
                    <?php endif; ?>

                    <!-- Main Title -->
                    <h2 style="font-size: 2rem; margin: 1rem 0; background: none; color: #333; padding: 0;">

                        <?php the_title(); ?>
                    </h2>
                    <!-- Note to User: Use <div class="summary-box">...</div> for the top summary in the editor -->
                </div>
                <!-- Post Content -->
                <?php the_content(); ?>
            </div>
        <?php endwhile; endif; ?>

    <!-- Contact Button (Themed) -->
    <!-- Contact Button (Themed) -->
    <?php
    $allowed_slugs = ['nomaki', 'sakura', 'talk', 'koishikiuchi'];
    if (in_array($slug, $allowed_slugs)):
        ?>
        <div style="text-align: center; margin: 4rem 0;">
            <a href="https://forms.gle/xGF9eTSNafoSH4LM6" target="_blank" rel="noopener noreferrer" title="お問い合わせ"
                aria-label="お問い合わせ" style="
                   display: inline-flex;
                   align-items: center;
                   justify-content: center;
                   width: 80px;
                   height: 80px;
                   background-color: var(--color-<?php echo $color; ?>);
                   border-radius: 50%;
                   text-decoration: none;
                   color: white;
                   transition: transform 0.3s;
           " onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                <span class="material-symbols-outlined" style="font-size: 40px;">mail</span>
            </a>
        </div>
    <?php endif; ?>

    <!-- Vertical Title -->
    <!--
    <div style="
            writing-mode: vertical-rl;
            position: fixed;
            bottom: 0;
            right: 0;
            z-index: 10;
            font-size: min(12vw, 13vh);
            font-weight: 900;
            color: transparent;
            -webkit-text-stroke: 0.03em var(--color-<?php echo $color; ?>);
            letter-spacing: -0.05em;
            line-height: 0.8;
            text-align: right;
            font-family: 'Zen Kaku Gothic New', sans-serif;
            opacity: 1;
            width: 100%;
            white-space: nowrap;
            pointer-events: none;
        ">
        <?php echo $cat_name; ?>
    </div>
    -->
</main>

<?php get_template_part('tmp-user/footer', 'custom'); ?>

<?php wp_footer(); ?>
</body>

</html>