<?php
// Custom Header for Child Theme - Integrates Cocoon Logic
if (!defined('ABSPATH'))
    exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php // Cocoon Meta & Analytics ?>
    <meta name="referrer"
        content="<?php echo apply_filters('cocoon_meta_referrer_content', get_meta_referrer_content()); ?>">
    <meta name="format-detection" content="telephone=no">

    <?php cocoon_template_part('tmp/head-analytics'); ?>

    <?php if (has_amp_page()): ?>
        <link rel="amphtml" href="<?php echo get_amp_permalink(); ?>">
    <?php endif ?>

    <?php if (get_google_search_console_id()): ?>
        <meta name="google-site-verification" content="<?php echo get_google_search_console_id() ?>" />
    <?php endif; ?>

    <?php // DNS prefetch ?>
    <?php
    $domains = list_text_to_array(get_pre_acquisition_list());
    if ($domains) {
        foreach ($domains as $domain) {
            echo '<link rel="preconnect dns-prefetch" href="//' . $domain . '">' . PHP_EOL;
        }
    }
    ?>

    <?php
    // Inject Theme Color Variable
    $context = cocoon_child_get_theme_context();
    $theme_color_var = isset($context['color']) ? "var(--color-{$context['color']})" : "var(--color-gray)";
    ?>
    <style>
        :root {
            --current-theme-color:
                <?php echo $theme_color_var; ?>
            ;
        }
    </style>

    <?php wp_head(); ?>

    <?php cocoon_template_part('tmp/head-custom-field'); ?>
    <?php cocoon_template_part('tmp/head-javascript'); ?>
    <?php cocoon_template_part('tmp/head-pwa'); ?>
    <?php cocoon_template_part('tmp-user/head-insert'); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (function_exists('wp_body_open')) {
        wp_body_open();
    } ?>

    <?php // Cocoon Body Analytics (GTM, etc.) ?>
    <?php cocoon_template_part('tmp/body-top-analytics'); ?>

    <?php // Custom Header Layout (Replaces Cocoon's body-top) ?>
    <?php get_template_part('tmp-user/orbit-modal'); ?>
    <?php get_template_part('tmp-user/header', 'custom'); ?>