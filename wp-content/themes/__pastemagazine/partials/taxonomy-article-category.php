<?php

global $wp_query, $article_cat, $idsNotIn;
$qo = $wp_query->get_queried_object();
$ppp = get_option('archive_listing_limit');
?>
<?php get_header(); ?>
<main class="expanded site-body" id="site_body" role="main">
    <?php
    
    $my_yoast_wpseo_title = class_exists('WPSEO_Taxonomy_Meta') ? WPSEO_Taxonomy_Meta::get_term_meta($qo->term_id, 'article-category', 'title') : '';
    
    if (!empty($my_yoast_wpseo_title)) {
        echo '<h1 class="hidden">' . $my_yoast_wpseo_title . '</h1>';
    }
    ?>
    <div class="grid-container">

        <?php get_template_part('partials/home-landing-top'); ?>

        <div class="grid-x tag-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">

                        <?php

                        $adSlotIndex = 0;

                        $features = pm_article_type_listing_shortcode_fn([
                            'article_category_slug' => $article_cat,
                            'article_type_slug' => 'features',
                            'article_type_label' => 'Features',
                            'id' => 'landing-more-featured',
                            'more_text' => 'More ' . $qo->name . ' Features',
                            'more_link' => '/articles/' . $article_cat . '/features',
                            'ppp' => $ppp,
                            'not_in' => !empty($idsNotIn) ? " AND ID NOT IN (" . implode(',', $idsNotIn) . ") " : ""
                        ]);

                        if (!empty($features)) {
                            $adSlotIndex++;
                            echo $features;
                            echo '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
                        }

                        $lists = pm_article_type_listing_shortcode_fn([
                            'article_category_slug' => $article_cat,
                            'article_type_slug' => 'lists',
                            'article_type_label' => 'Lists',
                            'id' => 'landing-lists',
                            'more_text' => 'More ' . $qo->name . ' Lists',
                            'more_link' => '/articles/' . $article_cat . '/lists',
                            'ppp' => $ppp
                        ]);

                        if (!empty($lists)) {
                            $adSlotIndex++;
                            echo $lists;
                            echo '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
                        }

                        $news = pm_article_type_listing_shortcode_fn([
                            'article_category_slug' => $article_cat,
                            'article_type_slug' => 'news',
                            'article_type_label' => 'News',
                            'id' => 'landing-detail-news',
                            'more_text' => 'More ' . $qo->name . ' News',
                            'more_link' => '/articles/' . $article_cat . '/news',
                            'ppp' => $ppp
                        ]);

                        if (!empty($news)) {
                            $adSlotIndex++;
                            echo $news;
                            echo '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
                        }

                        $reviews = pm_article_type_listing_shortcode_fn([
                            'article_category_slug' => $article_cat,
                            'article_type_slug' => 'reviews',
                            'article_type_label' => 'Reviews',
                            'id' => 'landing-reviews',
                            'class' =>
                            'grid-x articles-standard articles-reviews',
                            'ul_class' => 'articles reviews grid-margin-x flex-container flex-dir-column',
                            'more_text' => 'More ' . $qo->name . ' Reviews',
                            'more_link' => '/articles/' . $article_cat . '/reviews',
                            'ppp' => $ppp
                        ]);

                        if (!empty($reviews)) {
                            $adSlotIndex++;
                            echo $reviews;
                            echo '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
                        }
                        ?>

                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>