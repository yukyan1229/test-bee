<style>
    .work-posts-section {
        padding: 2.5rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin: 4rem auto 0;
    }
    .work-posts-section h3 {
        text-align: center;
        color: #333;
        font-size: 1.6rem;
        margin-bottom: 2rem;
        font-weight: 700;
        letter-spacing: 0.1em;
    }
    
    /* Tabs System */
    .work-tabs {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }
    .work-tab-btn {
        padding: 8px 20px;
        border: 2px solid transparent;
        background: #f0f0f0;
        border-radius: 30px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: bold;
        color: #666;
        transition: all 0.2s ease;
    }
    .work-tab-btn:hover {
        background: #e0e0e0;
    }
    .work-tab-btn.active {
        background: var(--page-color, #333);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .work-tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .work-tab-pane.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* List Items */
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
    
    /* WORKS（お仕事情報）内のテキストリンクに下線を引く */
    .work-excerpt a {
        text-decoration: underline !important;
        text-underline-offset: 3px !important;
        color: #333 !important;
        transition: color 0.2s;
    }
    .work-excerpt a:hover {
        color: #dc3545 !important;
        opacity: 1 !important;
    }
</style>

<div class="work-posts-section">
    <h3>WORKS</h3>
    
    <?php
    $terms = get_terms([
        'taxonomy' => 'media_type',
        'hide_empty' => true,
    ]);
    
    $has_works = false;

    if (!empty($terms) && !is_wp_error($terms)) {
        // ------------------------------
        // タブボタンの生成
        // ------------------------------
        echo '<div class="work-tabs">';
        $index = 0;
        foreach ($terms as $term) {
            $active_class = ($index === 0) ? 'active' : '';
            echo '<button class="work-tab-btn ' . esc_attr($active_class) . '" data-target="work-tab-' . $term->term_id . '">';
            echo esc_html($term->name);
            echo '</button>';
            $index++;
        }
        echo '</div>';
        
        // ------------------------------
        // タブコンテンツの生成
        // ------------------------------
        echo '<div class="work-tab-contents">';
        $index = 0;
        foreach ($terms as $term) {
            $work_args = [
                'post_type' => 'work',
                'posts_per_page' => -1,
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
                $active_class = ($index === 0) ? 'active' : '';
                
                echo '<div class="work-tab-pane ' . esc_attr($active_class) . '" id="work-tab-' . $term->term_id . '">';
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
            $index++;
        }
        echo '</div>'; // End .work-tab-contents
    }

    if (!$has_works) {
         echo '<p style="text-align:center; color:#999; font-size: 0.9rem;">現在掲載しているお仕事情報はありません。</p>';
    }
    ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.work-tab-btn');
    const panes = document.querySelectorAll('.work-tab-pane');
    
    // タブクリック時の処理
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            
            // 全てのタブとコンテンツのアクティブ状態を解除
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));
            
            // クリックされたタブと、対応するコンテンツをアクティブに
            this.classList.add('active');
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});
</script>
