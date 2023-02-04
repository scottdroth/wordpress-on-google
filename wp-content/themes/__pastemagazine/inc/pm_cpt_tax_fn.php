<?php


// Register Custom Post Type
function pm_articles_post_type()
{
    $labels = [
        'name' => _x('Articles', 'Post Type General Name', 'pastemagazine'),
        'singular_name' => _x(
            'Article',
            'Post Type Singular Name',
            'pastemagazine'
        ),
        'menu_name' => __('Articles', 'pastemagazine'),
        'name_admin_bar' => __('Articles', 'pastemagazine'),
        'archives' => __('Article Archives', 'pastemagazine'),
        'attributes' => __('Article Attributes', 'pastemagazine'),
        'parent_item_colon' => __('Parent Article:', 'pastemagazine'),
        'all_items' => __('All Articles', 'pastemagazine'),
        'add_new_item' => __('Add New Article', 'pastemagazine'),
        'add_new' => __('Add New', 'pastemagazine'),
        'new_item' => __('New Article', 'pastemagazine'),
        'edit_item' => __('Edit Article', 'pastemagazine'),
        'update_item' => __('Update Article', 'pastemagazine'),
        'view_item' => __('View Article', 'pastemagazine'),
        'view_items' => __('View Articles', 'pastemagazine'),
        'search_items' => __('Search Article', 'pastemagazine'),
        'not_found' => __('Not found', 'pastemagazine'),
        'not_found_in_trash' => __('Not found in Trash', 'pastemagazine'),
        'featured_image' => __('Featured Image', 'pastemagazine'),
        'set_featured_image' => __('Set featured image', 'pastemagazine'),
        'remove_featured_image' => __('Remove featured image', 'pastemagazine'),
        'use_featured_image' => __('Use as featured image', 'pastemagazine'),
        'insert_into_item' => __('Insert into article', 'pastemagazine'),
        'uploaded_to_this_item' => __(
            'Uploaded to this article',
            'pastemagazine'
        ),
        'items_list' => __('Articles list', 'pastemagazine'),
        'items_list_navigation' => __(
            'Articles list navigation',
            'pastemagazine'
        ),
        'filter_items_list' => __('Filter articles list', 'pastemagazine'),
    ];
    $rewrite = [
        'slug' => 'article',
        'with_front' => false,
        'pages' => true,
        'feeds' => false,
    ];
    $args = [
        'label' => __('Article', 'pastemagazine'),
        'description' => __('Articles', 'pastemagazine'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'author'],
        'taxonomies' => ['article-category', 'article-type', 'artist'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-list-view',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'page',
        'map_meta_cap' => true,
        'capabilities' => [
            'read_post' => 'read_article',
            'publish_posts' => 'publish_articles',
            'edit_posts' => 'edit_articles',
            'edit_published_posts' => 'edit_published_articles',
            'edit_others_posts' => 'edit_others_articles',
            'delete_posts' => 'delete_articles',
            'delete_others_posts' => 'delete_others_articles',
            'read_private_posts' => 'read_private_articles',
            'edit_post' => 'edit_article',
            'delete_post' => 'delete_article',
            'delete_published_posts' => 'delete_published_articles',
        ],
    ];
    register_post_type('article', $args);
}
add_action('init', 'pm_articles_post_type', 0);

// Register Article Category Custom Taxonomy
function pm_article_category()
{
    $labels = [
        'name' => _x(
            'Article Categories',
            'Taxonomy General Name',
            'pastemagazine'
        ),
        'singular_name' => _x(
            'Article Category',
            'Taxonomy Singular Name',
            'pastemagazine'
        ),
        'menu_name' => __('Article Category', 'pastemagazine'),
        'all_items' => __('All Items', 'pastemagazine'),
        'parent_item' => __('Parent Item', 'pastemagazine'),
        'parent_item_colon' => __('Parent Item:', 'pastemagazine'),
        'new_item_name' => __('New Item Name', 'pastemagazine'),
        'add_new_item' => __('Add New Item', 'pastemagazine'),
        'edit_item' => __('Edit Item', 'pastemagazine'),
        'update_item' => __('Update Item', 'pastemagazine'),
        'view_item' => __('View Item', 'pastemagazine'),
        'separate_items_with_commas' => __(
            'Separate items with commas',
            'pastemagazine'
        ),
        'add_or_remove_items' => __('Add or remove items', 'pastemagazine'),
        'choose_from_most_used' => __(
            'Choose from the most used',
            'pastemagazine'
        ),
        'popular_items' => __('Popular Items', 'pastemagazine'),
        'search_items' => __('Search Items', 'pastemagazine'),
        'not_found' => __('Not Found', 'pastemagazine'),
        'no_terms' => __('No items', 'pastemagazine'),
        'items_list' => __('Items list', 'pastemagazine'),
        'items_list_navigation' => __('Items list navigation', 'pastemagazine'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'capabilities' => array(
            'manage_terms' => 'manage_options',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_articles'
        )
    ];
    register_taxonomy('article-category', ['article'], $args);
}
add_action('init', 'pm_article_category', 1);

// Register Article Type Custom Taxonomy
function pm_article_type()
{
    $labels = [
        'name' => _x('Article Types', 'Taxonomy General Name', 'pastemagazine'),
        'singular_name' => _x(
            'Article Type',
            'Taxonomy Singular Name',
            'pastemagazine'
        ),
        'menu_name' => __('Article Type', 'pastemagazine'),
        'all_items' => __('All Items', 'pastemagazine'),
        'parent_item' => __('Parent Item', 'pastemagazine'),
        'parent_item_colon' => __('Parent Item:', 'pastemagazine'),
        'new_item_name' => __('New Item Name', 'pastemagazine'),
        'add_new_item' => __('Add New Item', 'pastemagazine'),
        'edit_item' => __('Edit Item', 'pastemagazine'),
        'update_item' => __('Update Item', 'pastemagazine'),
        'view_item' => __('View Item', 'pastemagazine'),
        'separate_items_with_commas' => __(
            'Separate items with commas',
            'pastemagazine'
        ),
        'add_or_remove_items' => __('Add or remove items', 'pastemagazine'),
        'choose_from_most_used' => __(
            'Choose from the most used',
            'pastemagazine'
        ),
        'popular_items' => __('Popular Items', 'pastemagazine'),
        'search_items' => __('Search Items', 'pastemagazine'),
        'not_found' => __('Not Found', 'pastemagazine'),
        'no_terms' => __('No items', 'pastemagazine'),
        'items_list' => __('Items list', 'pastemagazine'),
        'items_list_navigation' => __('Items list navigation', 'pastemagazine'),
    ];

    /* $rewrite = array(
        'slug'                       => 'articles/article-type',
        'with_front'                 => false,
        'hierarchical'               => false,
    ); */

    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        //'rewrite'                    => $rewrite,
        'capabilities' => array(
            'manage_terms' => 'manage_options',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_articles'
        )
    ];
    register_taxonomy('article-type', ['article'], $args);
}
add_action('init', 'pm_article_type', 2);

// Register Artist Custom Taxonomy
function pm_article_artist()
{
    $labels = [
        'name' => _x('Artists', 'Taxonomy General Name', 'pastemagazine'),
        'singular_name' => _x(
            'Artist',
            'Taxonomy Singular Name',
            'pastemagazine'
        ),
        'menu_name' => __('Artist', 'pastemagazine'),
        'all_items' => __('All Items', 'pastemagazine'),
        'parent_item' => __('Parent Item', 'pastemagazine'),
        'parent_item_colon' => __('Parent Item:', 'pastemagazine'),
        'new_item_name' => __('New Item Name', 'pastemagazine'),
        'add_new_item' => __('Add New Item', 'pastemagazine'),
        'edit_item' => __('Edit Item', 'pastemagazine'),
        'update_item' => __('Update Item', 'pastemagazine'),
        'view_item' => __('View Item', 'pastemagazine'),
        'separate_items_with_commas' => __(
            'Separate items with commas',
            'pastemagazine'
        ),
        'add_or_remove_items' => __('Add or remove items', 'pastemagazine'),
        'choose_from_most_used' => __(
            'Choose from the most used',
            'pastemagazine'
        ),
        'popular_items' => __('Popular Items', 'pastemagazine'),
        'search_items' => __('Search Items', 'pastemagazine'),
        'not_found' => __('Not Found', 'pastemagazine'),
        'no_terms' => __('No items', 'pastemagazine'),
        'items_list' => __('Items list', 'pastemagazine'),
        'items_list_navigation' => __('Items list navigation', 'pastemagazine'),
    ];

    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'meta_box_cb' => false,
        'capabilities' => array(
            'manage_terms' => 'manage_options',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_articles'
        )
    ];
    register_taxonomy('artist', ['article'], $args);
}
add_action('init', 'pm_article_artist', 3);

// Register Artist Custom Taxonomy
function pm_article_tag()
{
    $labels = [
        'name' => _x('Article Tags', 'Taxonomy General Name', 'pastemagazine'),
        'singular_name' => _x(
            'Article Tag',
            'Taxonomy Singular Name',
            'pastemagazine'
        ),
        'menu_name' => __('Article Tags', 'pastemagazine'),
        'all_items' => __('All Items', 'pastemagazine'),
        'parent_item' => __('Parent Item', 'pastemagazine'),
        'parent_item_colon' => __('Parent Item:', 'pastemagazine'),
        'new_item_name' => __('New Item Name', 'pastemagazine'),
        'add_new_item' => __('Add New Item', 'pastemagazine'),
        'edit_item' => __('Edit Item', 'pastemagazine'),
        'update_item' => __('Update Item', 'pastemagazine'),
        'view_item' => __('View Item', 'pastemagazine'),
        'separate_items_with_commas' => __(
            'Separate items with commas',
            'pastemagazine'
        ),
        'add_or_remove_items' => __('Add or remove items', 'pastemagazine'),
        'choose_from_most_used' => __(
            'Choose from the most used',
            'pastemagazine'
        ),
        'popular_items' => __('Popular Items', 'pastemagazine'),
        'search_items' => __('Search Items', 'pastemagazine'),
        'not_found' => __('Not Found', 'pastemagazine'),
        'no_terms' => __('No items', 'pastemagazine'),
        'items_list' => __('Items list', 'pastemagazine'),
        'items_list_navigation' => __('Items list navigation', 'pastemagazine'),
    ];

    $rewrite = array(
        'slug'                       => 'tag',
        'with_front'                 => false,
        'hierarchical'               => false,
    );

    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
        'meta_box_cb' => false,
        'capabilities' => array(
            'manage_terms' => 'edit_articles',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_articles'
        )
    ];
    register_taxonomy('article-tag', ['article'], $args);
}
add_action('init', 'pm_article_tag', 4);


/**
 * Display a custom taxonomy dropdown in admin
 */
function pm_filter_post_type_by_taxonomies()
{
    global $typenow;
    $post_type = 'article';
    $taxonomies  = ['article-category', 'article-type'];
    if ($typenow == $post_type) {
        foreach ($taxonomies as $taxonomy) {
            $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => sprintf(__('Show all %s', 'pastemagazine'), $info_taxonomy->label),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => true,
                'hide_empty'      => true,
            ));
        }
    };
}
add_action('restrict_manage_posts', 'pm_filter_post_type_by_taxonomies');

/**
 * Filter posts by taxonomy in admin
 */
function pm_convert_id_to_term_in_query($query)
{
    global $pagenow;
    $post_type = 'article';
    $taxonomies  = ['article-category', 'article-type'];
    $q_vars    = &$query->query_vars;
    foreach ($taxonomies as $taxonomy) {
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }
}
add_filter('parse_query', 'pm_convert_id_to_term_in_query');