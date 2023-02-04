<?php


global $wp_query, $wpdb;

/* echo '<pre>';
var_dump($wp_query);
echo '</pre>'; */

$article_cat = null;
$article_type = null;
$article_tag = null;
$artist = null;

if (isset($wp_query->query) && is_array($wp_query->query) && !empty($wp_query->query)) {

    //var_export($wp_query->query);

    global $article_cat, $article_cat_label, $article_type, $article_type_label, $article_tag, $article_tag_label, $artist, $artist_label;

    $article_cat = isset($wp_query->query['article-category']) ? $wp_query->query['article-category'] : null;
    $article_cat_label = isset($wp_query->query['article-category']) ? $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE slug = '{$wp_query->query['article-category']}'") : null;
    $article_type = isset($wp_query->query['article-type']) ? $wp_query->query['article-type'] : null;
    $article_type_label = isset($wp_query->query['article-type']) ? $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE slug = '{$wp_query->query['article-type']}'") : null;

    $article_tag = isset($wp_query->query['article-tag']) ? $wp_query->query['article-tag'] : null;
    $article_tag_label = isset($wp_query->query['article-tag']) ? $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE slug = '{$wp_query->query['article-tag']}'") : null;

    $artist = isset($wp_query->query['artist']) ? $wp_query->query['artist'] : null;
    $artist_label = isset($wp_query->query['artist']) ? $wpdb->get_var("SELECT t.name FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = '{$wp_query->query['artist']}' AND tt.taxonomy = 'artist'") : null;

    $article_type_header = $article_type_label ? $article_cat_label . ' ' . $article_type_label : $article_cat_label;
    $article_type_header = $article_type === 'most-read' ? "Most Popular $article_cat_label" : $article_type_header;
    $article_type_header = $article_type === 'editors-picks' ? "$article_cat_label Editors' Picks" : $article_type_header;
    $article_type_header = !is_null($artist_label) ? $artist_label : $article_type_header;
    $article_type_header = !is_null($article_tag_label) ? "Tag results: &#8220;$article_tag_label&#8221;" : $article_type_header;
}

if ($article_cat === 'crawdaddy') {
    get_template_part('partials/taxonomy-crawdaddy');
    exit;
}

if (!is_null($article_cat) && is_null($article_type)) {
    get_template_part('partials/taxonomy-article-category');
    exit;
}

if ($article_type === 'studio' || $article_cat === 'studio') {
    get_template_part('partials/taxonomy-studio');
    exit;
}

?>
<?php get_header(); ?>
<?php //var_dump($article_cat, $article_type, $article_tag, $artist);
?>
<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">
        <div class="grid-x tag-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">
                        <div class="grid-x articles-standard">
                            <div class="large-12 cell articles-header with-sorting">
                                <h1 class="float-left hed"><?php echo $article_type_header; ?></h1>
                                <?php if (!in_array($article_type, ['most-read'])) { ?>
                                <div class="sorting float-right">
                                    <a href="<?php echo preg_replace('/\?(.*)/', '', $_SERVER['REQUEST_URI']); ?>"
                                        class="time <?php echo !isset($_GET['sort']) ? 'a' : ''; ?>">Most Recent</a>
                                    <a class="popularity <?php echo isset($_GET['sort']) && $_GET['sort'] === 'popular' ? 'a' : ''; ?>"
                                        href="<?php echo preg_replace('/\?(.*)/', '', $_SERVER['REQUEST_URI']); ?>?sort=popular">Most
                                        Popular</a>
                                </div>
                                <?php } ?>
                            </div>

                            <ul class="articles no-bullet grid-margin-x flex-container flex-dir-column">
                                <?php

                                /* if (have_posts()) : while (have_posts()) : the_post(); ?>
                                <?php get_template_part('partials/archive-entry'); ?>
                                <?php endwhile;
                                endif; */

                                //$ppp = in_array($article_type, ['most-read', 'news']) ? $ppp * 4 : $ppp;
                                $ppp = get_option('archive_type_listing_limit') ?? $wp_query->query_vars['posts_per_page'];

                                //var_dump($ppp);

                                pm_list_articles([
                                    'article_cat' => $article_cat,
                                    'article_type' => $article_type,
                                    'article_tag' => $article_tag,
                                    'artist' => $artist,
                                    'ppp' => $ppp
                                ]);

                                ?>

                            </ul>

                            <div style="clear:both"></div>

                            <?php
                            /* echo '<pre>';
                                var_dump($wp_query);
                                echo '</pre>'; */

                            echo pm_pagination([
                                'results_count' => $wp_query->query_vars['results_count'],
                                'current_page' => $wp_query->query_vars['current_page'],
                                'ppp' => $ppp
                            ]);
                            ?>

                        </div>
                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>