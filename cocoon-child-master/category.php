<?php
// category.php - Archive for Live Categories
// Maps categories to colors and displays child Pages as a grid

$cat = get_queried_object();
$slug = $cat->slug;
$cat_name = $cat->name;

// Color Mapping
$colors = [
    'talk' => 'orange',
    'sakura' => 'pink',
    'nomaki' => 'blue',
    'koishikiuchi' => 'yellow',
    'streaming' => 'green',
];
$color = isset($colors[$slug]) ? $colors[$slug] : 'gray';

// Define Orbit Items for Modal (Dynamic Active State)
$orbit_items = [
    ['slug' => 'talk', 'char' => 'ト', 'color' => 'orange', 'link' => home_url('/talk/')],
    ['slug' => 'sakura', 'char' => '桜', 'color' => 'pink', 'link' => home_url('/sakura/')],
    ['slug' => 'nomaki', 'char' => 'の', 'color' => 'blue', 'link' => home_url('/nomaki/')],
    ['slug' => 'koishikiuchi', 'char' => 'コ', 'color' => 'yellow', 'link' => home_url('/koishikiuchi/')],
    ['slug' => 'streaming', 'char' => '配', 'color' => 'green', 'link' => home_url('/streaming/')],
    // ['slug' => 'goods', 'char' => 'グ', 'color' => 'red', 'link' => 'https://bee6940.base.shop/', 'target' => '_blank'],
    ['slug' => 'blog', 'char' => 'B', 'color' => 'gray', 'link' => 'https://bee-nice.jugem.jp/', 'target' => '_blank'],
];

// Determine Center Item from Slug (approximate)
$active_item = null;
foreach ($orbit_items as $item) {
    if ($item['slug'] === $slug) {
        $active_item = $item;
        break;
    }
}
// Default if not found (e.g. uncategorized)
if (!$active_item) {
    $active_item = ['char' => mb_substr($cat_name, 0, 1), 'color' => $color, 'link' => '#'];
}
?>
<?php get_header(); ?>

<main style="text-align: center;">
    <!-- Grid -->
    <div style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
            justify-items: center;
            margin-top: 4rem;
        ">
        <?php
        // Get Latest Content Info for Dynamic Border
        $latest_info = cocoon_child_get_latest_content_info();
        $latest_id = $latest_info ? $latest_info['id'] : 0;

        if (have_posts()): ?>
            <?php while (have_posts()):
                the_post();
                $is_latest = (get_the_ID() === $latest_id);
                ?>
                <a href="<?php the_permalink(); ?>" class="circle-btn"
                    style="background-color: var(--color-<?php echo $color; ?>); width: 70px; height: 70px;">
                    <?php if ($is_latest): ?>
                        <div class="rainbow-border"></div>
                    <?php endif; ?>
                    <span class="date">
                        <?php
                        // Extract suffix from slug (e.g. 'sakura-01' -> '01', 'talk_05' -> '05')
                        // Logic: Split by '-' or '_', take the last part.
                        $post_slug = get_post()->post_name;
                        $parts = preg_split('/[-_]/', $post_slug);
                        $display_text = end($parts);

                        // If split failed or only one part, maybe use title or check formatting
                        // Assuming format is PREFIX_NUMBER or PREFIX-NUMBER
                        echo esc_html($display_text);
                        ?>
                    </span>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>記事が見つかりませんでした。</p>
        <?php endif; ?>
    </div>

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
        <?php single_cat_title(); ?>
    </div>
</main>

<?php get_template_part('tmp-user/footer', 'custom'); ?>

<?php wp_footer(); ?>
</body>

</html>