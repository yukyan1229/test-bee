<?php
/*
Template Name: Streaming Page
*/

// Set color for this template
$color = 'green';
$cat_name = '配信';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>配信 - LIVEのお知らせ</title>
    <?php wp_head(); ?>
    <style>
        .streaming-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 600px;
            margin: 4rem auto;
            padding: 0 1rem;
        }

        .streaming-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            border: 1px solid var(--color-green);
            border-radius: 50px;
            /* Pill shape */
            text-decoration: none;
            color: var(--color-green);
            background: transparent;
            transition: all 0.3s ease;
        }

        .streaming-card:hover {
            background-color: var(--color-green);
            color: #fff;
            transform: scale(1.02);
        }

        .service-name {
            font-size: 1.2rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            font-family: var(--font-title);
        }

        .arrow-icon {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .intro-text {
            text-align: center;
            margin-top: 2rem;
            font-size: 1rem;
            line-height: 2;
            font-family: serif;
            font-style: italic;
            color: #666;
        }
    </style>
</head>

<body <?php body_class(); ?>>

    <?php get_template_part('tmp-user/orbit-modal'); ?>
    <?php get_template_part('tmp-user/header', 'custom'); ?>

    <main style="max-width: 1000px; margin: 0 auto; padding-bottom: 4rem; padding-top: 8rem;">

        <!-- Content from Editor (Optional Intro Text) -->
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <div class="intro-text">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>

        <!-- Streaming Links (Hardcoded Layout) -->
        <div class="streaming-container">
            <!-- Apple Music -->
            <a href="https://music.apple.com/jp/artist/%E6%9C%A8%E5%86%85%E7%A7%80%E4%BF%A1/340336614" target="_blank"
                rel="noopener noreferrer" class="streaming-card" title="Streaming: Apple Music">
                <span class="service-name">Apple Music</span>
                <svg class="arrow-icon" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Amazon Music -->
            <a href="https://music.amazon.co.jp/artists/B08NJ32WBS/%25E6%259C%25A8%25E5%2586%2585%25E7%25A7%2580%25E4%25BF%25A1"
                target="_blank" rel="noopener noreferrer" class="streaming-card" title="Streaming: Amazon Music">
                <span class="service-name">Amazon Music</span>
                <svg class="arrow-icon" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Spotify -->
            <a href="https://open.spotify.com/intl-ja/artist/6YCd58s2AOxXvgOxpp4CE6" target="_blank"
                rel="noopener noreferrer" class="streaming-card" title="Streaming: Spotify">
                <span class="service-name">Spotify</span>
                <svg class="arrow-icon" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <!-- YouTube Music -->
            <a href="https://music.youtube.com/search?q=%E6%9C%A8%E5%86%85%E7%A7%80%E4%BF%A1" target="_blank"
                rel="noopener noreferrer" class="streaming-card" title="Streaming: YouTube Music">
                <span class="service-name">YouTube Music</span>
                <svg class="arrow-icon" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
        </div>




        <!-- Vertical Title -->
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
    </main>

    <footer class="custom-footer">
        &copy;
        <?php echo date('Y'); ?> bee-nice.
    </footer>

    <?php wp_footer(); ?>
</body>

</html>