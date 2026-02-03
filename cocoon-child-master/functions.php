<?php //子テーマ用関数
if (!defined('ABSPATH'))
    exit;

function cocoon_child_setup()
{
    //子テーマ用のビジュアルエディタースタイルを適用
    // add_editor_style(); // style.cssの opacity: 0 がエディタに適用されてしまうため無効化
}
add_action('after_setup_theme', 'cocoon_child_setup');

//以下に子テーマ用の関数を書く

// 1. 固定ページでカテゴリーを使えるようにする
function cocoon_child_add_category_to_page()
{
    register_taxonomy_for_object_type('category', 'page');
}
add_action('init', 'cocoon_child_add_category_to_page');

// 2. カテゴリーアーカイブに固定ページを含める
function cocoon_child_add_page_to_category_archive($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->is_category()) {
        $query->set('post_type', array('post', 'page'));
    }
}
add_action('pre_get_posts', 'cocoon_child_add_page_to_category_archive');

// 3. スクリプトとスタイルの読み込み
function cocoon_child_enqueue_scripts()
{
    // スタイル (style.cssはCocoonが自動で読み込むが、念のため依存関係を明確にする)
    // wp_enqueue_style( 'cocoon-child-style', get_stylesheet_uri(), array('cocoon-style') );

    // Google Fonts
    wp_enqueue_style('google-fonts-zen', 'https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap', array(), null);
    wp_enqueue_style('material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0', array(), null);

    // JS
    wp_enqueue_script('site-script', get_stylesheet_directory_uri() . '/script.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'cocoon_child_enqueue_scripts');

// 4. コンテキスト判定用関数 (LIVE vs BLOG)
function cocoon_child_is_live_context()
{
    // フロントページ、詳細ページ(固定ページ)、特定のカテゴリー(LIVE関連)の場合はLIVEコンテキスト
    if (is_front_page() || is_page()) {
        return true;
    }
    // LIVE関連カテゴリーのスラッグリスト
    $live_categories = array('talk', 'sakura', 'nomaki', 'koishikiuchi', 'streaming');
    if (is_category($live_categories)) {
        return true;
    }

    return false;
}

// 5. メニュー項目の表示判定 (投稿がない場合は非表示)
function cocoon_child_is_menu_item_visible($slug, $type = 'category')
{
    // 外部サイトなどは常に表示
    if ($type === 'external' || $slug === 'blog' || $slug === 'goods') {
        return true;
    }

    // カテゴリーの場合：投稿数(固定ページ含む)が1以上か判定
    if ($type === 'category') {
        // term->count は「投稿」のみで固定ページを含まない場合があるため、WP_Queryで実数チェック
        $args = [
            'post_type' => ['post', 'page'],
            'category_name' => $slug,
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'fields' => 'ids', // IDのみ取得で高速化
            'no_found_rows' => true // 行数計算不要
        ];
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            return true;
        }
    }

    // 固定ページの場合 (streamingなど)：公開済みページが存在するか
    if ($type === 'page') {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page && $page->post_status === 'publish') {
            return true;
        }
    }

    return false;
}

// 6. 本文内のURLを自動でリンクにする
add_filter('the_content', 'make_clickable');

// 7. 特定カテゴリ(fixed page)のURLを /{slug}/{page_name}/ に変更
function cocoon_child_hierarchical_page_link($link, $post)
{
    if ($post->post_type !== 'page') {
        return $link;
    }

    $target_slugs = ['talk', 'sakura', 'nomaki', 'koishikiuchi'];
    // ページに設定されたカテゴリを取得 (get_the_categoryよりもterms取得が確実)
    $cats = get_the_terms($post->ID, 'category');
    if ($cats && !is_wp_error($cats)) {
        foreach ($cats as $c) {
            if (in_array($c->slug, $target_slugs)) {
                // /{slug}/{pagename}/ 形式にして返す (/category/ を省略)
                return home_url('/' . $c->slug . '/' . $post->post_name . '/');
            }
        }
    }
    return $link;
}
add_filter('post_type_link', 'cocoon_child_hierarchical_page_link', 10, 2);

// 8. リライトルールの追加 (上のURLでアクセスできるようにする)
function cocoon_child_add_rewrite_rules()
{
    $target_slugs = ['talk', 'sakura', 'nomaki', 'koishikiuchi'];
    $slug_regex = '(' . implode('|', $target_slugs) . ')';

    // 1. 詳細ページルール: /CategorySLUG/PageSLUG/ -> index.php?pagename=PageSLUG (具体的なルールを優先)
    add_rewrite_rule(
        '^' . $slug_regex . '/([^/]+)/?$',
        'index.php?pagename=$matches[2]&post_type=page',
        'top'
    );

    // 2. 各カテゴリのアーカイブページルール: /CategorySLUG/ -> index.php?category_name=CategorySLUG
    add_rewrite_rule(
        '^' . $slug_regex . '/?$',
        'index.php?category_name=$matches[1]',
        'top'
    );
}
// 9. 古いURL(フラット)から新しいURL(階層)へリダイレクト
function cocoon_child_redirect_to_hierarchical_url()
{
    if (!is_page()) {
        return;
    }

    $post = get_post();
    $target_slugs = ['talk', 'sakura', 'nomaki', 'koishikiuchi'];

    // 現在のページがターゲット区分のカテゴリを持っているか確認
    $cats = get_the_terms($post->ID, 'category');
    if ($cats && !is_wp_error($cats)) {
        foreach ($cats as $c) {
            if (in_array($c->slug, $target_slugs)) {

                // 正しいURL (階層あり) を生成
                // 例: https://example.com/sakura/sakura-06/
                $correct_url = home_url('/' . $c->slug . '/' . $post->post_name . '/');

                // 現在のアクセスURLと比較
                // $_SERVER['REQUEST_URI'] は /sakura-06/ など
                // $correct_url からドメインを除いたパスを作成して比較推奨だが、
                // 単純に get_permalink() が新ルールで正しく生成されているなら、それと比較すればよい

                // 現在のリクエストURI(ドメインなし)
                $current_uri = $_SERVER['REQUEST_URI'];

                // 正しいURLのパス部分
                $correct_path = parse_url($correct_url, PHP_URL_PATH);

                // スラッシュの有無で誤動作しないよう、trimして比較
                if (trim($current_uri, '/') !== trim($correct_path, '/')) {
                    wp_safe_redirect($correct_url, 301);
                    exit;
                }
            }
        }
    }
}
add_action('template_redirect', 'cocoon_child_redirect_to_hierarchical_url');
// 10. 最新記事情報の取得 (虹色ボーダー用)
function cocoon_child_get_latest_content_info()
{
    // ターゲットカテゴリ (固定ページも含む)
    $target_slugs = ['talk', 'sakura', 'nomaki', 'koishikiuchi'];

    $args = [
        'post_type' => ['post', 'page'],
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $target_slugs,
            ],
        ],
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $categories = get_the_category();
        $cat_slug = '';

        // ターゲットカテゴリに一致するものを探す
        if ($categories) {
            foreach ($categories as $cat) {
                if (in_array($cat->slug, $target_slugs)) {
                    $cat_slug = $cat->slug;
                    break;
                }
            }
        }

        wp_reset_postdata();

        return [
            'id' => $post_id,
            'category' => $cat_slug
        ];
    }

    return null;
}
// 11. フッターメニューの登録
function cocoon_child_register_menus()
{
    register_nav_menu('footer-custom-menu', 'Custom Footer Menu');
}
add_action('init', 'cocoon_child_register_menus');
add_action('init', 'cocoon_child_add_rewrite_rules');