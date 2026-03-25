<?php
// home.php - Blog Posts Index Template
get_header(); 
?>

<style>
    :root {
        --page-color: var(--color-gray);
    }
    .blog-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .blog-item {
        text-align: left;
        margin-bottom: 2rem;
    }
    .blog-item-date {
        font-size: 0.95rem;
        color: #888;
        margin-bottom: 0.3rem;
        letter-spacing: 0.05em;
    }
    .blog-item-title {
        font-size: 1.3rem;
        font-weight: 500;
        margin: 0;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #ddd;
        background: transparent !important; /* Force override */
        line-height: 1.5;
    }
    .blog-item-title a {
        color: #333 !important;
        text-decoration: none;
        transition: color 0.2s;
    }
    .blog-item-title a:hover {
        color: #dc3545 !important;
        opacity: 1;
    }
    .pagination {
        margin-top: 4rem;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .pagination .page-numbers {
        display: inline-block;
        padding: 8px 16px;
        background: #f7f7f7;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        transition: background 0.2s;
    }
    .pagination .page-numbers:hover {
        background: #eee;
    }
    .pagination .current {
        background: var(--page-color);
        color: #fff;
    }
</style>

<main style="max-width: 1000px; margin: 0 auto; padding-bottom: 4rem; padding-top: 10rem; padding-left: 1rem; padding-right: 1rem;">
    <div class="detail-content">
        
        <!-- ブログトップ タイトル -->
        <h1 style="font-size: 2.2rem; margin-bottom: 3rem; display: block; color: #333; font-weight: bold; letter-spacing: 0.1em; text-align: left;">
            BLOG
        </h1>
        
        <?php if (have_posts()) : ?>
            <ul class="blog-list">
                <?php while (have_posts()) : the_post(); ?>
                    <li class="blog-item">
                        <div class="blog-item-date"><?php the_time('Y.m.d'); ?></div>
                        <div class="blog-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            
            <!-- ページネーション -->
            <div class="pagination">
                <?php 
                echo paginate_links([
                    'prev_text' => '&laquo; 前へ',
                    'next_text' => '次へ &raquo;',
                    'type'      => 'plain',
                ]); 
                ?>
            </div>
            
        <?php else : ?>
            <p style="text-align:center; color:#999; padding: 4rem 0;">記事がありません。</p>
        <?php endif; ?>

        <!-- お仕事情報の表示部分 -->
        <?php get_template_part('tmp-user/work-info'); ?>

    </div>
</main>

<?php get_template_part('tmp-user/footer', 'custom'); ?>

<?php wp_footer(); ?>
</body>
</html>
