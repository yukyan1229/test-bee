<?php
// front-page.php
?>
<?php get_header(); ?>


<div class="home-container">
    <main class="home-main"
        style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; max-width: 1000px; margin: 0 auto; padding: 0;">
        <h1 id="top-title" class="main-title" style="
            margin-bottom: 4vh;
            text-align: center;
            width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        ">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/beenice_logo.png" alt="bee-nice"
                style="width: 100%; max-width: 300px; height: auto;">
        </h1>

        <div id="top-menu-container" class="menu-hidden"
            style="display: flex; flex-wrap: wrap; justify-content: center; max-width: 800px; gap: 10px;">
            <?php
            // Get Latest Content Info for Dynamic Border
            $latest_info = cocoon_child_get_latest_content_info();
            $latest_cat_slug = $latest_info ? $latest_info['category'] : '';

            // Menu Items Configuration
            $menu_items = [
                [
                    'slug' => 'talk',
                    'type' => 'category',
                    'char' => 'ト',
                    'color' => 'orange',
                    'link' => home_url('/talk/'),
                    'delay' => '0s',
                    'has_border' => ($latest_cat_slug === 'talk')
                ],
                [
                    'slug' => 'sakura',
                    'type' => 'category',
                    'char' => '桜',
                    'color' => 'pink',
                    'link' => home_url('/sakura/'),
                    'delay' => '-0.5s',
                    'has_border' => ($latest_cat_slug === 'sakura')
                ],
                [
                    'slug' => 'nomaki',
                    'type' => 'category',
                    'char' => 'の',
                    'color' => 'blue',
                    'link' => home_url('/nomaki/'),
                    'delay' => '-1s',
                    'has_border' => ($latest_cat_slug === 'nomaki')
                ],
                [
                    'slug' => 'koishikiuchi',
                    'type' => 'category',
                    'char' => 'コ',
                    'color' => 'yellow',
                    'link' => home_url('/koishikiuchi/'),
                    'delay' => '-1.5s',
                    'has_border' => ($latest_cat_slug === 'koishikiuchi')
                ],
                [
                    'slug' => 'streaming',
                    'type' => 'page',
                    'char' => '配',
                    'color' => 'green',
                    'link' => home_url('/streaming/'),
                    'delay' => '-2s',
                    'has_border' => false
                ],
                /*
                [
                    'slug' => 'goods',
                    'type' => 'external',
                    'char' => 'グ',
                    'color' => 'red',
                    'link' => 'https://bee6940.base.shop/',
                    'delay' => '-2.5s',
                    'has_border' => false,
                    'target' => '_blank'
                ],
                */
                [
                    'slug' => 'blog',
                    'type' => 'external',
                    'char' => 'B',
                    'color' => 'gray',
                    'link' => 'https://bee-nice.jugem.jp/',
                    'delay' => '-3s',
                    'has_border' => false,
                    'target' => '_blank'
                ]
            ];

            // Render loop
            foreach ($menu_items as $item):
                // Check Visibility
                if (!cocoon_child_is_menu_item_visible($item['slug'], $item['type'])) {
                    continue; // Skip if no content
                }

                $target_attr = isset($item['target']) ? ' target="' . $item['target'] . '" rel="noopener noreferrer"' : '';
                ?>
                <div class="menu-item-wrapper" style="animation-delay: <?php echo $item['delay']; ?>;">
                    <a href="<?php echo $item['link']; ?>" class="circle-btn theme-<?php echo $item['slug']; ?>"
                        style="--item-color: var(--color-<?php echo $item['color']; ?>); background-color: var(--item-color);"
                        <?php echo $target_attr; ?>>
                        <?php if ($item['has_border']): ?>
                            <div class="rainbow-border"></div>
                        <?php endif; ?>
                        <span class="date"><?php echo $item['char']; ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php get_template_part('tmp-user/footer', 'custom'); ?>
</div>

<?php wp_footer(); ?>
</body>

</html>