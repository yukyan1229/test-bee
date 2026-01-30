<?php
// Orbit Modal Template Part
// Logic to determine active item and render the menu

// Determine Context
$slug = '';
if (is_category()) {
    $slug = get_queried_object()->slug;
} elseif (is_page()) {
    $cats = get_the_category();
    if (!empty($cats)) {
        $slug = $cats[0]->slug;
    } else {
        $post = get_post();
        $slug = $post->post_name; // e.g. 'streaming'
    }
} elseif (is_front_page()) {
    // Front page doesn usually show orbit modal, but in case
}

// Orbit Items Configuration
$orbit_items_raw = [
    ['slug' => 'talk', 'type' => 'category', 'char' => 'ト', 'color' => 'orange', 'link' => home_url('/talk/')],
    ['slug' => 'sakura', 'type' => 'category', 'char' => '桜', 'color' => 'pink', 'link' => home_url('/sakura/')],
    ['slug' => 'nomaki', 'type' => 'category', 'char' => 'の', 'color' => 'blue', 'link' => home_url('/nomaki/')],
    ['slug' => 'koishikiuchi', 'type' => 'category', 'char' => 'コ', 'color' => 'yellow', 'link' => home_url('/koishikiuchi/')],
    ['slug' => 'streaming', 'type' => 'page', 'char' => '配', 'color' => 'green', 'link' => home_url('/streaming/')],
    // ['slug' => 'goods', 'type' => 'external', 'char' => 'グ', 'color' => 'red', 'link' => 'https://bee6940.base.shop/', 'target' => '_blank'],
    ['slug' => 'blog', 'type' => 'external', 'char' => 'B', 'color' => 'gray', 'link' => 'https://bee-nice.jugem.jp/', 'target' => '_blank'],
];

// 1. Determine Active Item
$active_item = null;
foreach ($orbit_items_raw as $item) {
    if ($item['slug'] === $slug) {
        $active_item = $item;
        break;
    }
}
if (!$active_item) {
    $active_item = ['slug' => 'unknown', 'char' => '?', 'color' => 'gray', 'link' => '#'];
}

// 2. Filter Items for Orbit Ring (Remove Active AND Empty Categories)
$ring_items = array_filter($orbit_items_raw, function ($i) use ($active_item) {
    // Exclude self
    if ($i['slug'] === $active_item['slug'])
        return false;

    // Check Visibility (Post Count etc.)
    return cocoon_child_is_menu_item_visible($i['slug'], $i['type']);
});

// Re-index array
$ring_items = array_values($ring_items);

// 3. Calculate Layout
$count = count($ring_items);
$angle_step = ($count > 0) ? 360 / $count : 0;
?>

<!-- Pizza Slice Trigger -->
<div class="orbit-trigger" style="background-color: var(--color-<?php echo $active_item['color']; ?>);">
    <div class="orbit-trigger-icon">Menu</div>
</div>

<!-- Orbit Modal -->
<div id="orbit-modal-overlay" class="orbit-modal-overlay"></div>
<div class="orbit-modal">
    <div class="orbit-modal-close">&times;</div>
    <div class="orbit-header-container">
        <!-- Central Circle -->
        <a href="<?php echo $active_item['link']; ?>" class="central-circle"
            style="background-color: var(--color-<?php echo $active_item['color']; ?>);">
            <span style="font-size: 1.5rem;"><?php echo $active_item['char']; ?></span>
        </a>

        <!-- Orbiting Items -->
        <div class="orbit-rotator">
            <?php
            $current_angle = 0;
            // To ensure 12 o'clock start or appropriate relative positioning?
            // Existing CSS starts at 0 deg (3 o'clock usually unless rotated container).
            // Let's stick to simple distribution.
            
            foreach ($ring_items as $item):
                $target_attr = isset($item['target']) ? ' target="' . $item['target'] . '" rel="noopener noreferrer"' : '';
                ?>
                <div class="orbit-wrapper"
                    style="transform: rotate(<?php echo $current_angle; ?>deg) translate(120px) rotate(-<?php echo $current_angle; ?>deg);">
                    <a href="<?php echo $item['link']; ?>" class="orbit-item"
                        style="background-color: var(--color-<?php echo $item['color']; ?>);" <?php echo $target_attr; ?>>
                        <div class="orbit-item-content"><?php echo $item['char']; ?></div>
                    </a>
                </div>
                <?php
                $current_angle += $angle_step;
            endforeach;
            ?>
        </div>
    </div>
</div>