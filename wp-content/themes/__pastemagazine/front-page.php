<?php get_header(); ?>
<main class="expanded site-body" id="site_body" role="main">
    
    <?php
    $my_yoast_wpseo_title = get_post_meta($post->ID, '_yoast_wpseo_title', true);
    $my_yoast_wpseo_title = empty($my_yoast_wpseo_title) ? 'Paste Magazine: Your Guide to the Best Music, Movies & TV Shows' : $my_yoast_wpseo_title;

    echo '<h1 class="hidden">' . $my_yoast_wpseo_title . '</h1>';

    ?>
    
    <div class="grid-container">


        <?php get_template_part('partials/home-landing-top'); ?>


        <div class="grid-x landing-wrapper __web-inspector-hide-shortcut__">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">

                        <?php
                        global $wp_query, $idsNotIn;
                        $ppp_features = get_option('homepage_article_limit_features') ?? $wp_query->query_vars['posts_per_page'];
                        $ppp_lists = get_option('homepage_article_limit_lists') ?? $wp_query->query_vars['posts_per_page'];
                        $ppp_news = get_option('homepage_article_limit_news') ?? $wp_query->query_vars['posts_per_page'];
                        $ppp_reviews = get_option('homepage_article_limit_reviews') ?? $wp_query->query_vars['posts_per_page'];

                        echo pm_article_type_listing_shortcode_fn([
                            'article_type_slug' => 'features',
                            'article_type_label' => 'Features',
                            'id' => 'landing-more-featured',
                            'ppp' => $ppp_features,
                            'not_in' => !empty($idsNotIn) ? " AND ID NOT IN (" . implode(',', $idsNotIn) . ") " : ""
                        ]);

                        echo pm_article_type_listing_shortcode_fn([
                            'article_type_slug' => 'lists',
                            'article_type_label' => 'Lists',
                            'id' => 'landing-lists',
                            'ppp' => $ppp_lists
                        ]);
                        ?>

                        <div class="newsletter-container">
                            <div class="newsletter-copy-container">
                                <p class="copy-a">GET PASTE RIGHT IN YOUR INBOX</p>
                                <p class="copy-b">The best music, movies, TV, books, comedy and more.</p>

                                <p id="newsletter-signup-result" class="copy-a"></p>
                                <div id="newsletter-signup" class="copy newsletter-signup">
                                    <?php echo do_shortcode('[mc4wp_form id="261672"]'); ?>
                                </div>
                            </div>

                        </div>

                        <?php
                        echo pm_article_type_listing_shortcode_fn([
                            'article_type_slug' => 'news',
                            'article_type_label' => 'News',
                            'id' => 'landing-detail-news',
                            'ppp' => $ppp_news
                        ]);

                        echo pm_article_type_listing_shortcode_fn([
                            'article_type_slug' => 'reviews',
                            'article_type_label' => 'Reviews',
                            'id' => 'landing-reviews',
                            'class' =>
                            'grid-x articles-standard articles-reviews',
                            'ul_class' => 'articles reviews grid-margin-x flex-container flex-dir-column',
                            'ppp' => $ppp_reviews
                        ]);

                        /*
$args = [
'post_type' => 'articles',
];

$articles = new WP_Query($args);

if ($articles->have_posts()):
while ($articles->have_posts()):
$articles->the_post(); ?>
                        <?php get_template_part('entry'); ?>
                        <?php
//comments_template();
?>
                        <?php
endwhile;
wp_reset_postdata();
endif;
?>
                        <?php*/
                        //get_template_part( 'nav', 'below' );
                        ?>
                    </div>

                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>