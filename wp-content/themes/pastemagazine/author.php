<?php
global $wpdb;
$author_id = (int) get_query_var('author');
$author_display_name = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID = $author_id");

?>

<?php get_header(); ?>

<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">
        <div class="grid-x tag-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">
                        <div class="grid-x articles-standard">
                            <div class="large-12 cell articles-header with-sorting">
                                <h1 class="float-left hed">Articles by <?php echo $author_display_name; ?></h1>
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
                                $ppp = get_option('author_article_limit') ?? $wp_query->query_vars['posts_per_page'];

                                pm_list_articles([
                                    'author' => $author_id,
                                    'ppp' => $ppp
                                ]); ?>
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