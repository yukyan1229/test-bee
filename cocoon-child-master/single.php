<?php
// single.php - Standard Blog Post Template with Work Section
get_header(); ?>

<style>
    :root {
        --page-color: var(--color-gray); /* Default tonemanner color for blog */
    }
    .custom-blog-swiper {
        width: 100%;
        height: auto;
        margin: 2rem 0;
        border-radius: 12px;
        overflow: hidden;
    }
    .custom-blog-swiper .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f7f7f7; /* Lighter background for images */
    }
    .custom-blog-swiper .swiper-slide img {
        width: 100%;
        max-height: 70vh;
        object-fit: contain;
    }
    .swiper-pagination-bullet-active {
        background: var(--page-color) !important;
    }
    
    /* Blog post text padding to match design */
    .post-body {
        line-height: 1.8;
        font-size: 1.1rem;
        color: #444;
    }
    
    .work-posts-section {
        padding: 2.5rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin: 4rem auto;
    }
    .work-posts-section h3 {
        text-align: center;
        color: #333;
        font-size: 1.6rem;
        margin-bottom: 2.5rem;
        font-weight: 700;
        letter-spacing: 0.1em;
    }
    .work-group h4 {
        color: #666;
        border-bottom: 2px solid #eee;
        padding-bottom: 0.8rem;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .work-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .work-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed #eee;
    }
    .work-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .work-title {
        font-weight: bold;
        font-size: 1.15rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .work-excerpt {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
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
    <div class="work-posts-section">
        <h3>Information / お仕事情報</h3>
        
        <?php
        // タクソノミー「media_type」（配信媒体）の情報をすべて取得
        $terms = get_terms([
            'taxonomy' => 'media_type',
            'hide_empty' => true, // 投稿がない媒体は非表示
        ]);
        
        $has_works = false;

        if (!empty($terms) && !is_wp_error($terms)) {
            // 各媒体（Netflix, Amazon Primeなど）ごとにループ
            foreach ($terms as $term) {
                // その媒体に属する「お仕事情報(work)」を取得
                $work_args = [
                    'post_type' => 'work',
                    'posts_per_page' => -1, // すべて取得
                    'tax_query' => [
                        [
                            'taxonomy' => 'media_type',
                            'field'    => 'term_id',
                            'terms'    => $term->term_id,
                        ],
                    ],
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ];
                $work_query = new WP_Query($work_args);
                
                if ($work_query->have_posts()) {
                    $has_works = true;
                    echo '<div class="work-group">';
                    echo '<h4>' . esc_html($term->name) . '</h4>';
                    echo '<ul class="work-list">';
                    
                    while ($work_query->have_posts()) {
                        $work_query->the_post();
                        echo '<li class="work-item">';
                        echo '<div class="work-title">' . get_the_title() . '</div>';
                        echo '<div class="work-excerpt">' . get_the_content() . '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
                wp_reset_postdata();
            }
        }

        // もし「media_type」を設定していない（ALLや未分類）お仕事情報があれば、最後にまとめて出す
        $fallback_args = [
            'post_type' => 'work',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            // tax_queryを追加して、「どのmedia_typeにも属していない」投稿を取得することも可能だが
            // シンプルに全件取得して、タクソノミーがない場合のみ表示するように調整する
        ];
        
        if (!$has_works) {
             echo '<p style="text-align:center; color:#999; font-size: 0.9rem;">現在掲載しているお仕事情報はありません。</p>';
        }
        ?>
    </div>

</main>

<!-- カスタムフッターの読み込み -->
<?php get_template_part('tmp-user/footer', 'custom'); ?>

<!-- Swiper初期化用JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // WordPress標準の「ギャラリーブロック」を探す
    const galleries = document.querySelectorAll('.wp-block-gallery');
    
    galleries.forEach((gallery, index) => {
        // ギャラリー内のすべての画像ブロックを取得
        const imageBlocks = gallery.querySelectorAll('.wp-block-image');
        if (imageBlocks.length <= 1) return; // 画像が1枚以下の場合はスライダーにしない
        
        // Swiper 用のHTML構造を新しく作成
        /*
          <div class="swiper custom-blog-swiper">
            <div class="swiper-wrapper">
              <div class="swiper-slide"> <img ...> </div>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        */
        const swiperContainer = document.createElement('div');
        swiperContainer.classList.add('swiper', 'custom-blog-swiper', `swiper-gallery-${index}`);
        
        const swiperWrapper = document.createElement('div');
        swiperWrapper.classList.add('swiper-wrapper');
        
        // 元のギャラリーの直前に Swiper コンテナを挿入
        gallery.parentNode.insertBefore(swiperContainer, gallery);
        
        // 元の画像をスライドの中に移動する
        imageBlocks.forEach(block => {
            const slide = document.createElement('div');
            slide.classList.add('swiper-slide');
            
            const img = block.querySelector('img');
            if (img) {
                // 画像をクローンしてスライドに追加
                slide.appendChild(img.cloneNode(true));
                swiperWrapper.appendChild(slide);
            }
        });
        
        swiperContainer.appendChild(swiperWrapper);
        
        // ページネーション（ドット）を追加
        const pagination = document.createElement('div');
        pagination.classList.add('swiper-pagination');
        swiperContainer.appendChild(pagination);
        
        // 古いギャラリーを画面から隠す（安全のため削除はせず非表示化）
        gallery.style.display = 'none';
        
        // Swiper を起動
        if (typeof Swiper !== 'undefined') {
            new Swiper(`.swiper-gallery-${index}`, {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                grabCursor: true, // ホバー時に指マーク
                // autoHeight: true // 画像の高さがバラバラな場合に自動調整させるなら
            });
        } else {
            console.error('Swiper.js is not loaded.');
            gallery.style.display = ''; // fallback
            swiperContainer.style.display = 'none';
        }
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
