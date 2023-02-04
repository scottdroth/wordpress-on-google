<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);

require 'inc/pm_functions.php';


function callback($buffer)
{
    $buffer = str_replace('src="https://www.pastemagazine.com', 'src="https://cdn.pastemagazine.com', $buffer);
    $buffer = str_replace('srcset="https://www.pastemagazine.com', 'srcset="https://cdn.pastemagazine.com', $buffer);
    $buffer = str_replace('srcset="https://www.pastemagazine.com/wp-content/uploads/', 'srcset="https://cdn.pastemagazine.com/wp-content/uploads/', $buffer);
    
    
    //$buffer = str_replace('href="https://www-wp.pastemagazine.com/wp-content/cache/fvm/', 'href="https://cdn.pastemagazine.com/wp-content/cache/fvm/', $buffer);
    
    
    //$buffer = str_replace('https://cdn.pastemagazine.com/wp-content/themes/pastemagazine/style.css', 'https://www-wp.pastemagazine.com/wp-content/themes/pastemagazine/style.css', $buffer);
    
    return $buffer;
}

function buffer_start()
{
    ob_start("callback");
}

function buffer_end()
{
    ob_end_flush();
}

// if (!is_admin()) {
//     add_action('wp_loaded', 'buffer_start');
//     add_action('shutdown', 'buffer_end');
// }

function pm_setup()
{
    load_theme_textdomain(
        'pastemagazine',
        get_template_directory() . '/languages'
    );
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'navigation-widgets']);
    //add_theme_support('woocommerce');

    global $content_width;

    if (!isset($content_width)) {
        $content_width = 1920;
    }

    register_nav_menus([
        'header-menu' => esc_html__('Header Menu', 'pastemagazine'),
    ]);

    /* register_nav_menus([
        'main-menu' => esc_html__('Main Menu', 'pastemagazine'),
    ]); */

    register_nav_menus([
        'footer-menu' => esc_html__('Footer Menu', 'pastemagazine'),
    ]);
}
add_action('after_setup_theme', 'pm_setup');


/**
 * WP - Load CSS Asynchronously
 * Eliminate blocking-resources
 */
/* function ct_style_loader_tag($html, $handle)
{
    $async_loading = array(
        'pastemagazine-old_inline-style',
        'pastemagazine-old_stylesheet-style'
    );
    if (in_array($handle, $async_loading)) {
        $async_html = str_replace("rel='stylesheet'", "rel='preload' as='style'", $html);
        $async_html .= str_replace('media=\'all\'', 'media="print" onload="this.media=\'all\'"', $html);
        return $async_html;
    }
    return $html;
}
add_filter('style_loader_tag', 'ct_style_loader_tag', 10, 2); */


function pm_enqueue()
{
    wp_enqueue_style(
        'pastemagazine-old_inline-style',
        get_template_directory_uri() . '/css/inline-39.2022.10209.11821.css',
        //'https://cdn.pastemagazine.com/wp-content/staging/themes/pastemagazine/css/inline-39.2022.10209.11821.css',
        '',
        microtime()
    );

    wp_enqueue_style(
        'pastemagazine-old_stylesheet-style',
        get_template_directory_uri() . '/css/stylesheet-39.2022.10209.11821.css',
        //'https://cdn.pastemagazine.com/wp-content/staging/themes/pastemagazine/css/stylesheet-39.2022.10209.11821.css',
        '',
        microtime()
    );

    wp_enqueue_style(
        'pastemagazine-theme-style',
        get_stylesheet_uri(),
        '',
        microtime()
    );

    /*https://cdn.pastemagazine.com/wp-content/prod/themes/pastemagazine/css/stylesheet-39.2022.10209.11821.css
    https://cdn.pastemagazine.com/wp-content/prod/themes/pastemagazine/css/stylesheet-39.2022.10209.11821.css
    https://cdn.pastemagazine.com/wp-content/staging/themes/pastemagazine/css/stylesheet-39.2022.10209.11821.css

    https://cdn.pastemagazine.com/wp-content/staging/themes/pastemagazine/css/stylesheet-39.2022.10209.11821.css*/


    /* wp_enqueue_style(
        'pm-jplayer',
        //get_template_directory_uri() . '/css/pink.flag/css/jplayer.pink.flag.css',
        get_template_directory_uri() . '/css/blue.monday/css/jplayer.blue.monday.min.css',
        '',
        microtime()
    ); */


    wp_enqueue_script('jquery');

    //wp_enqueue_script('pm-js', get_template_directory_uri() . '/js/javascript.js', array(), microtime(), false);
    //wp_enqueue_script('foundation', get_template_directory_uri() . '/js/00-03-foundation.js', array(), microtime(), false);
    //wp_enqueue_script('jplayer', get_template_directory_uri() . '/js/00-03-jquery.jplayer.min.js', array(), microtime(), false);

    //wp_enqueue_script('pm-home', get_template_directory_uri() . '/js/home.js', array(), microtime(), false);

    //wp_enqueue_script('pm-player', get_template_directory_uri() . '/js/player.js', array(), microtime(), false);
    //wp_enqueue_script('pm-player-single', get_template_directory_uri() . '/js/02-02-article-detail.js', array(), microtime(), false);
    //wp_enqueue_script('pm-js', get_template_directory_uri() . '/js/javascript-39.2022.10209.11821.js', array(), microtime(), false);

    //wp_enqueue_script('pm-ads', get_template_directory_uri() . '/js/ads.js', array(), microtime(), false);
    if (is_singular('article')) {

        global $post;


        if (stristr($post->post_content, 'audio-playlist-embed') || stristr($post->post_content, 'data-noisetrade=')) {
            wp_enqueue_script('jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js', array(), uniqid(), false);
            wp_enqueue_script('audio', get_template_directory_uri() . '/js/audio.js', array(), uniqid(), true);
        }


        if (stristr($post->post_content, 'twitter-tweet')) {
            wp_enqueue_script('twitter', 'https://platform.twitter.com/widgets.js', array(), uniqid(), true);
        }
    }

    //wp_enqueue_script('pm-ad-commands', get_template_directory_uri() . '/js/pm_ad_commands.js', array(), uniqid(), false);
    wp_enqueue_script('sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array(), uniqid(), false);
    wp_enqueue_script('pm-custom', get_template_directory_uri() . '/js/pm_custom.js', array(), uniqid(), false);
}
add_action('wp_enqueue_scripts', 'pm_enqueue');

function defer_parsing_of_js($script_html)
{
    if (is_user_logged_in()) return $script_html; //don't break WP Admin
    if (FALSE === strpos($script_html, '.js')) return $script_html;
    if (strpos($script_html, 'jquery.js') || strpos($script_html, 'jquery.min.js')) return $script_html;

    $script_html = str_replace(' src', ' defer src', $script_html);
    /*$script_html = strstr($script_html, 'twitter') ? "<script>
                                                        setTimeout(function() {
                                                            var headID = document.getElementsByTagName('head')[0];         
                                                            var newScript = document.createElement('script');
                                                            newScript.type = 'text/javascript';
                                                            newScript.src = 'https://platform.twitter.com/widgets.js';
                                                            headID.appendChild(newScript);
                                                        }, 3000);
                                                        </script>
                                                        " : "";*/
    return $script_html;
}
add_filter('script_loader_tag', 'defer_parsing_of_js', 11, 1);

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);


function pm_admin_enqueue()
{
    wp_enqueue_style(
        'pm-admin-styles',
        get_stylesheet_directory_uri() . '/css/admin_styles.css',
        '',
        microtime()
    );
}
add_action('admin_enqueue_scripts', 'pm_admin_enqueue');

function pm_footer()
{
?>
<script>
jQuery(document).ready(function($) {
    var deviceAgent = navigator.userAgent.toLowerCase();
    if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
        $("html").addClass("ios");
        $("html").addClass("mobile");
    }
    if (deviceAgent.match(/(Android)/)) {
        $("html").addClass("android");
        $("html").addClass("mobile");
    }
    if (navigator.userAgent.search("MSIE") >= 0) {
        $("html").addClass("ie");
    } else if (navigator.userAgent.search("Chrome") >= 0) {
        $("html").addClass("chrome");
    } else if (navigator.userAgent.search("Firefox") >= 0) {
        $("html").addClass("firefox");
    } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
        $("html").addClass("safari");
    } else if (navigator.userAgent.search("Opera") >= 0) {
        $("html").addClass("opera");
    }
});
</script>
<?php
}
add_action('wp_footer', 'pm_footer');

function pm_document_title_separator($sep)
{
    $sep = '|';
    return $sep;
}
add_filter('document_title_separator', 'pm_document_title_separator');

function pm_title($title)
{
    if ($title == '') {
        return '...';
    } else {
        return $title;
    }
}
add_filter('the_title', 'pm_title');

function pm_schema_type()
{
    $schema = 'https://schema.org/';
    if (is_single()) {
        $type = 'Article';
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . $schema . $type . '"';
}

function pm_schema_url($atts)
{
    $atts['itemprop'] = 'url';
    return $atts;
}
add_filter('nav_menu_link_attributes', 'pm_schema_url', 10);

if (!function_exists('pm_wp_body_open')) {
    function pm_wp_body_open()
    {
        do_action('wp_body_open');
    }
}

function pm_skip_link()
{
    echo '<a href="#content" class="skip-link screen-reader-text">' .
        esc_html__('Skip to the content', 'pastemagazine') .
        '</a>';
}
add_action('wp_body_open', 'pm_skip_link', 5);

function pm_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' .
            esc_url(get_permalink()) .
            '" class="more-link">' .
            sprintf(
                __('...%s', 'pastemagazine'),
                '<span class="screen-reader-text">  ' .
                    esc_html(get_the_title()) .
                    '</span>'
            ) .
            '</a>';
    }
}
add_filter('the_content_more_link', 'pm_read_more_link');

function pm_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' .
            esc_url(get_permalink($post->ID)) .
            '" class="more-link">' .
            sprintf(
                __('...%s', 'pastemagazine'),
                '<span class="screen-reader-text">  ' .
                    esc_html(get_the_title()) .
                    '</span>'
            ) .
            '</a>';
    }
}
add_filter('excerpt_more', 'pm_excerpt_read_more_link');



function pm_widgets_init()
{
    register_sidebar([
        'name' => esc_html__('Sidebar Widget Area', 'pastemagazine'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'pm_widgets_init');