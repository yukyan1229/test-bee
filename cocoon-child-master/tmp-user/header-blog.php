<?php
// ヘッダー (BLOGコンテキスト用)
?>
<header class="custom-header">
    <!-- Left: LIVE Top Link -->
    <a href="<?php echo home_url('/'); ?>"
        style="display: flex; align-items: center; justify-content: center; height: 44px; padding: 0 10px;">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/beenice_logo_orange.png" alt="bee-nice" style="height: 25px; width: auto; object-fit: contain;">
    </a>

    <!-- Center: Blog Title -->
    <a href="<?php echo home_url('/blog/'); ?>">
        LIFE IS BEAUTIFUL
    </a>

    <!-- Right: X Link -->
    <a href="https://x.com/beenice1969" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter)"
        style="display: flex; align-items: center; justify-content: center; width: 44px; height: 44px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                fill="currentColor" />
        </svg>
    </a>
</header>