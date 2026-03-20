<?php
// page-blog.php - Blog Archive Page Template (Automatically applies to page with slug "blog")
get_header(); 

// ブログ記事（post）の取得用カスタムクエリ
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'paged'          => $paged
);
$blog_query = new WP_Query($args);
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
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px dashed #eee;
    }
    .blog-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .blog-item-date {
        font-size: 0.9em;
        color: #888;
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
    }
    .blog-item-title {
        font-size: 1.6rem;
        font-weight: bold;
        margin: 0 0 1rem 0;
        line-height: 1.4;
    }
    .blog-item-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.2s;
    }
    .blog-item-title a:hover {
        color: var(--page-color);
        opacity: 0.7;
    }
    .blog-item-excerpt {
        color: #666;
        line-height: 1.6;
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
        
        <?php if ($blog_query->have_posts()) : ?>
            <ul class="blog-list">
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <li class="blog-item">
                        <div class="blog-item-date"><?php the_time('Y.m.d'); ?></div>
                        <h2 class="blog-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="blog-item-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            
            <!-- ページネーション -->
            <div class="pagination">
                <?php 
                echo paginate_links([
                    'total'     => $blog_query->max_num_pages,
                    'current'   => $paged,
                    'prev_text' => '&laquo; 前へ',
                    'next_text' => '次へ &raquo;',
                    'type'      => 'plain',
                ]); 
                ?>
            </div>
            
            <?php wp_reset_postdata(); ?>
            
        <?php else : ?>
            <p style="text-align:center; color:#999; padding: 4rem 0;">記事がありません。</p>
        <?php endif; ?>

    </div>
</main>

<?php get_template_part('tmp-user/footer', 'custom'); ?>

<?php wp_footer(); ?>
</body>
</html>
