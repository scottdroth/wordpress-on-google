<?php





/**
 * Admin speed filters
 */

add_filter('media_library_months_with_files', function () {
    return [];
});

add_action('admin_init', 'pm_remove_yoast_seo_admin_filters', 20);
function pm_remove_yoast_seo_admin_filters()
{
    global $wpseo_meta_columns;
    if ($wpseo_meta_columns) {
        remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown'));
        remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown_readability'));
    }
}

/* add_filter('wp_all_import_curl_download_only', 'wpai_wp_all_import_curl_download_only', 10, 1);

function wpai_wp_all_import_curl_download_only($download_only)
{
    return true;
} */

/* function asdasd()
{
    global $wp_query, $wp_rewrite;
    echo '<pre>';
    //var_dump($wp_query);
    var_dump($wp_rewrite);
    echo '</pre>';
    die;
} */
//add_action('wp_head', 'asdasd');

/** 
 * Includes
 */

//include 'pm_lead_image_metabox.php';
include 'pm_options_fn.php';
include 'pm_article_index_table_fn.php';
include 'pm_cpt_tax_fn.php';
include 'pm_shortcodes.php';
include 'pm_articles_fn.php';
include 'pm_search_fn.php';


function pm_var_dump($string, $echo = true, $die = true)
{

    ob_start();
    echo '<pre>';
    var_dump($string);
    echo '</pre>';
    $result = ob_get_clean();

    if ($echo) echo $result;
    else return $result;

    if ($die) die();
}


function pm_var_export($string, $echo = true, $die = true)
{

    ob_start();
    echo '<pre>';
    var_export($string);
    echo '</pre>';
    $result = ob_get_clean();

    if ($echo) echo $result;
    else return $result;

    if ($die) die();
}


function pm_globals()
{

    global $wpdb, $global_article_category, $global_article_type, $detect;

    require_once 'Mobile_Detect.php';
    $detect = new Mobile_Detect;

    $article_cats = $wpdb->get_results("SELECT t.name, t.slug FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'article-category'");
    $article_types = $wpdb->get_results("SELECT t.name, t.slug FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'article-type'");

    if ($article_cats) {
        foreach ($article_cats as $aC) {
            $global_article_category[$aC->slug] = $aC->name;
        }
    }

    if ($article_types) {
        foreach ($article_types as $aT) {
            $global_article_type[$aT->slug] = $aT->name;
        }
    }
}
add_action('init', 'pm_globals', 1);



function pm_set_user_roles()
{

    global $wp_roles; // global class wp-includes/capabilities.php

    foreach (['administrator', 'editor', 'author'] as $role) {

        $wp_roles->add_cap($role, 'read_article');
        $wp_roles->add_cap($role, 'publish_articles');
        $wp_roles->add_cap($role, 'edit_article');
        $wp_roles->add_cap($role, 'edit_articles');
        $wp_roles->add_cap($role, 'edit_published_articles');
        $wp_roles->add_cap($role, 'delete_article');
        $wp_roles->add_cap($role, 'delete_articles');
        $wp_roles->add_cap($role, 'delete_published_articles');
    }

    foreach (['administrator', 'editor'] as $role) {

        $wp_roles->add_cap($role, 'read_private_articles');
        $wp_roles->add_cap($role, 'edit_others_articles');
        $wp_roles->add_cap($role, 'delete_others_articles');
    }

    $wp_roles->add_cap('editor', 'list_users');
    $wp_roles->add_cap('editor', 'create_users');
}
add_action('admin_init', 'pm_set_user_roles');


function pm_add_users_article_column($columns)
{
    $columns["articles"] = "Articles";
    return $columns;
}
add_filter('manage_users_columns', 'pm_add_users_article_column');


function pm_add_users_article_column_content($value, $column_name, $user_id)
{
    if ($column_name == "articles") {
        global $wpdb;
        $article_count = (int) $wpdb->get_var("SELECT COUNT(ID) FROM pm_article_index WHERE post_author = $user_id");
        return $article_count;
    }
}
add_action('manage_users_custom_column', 'pm_add_users_article_column_content', 10, 3);





function remove_menu_links()
{

    if (!current_user_can('administrator')) {
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit.php?post_type=acf-field-group');
        remove_menu_page('upload.php');
        remove_menu_page('index.php');
        //remove_menu_page( 'profile.php' );
        remove_menu_page('options-general.php');
        remove_menu_page('wpseo_workouts');

        remove_submenu_page('edit.php?post_type=article', 'edit-tags.php?taxonomy=article-tag&amp;post_type=article');
    }
}
add_action('admin_menu', 'remove_menu_links', 9999);



function pm_upload_dir_filter($uploads)
{
    $day = date('d');
    $uploads['path'] .= '/' . $day;
    $uploads['url']  .= '/' . $day;
    return $uploads;
}
add_filter('upload_dir', 'pm_upload_dir_filter');



function pm_remove_default_images($sizes)
{
    unset($sizes['small']); // 150px
    unset($sizes['medium']); // 300px
    unset($sizes['large']); // 1024px
    unset($sizes['medium_large']); // 768px
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'pm_remove_default_images');


add_filter('big_image_size_threshold', '__return_false');


function pm_has_tag(string $tag_slug, array $term_objects)
{

    foreach ($term_objects as $t) {

        if ($t->slug === $tag_slug) return true;
    }

    return false;
}



/* Add custom rewrite rules */

add_action('init', function () {
    flush_rewrite_rules();

    add_rewrite_rule('articles/article-type/(.*)/page/(\d*)$', 'index.php?post_type=articles&article-type=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule('articles/article-type/(.*)$', 'index.php?post_type=articles&article-type=$matches[1]', 'top');

    add_rewrite_rule(
        'articles/(.*)/(.*)/page/(\d*)$',
        'index.php?post_type=articles&article-category=$matches[1]&article-type=$matches[2]&paged=$matches[3]',
        'top'
    );
    add_rewrite_rule(
        'articles/(.*)/(.*)$',
        'index.php?post_type=articles&article-category=$matches[1]&article-type=$matches[2]',
        'top'
    );


    add_rewrite_rule(
        '^studio/page/(\d*)$',
        'index.php?post_type=articles&article-type=studio&paged=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^studio$',
        'index.php?post_type=articles&article-type=studio',
        'top'
    );


    $articleCategoryPattern = '(books|music|movies|tv|comedy|games|food|drink|travel|tech|crawdaddy|business|visual-arts|comics|design|olympics|politics|science|soccer|style|wrestling|theatre|health|media)';

    add_rewrite_rule(
        '^' . $articleCategoryPattern . '$',
        'index.php?post_type=articles&article-category=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^' . $articleCategoryPattern . '/page/(\d*)$',
        'index.php?post_type=articles&article-category=$matches[1]&paged=$matches[2]',
        'top'
    );
});


function pm_print_authors($authors_array, $link = false)
{

    $output = '';

    $c = count($authors_array);
    $i = 1;

    foreach ($authors_array as $id => $aData) {

        list($display_name, $nicename) = explode('|', $aData);

        $output .= $link ? '<a href="/author/' . $nicename . '">' : '';
        $output .= $display_name;
        $output .= $link ? '</a>' : '';
        $output .= $i < $c ? ' and ' : ', ';

        $i++;
    }

    return rtrim($output, ', ');
}





function filter_posts_request($request)
{


    global $current_user, $wpdb, $wp_query;


    /**
     * ARTICLE TYPE & ARTICLE CATEGORY CUSTOM ARCHIVE
     */

    if (
        !is_admin()
        && $GLOBALS['wp_query']->is_main_query()
        && (isset($GLOBALS['wp_query']->query['article-category']) || isset($GLOBALS['wp_query']->query['article-type']))
    ) {
        return "SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID > 1 LIMIT 1"; // return some dummy query to prevent 404
    }

    if (
        is_admin()
        && $GLOBALS['wp_query']->is_main_query()
        && ((isset($GLOBALS['wp_query']->query['post_type']) && $GLOBALS['wp_query']->query['post_type'] == 'articles') || (isset($_GET['post_type']) && $_GET['post_type'] == 'article'))
        && !stristr($request, 'acf-field')
    ) {
    }

    /**
     * FRONT-END SEARCH
     */

    if (
        !is_admin()
        && $GLOBALS['wp_query']->is_main_query()
        && (is_search() || is_page('search'))
    ) {
        //wp_die("here");
        return "SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID > 1 LIMIT 1"; // return some dummy query to prevent 404
    }


    if (
        !is_admin()
        && $GLOBALS['wp_query']->is_main_query()
        && is_author()
    ) {
        //SELECT SQL_CALC_FOUND_ROWS wppm_posts.* FROM wppm_posts WHERE 1=1 AND (wppm_posts.post_author = 47) AND ((wppm_posts.post_type = 'post' AND (wppm_posts.post_status = 'publish' OR wppm_posts.post_status = 'acf-disabled' OR wppm_posts.post_status = 'private'))) ORDER BY wppm_posts.post_date DESC LIMIT 5, 5
        return "SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID > 1 LIMIT 1"; // return some dummy query to prevent 404
    }

    return $request;
}
add_filter('posts_request', 'filter_posts_request', 10, 1);



function _pm_w3tc_flush_url()
{
    if (!isset($_GET['pm_w3_flush'])) return;

    $url = home_url() . preg_replace('/(\?.*)/', '', $_SERVER['REQUEST_URI']);

    w3tc_flush_url($url);
}
add_action('wp_head', '_pm_w3tc_flush_url');




add_action('register_new_user', function () {
    remove_action('register_new_user', 'wp_send_new_user_notifications');
}, 9);




function pm_yoast_metadesc($desc)
{

    global $post;

    if (empty($desc) && has_term('audio', 'article-type', $post)) {
        $artist = wp_get_object_terms($post->ID, 'artist')[0]->name;
        return "Listen to a free exclusive performance from $artist recorded at Daytrotter.";
    }

    if (empty($desc) && has_term('studio', 'article-type', $post)) {
        $artist = wp_get_object_terms($post->ID, 'artist')[0]->name;
        return "Watch a free exclusive live performance from $artist from the Paste Studio.";
    }

    return $desc;
}
add_filter('wpseo_metadesc', 'pm_yoast_metadesc');