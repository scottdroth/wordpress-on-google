<?php

global $wpdb, $article_cat, $article_cat_label;

setup_postdata($post);

$title = get_the_title();
$titleRaw = wp_strip_all_tags($title);
$featuredURL = @array_shift(wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'));
$permalink = get_permalink();


$articleCustom = get_field('additional_article_fields');

//var_dump($articleCustom);

$articleCategoryTerms = wp_get_object_terms($post->ID, 'article-category');
$articleTypeTerms = wp_get_object_terms($post->ID, 'article-type');
$artistTerms = wp_get_object_terms($post->ID, 'artist');
$articleTags = wp_get_object_terms($post->ID, 'article-tag');

$article_cat = $articleCategoryTerms[0]->slug;
$article_cat_label = $articleCategoryTerms[0]->name;

$artist = $artistTerms[0]->slug;
$artistLabel = $artistTerms[0]->name;


$authors = json_decode($wpdb->get_var("SELECT co_authors FROM pm_article_index WHERE ID = $post->ID"), true);


?>

<?php get_header(); ?>

<main class="expanded site-body" id="site_body">
    <div class="grid-container">
        <div class="grid-x article-wrapper noisetrade-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto daytrotter-session">

                        <!-- LOOP USED TO BEGIN HERE -->

                        <section id="article-detail-container" class="noisetrade">
                            <div class="left">
                                <div class="header">
                                    <h1 class="title">
                                        <a class="non"
                                            href="/artist/<?php echo $artist; ?>"><?php echo $artistLabel; ?></a>

                                    </h1>
                                    <?php
                                    //echo !empty($articleCustom['subtitle']) ? '<h2 class="subtitle">' . $articleCustom['subtitle'] . '</h2>' : ''; 
                                    //echo htmlentities(wp_strip_all_tags($title));
                                    $subtitle = explode(' &#8211; ', wp_strip_all_tags($title));
                                    array_shift($subtitle);
                                    echo '<h2 class="subtitle">' . implode(' - ', $subtitle) . '</h2>';
                                    ?>
                                </div>
                                <!-- <img src="https://images.daytrotter.com/concerts/320/20031301-37382359.jpg"
                                    alt="Aug 9, 2010 Daytrotter Studio Rock Island, IL by First Aid Kit"> -->

                                <?php if (!empty($featuredURL) && !stristr($featuredURL, 'p-logo-main-m')) { ?>
                                <img src="<?php echo $featuredURL; ?>" alt="<?php echo wp_strip_all_tags($title); ?>">
                                <?php } ?>

                                <div class="grid-x article-shares-links four">
                                    <a href="<?php echo $permalink; ?>" data-title="<?php echo $title; ?>"
                                        class="small-3 icon-facebook">Share</a>
                                    <a href="<?php echo $permalink; ?>" data-title="<?php echo $title; ?>"
                                        class="small-3 icon-twitter">Tweet</a>
                                    <a href="<?php echo $permalink; ?>" data-title="<?php echo $title; ?>"
                                        class="small-3 icon-reddit-alien only-view-large">Submit</a>
                                    <a href="<?php echo $permalink; ?>" data-title="<?php echo $title; ?>"
                                        data-image="<?php echo $featuredURL; ?>" class="small-3 icon-pinterest">Pin</a>
                                </div>
                                <br>
                                <div class="dfp show-for-large">
                                    <div id="middle_rectangle">
                                        <div id="aax_middle_rectangle"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">

                                <div class="session-player" id="jp_jplayer_0" style="width: 0px; height: 0px;">
                                    <img id="jp_poster_0" style="width: 0px; height: 0px; display: none;">
                                </div>

                                <div class="meta hide-for-large">
                                </div>
                                <div class="standard associated-content">
                                    <div class="copy" id="noisetrade-about">
                                        <?php the_content(); ?>
                                    </div>
                                    <div id="noisetrade-tourdates" style="display:none">
                                        <b class="hed">Tour Dates</b>
                                        <ul class="nof">
                                            <!--populated via JS-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <script>
                            {
                                var ntWatchInterval = window.setInterval(function() {
                                    if (pm && pm.noisetrade) {
                                        window.clearInterval(ntWatchInterval);
                                        pm.noisetrade.playerTimeUpdateWatch = function(time, $player) {
                                            if (time > 45) $player.jPlayer("stop");
                                        }
                                    }
                                }, 500);
                            }
                            </script>
                        </section>

                    </div>

                    <aside class="large-3 medium-3 small-12">
                        <div class="dfp">
                            <div id="top_rectangle">

                            </div>
                        </div>
                        <div id="right-column-sticky" class="dfp cell sticky-container" data-sticky-container=""
                            data-mutate="bottom_rectangle" style="min-height: 488px; height: 600px;"
                            data-events="mutate">
                            <div id="bottom_rectangle" lass="sticky" data-sticky="wiewtn-sticky" data-sticky-on="large"
                                data-anchor="right-column-sticky" class="sticky is-anchored is-at-top"
                                data-resize="bottom_rectangle" data-mutate="bottom_rectangle"
                                style="max-width: 300px; margin-top: 0px; bottom: auto; top: 0px;" data-events="mutate">
                            </div>
                    </aside>


                </div>
            </div>
        </div>
</main>

<?php get_footer(); ?>