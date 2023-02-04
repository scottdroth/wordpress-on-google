<?php

global $wp_query, $article_cat, $article_type;
$qo = $wp_query->get_queried_object();

?>
<?php //var_dump($article_cat, $article_type, $article_tag);
?>
<?php get_header(); ?>
<main class="expanded site-body" id="site_body">
    <div class="grid-container">
        <div class="grid-x landing-wrapper studio-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-12 medium-12 small-12">

                        <?php

                        $ppp = get_option('video_listing_limit');

                        if (!is_null($article_cat) && !is_null($article_type)) {

                            switch ($article_type) {

                                case 'recent':
                                default:

                                    $args = [
                                        'label' => 'Most Recent Videos',
                                        'ppp' => $ppp
                                        //'where' => '',
                                        //'orderby' => 'ORDER BY post_date DESC'
                                    ];

                                    break;

                                case 'most-popular':

                                    $args = [
                                        'label' => 'Most Popular Videos',
                                        'ppp' => $ppp,
                                        'where' => " AND visits > 0",
                                        'orderby' => 'ORDER BY visits DESC'
                                    ];

                                    break;


                                case 'recommended':

                                    $args = [
                                        'label' => 'Recommended Videos',
                                        'ppp' => $ppp,
                                        'where' => " AND is_recommended = 1",
                                        //'orderby' => 'ORDER BY visits DESC'
                                    ];

                                    break;

                                case 'interviews':

                                    $args = [
                                        'label' => 'Interview Videos',
                                        'ppp' => $ppp,
                                        'where' => " AND is_interview = 1",
                                        //'orderby' => 'ORDER BY visits DESC'
                                    ];

                                    break;
                            }


                            echo pm_studio_article_listing_shortcode_fn($args);

                            /* echo '<pre>';
                                var_dump($wp_query);
                                echo '</pre>'; */

                            echo pm_pagination([
                                'results_count' => $wp_query->query_vars['results_count'],
                                'current_page' => $wp_query->query_vars['current_page'],
                                'ppp' => $ppp
                            ]);
                        } else {

                            echo pm_studio_article_listing_shortcode_fn([
                                'label' => 'Recent',
                                'slug' => 'recent',
                                'ppp' => $ppp
                                //'where' => '',
                                //'orderby' => 'ORDER BY post_date DESC'
                            ]);

                            echo pm_pagination([
                                'results_count' => $wp_query->query_vars['results_count'],
                                'current_page' => $wp_query->query_vars['current_page'],
                                'ppp' => $ppp
                            ]);
                            /* echo pm_studio_article_listing_shortcode_fn([
                                'label' => 'Most Popular',
                                'slug' => 'most-popular',
                                'ppp' => $ppp,
                                'where' => " AND visits > 0",
                                'orderby' => 'ORDER BY visits DESC'
                            ]); */

                            /* echo pm_studio_article_listing_shortcode_fn([
                                'label' => 'Recommended',
                                'slug' => 'recommended',
                                'ppp' => $ppp,
                                'where' => " AND is_recommended = 1",
                                //'orderby' => 'ORDER BY visits DESC'
                            ]);

                            echo pm_studio_article_listing_shortcode_fn([
                                'label' => 'Interviews',
                                'slug' => 'interviews',
                                'ppp' => $ppp,
                                'where' => " AND is_interview = 1",
                                //'orderby' => 'ORDER BY visits DESC'
                            ]); */
                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>