<?php
// single.php - Standard Blog Post Template with Work Section
get_header(); ?>

<style>
    :root {
        --page-color: var(--color-gray); /* Default tonemanner color for blog */
    }

    /* ギャラリーのグリッド表示 (PC3列、スマホ2列) */
    .post-body .wp-block-gallery {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 20px !important;
        margin: 2.5rem 0;
        padding: 0;
    }
    
    .post-body .wp-block-gallery .wp-block-image {
        margin: 0 !important;
        width: 100% !important;
    }
    
    .post-body .wp-block-gallery .wp-block-image img {
        width: 100%;
        height: auto;
    }
    
    @media (max-width: 768px) {
        .post-body .wp-block-gallery {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }
    }
    
    /* Blog post text padding to match design */
    .post-body {
        line-height: 1.8;
        font-size: 1.1rem;
        color: #444;
    }
    
    /* 本文内のテキストリンクに下線を引く */
    .post-body p a, .post-body li a {
        text-decoration: underline !important;
        text-underline-offset: 3px !important;
        color: #333 !important; /* Text color for standard links */
        transition: color 0.2s;
    }
    .post-body p a:hover, .post-body li a:hover {
        color: #dc3545 !important;
        opacity: 1 !important;
    }
</style>

<main style="max-width: 1000px; margin: 0 auto; padding-bottom: 4rem; padding-top: 10rem;">

    <!-- ブログ記事部分 -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="detail-content" style="padding: 0 1rem;">
            <!-- タイトルと日付 -->
            <div style="text-align: left; margin-bottom: 3rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 2rem;">
                <div style="font-size: 0.95rem; margin-bottom: 0.8rem; letter-spacing: 0.05em; color: #888;">
                    <?php the_time('Y.m.d'); ?>
                    <?php 
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<span style="margin-left: 1rem; background: #f0f0f0; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; color: #555; text-decoration: none !important;">' . esc_html($categories[0]->name) . '</span>';
                    }
                    ?>
                </div>
                <h1 style="font-size: 2.2rem; margin: 0; background: none; color: #333; padding: 0; font-weight: bold; line-height: 1.4;">
                    <?php the_title(); ?>
                </h1>
            </div>
            
            <!-- 本文 -->
            <div class="post-body">
                <?php the_content(); ?>
            </div>
        </div>
    <?php endwhile; endif; ?>
    
    <!-- ここから下がお仕事情報の表示部分 -->
    <?php get_template_part('tmp-user/work-info'); ?>

</main>

<!-- カスタムフッターの読み込み -->
<?php get_template_part('tmp-user/footer', 'custom'); ?>

<!-- ギャラリーはCSSのGridレイアウトで 3列 に自動調整されます -->

<?php wp_footer(); ?>
</body>
</html>
