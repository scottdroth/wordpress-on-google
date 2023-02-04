<?php

global $wpdb, $article_cat, $article_cat_label, $detect, $ytID;
ini_set('allow_url_fopen',1);
setup_postdata($post);

if (has_term('audio', 'article-type', $post)) {
    get_template_part('partials/single-article-audio');
    exit;
}

//$isNoisetrade = has_term('audio', 'article-type', $post);


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
if ( !empty($artistTerms[0] )) {
    $artist = $artistTerms[0]->slug;
}  else {
    
 $artist = "";

}

if ( !empty($artistLabel[0] )) {
   $artistLabel = $artistTerms[0]->name;
}  else {
    
 $artistLabel = "";

}




$authors = json_decode($wpdb->get_var("SELECT co_authors FROM pm_article_index WHERE ID = $post->ID"), true);

$ppp = get_option('single_article_limit') ?? $wp_query->query_vars['posts_per_page'];

$ytID = $articleCustom['youtube_id'];


?>

<?php get_header(); ?>

<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">
        <div class="grid-x article-wrapper" id="article_detail_wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto">

                        <!-- LOOP USED TO BEGIN HERE -->

                        <article id="article-detail-container" <?php //echo $isNoisetrade ? 'class="noisetrade"' : '';
                                                                ?>>

                            <?php if (!empty($ytID)) { ?>

                            <div
                                class="video <?php echo !empty($articleCustom['next_track_url']) ? 'has-next' : ''; ?>">
                                <div id="detail-main-video"
                                    style="background-image: url('https://i3.ytimg.com/vi/<?php echo $ytID; ?>/maxresdefault.jpg'); background-color: #000000; background-size: cover;background-position: center"
                                    class="responsive-embed widescreen" data-artist="<?php echo $artistLabel; ?>">
                                    <iframe style="background-color: transparent;" id="youtube_player" frameborder="0"
                                        allowfullscreen="1" allow="autoplay; encrypted-media"> </iframe>
                                    <script>
                                    setTimeout(function() {

                                        if (!document.getElementById('youtube_iframe_script')) {
                                            var tag = document.createElement('script');
                                            tag.id = 'youtube_iframe_script';
                                            tag.src = 'https://www.youtube.com/iframe_api';
                                            var firstScriptTag = document.getElementsByTagName('script')[0];
                                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                                        }
                                        var player;

                                        function onPlayerStateChange(e) {
                                            if (e.data == YT.PlayerState.ENDED) {
                                                playerEnded(player);
                                            } /* ended */
                                        }

                                        function onYouTubeIframeAPIReady() {
                                            player = new YT.Player('youtube_player', {
                                                events: {
                                                    'onStateChange': onPlayerStateChange
                                                }
                                            });
                                        }

                                        var ifr = document.getElementById('youtube_player');
                                        ifr.setAttribute('src',
                                            'https://www.youtube.com/embed/<?php echo $ytID; ?>?enablejsapi=1&amp;autoplay=0'
                                        );
                                    }, 4000);
                                    </script>
                                </div>

                                <?php if (!empty($articleCustom['next_track_url'])) {
                                        echo '<a href="' . $articleCustom['next_track_url'] . '" class="non next icon-right-dir">' . (!empty($articleCustom['next_track_title']) ? explode(' - ', $articleCustom['next_track_title'])[1] : 'Next track') . '</a>';
                                    } ?>

                            </div>

                            <?php } ?>

                            <div class="header" data-entry-id="<?php the_ID(); ?>">

                                <?php echo !empty($articleCustom['rating']) ? '<div class="rating">' . $articleCustom['rating'] . '</div>' : ''; ?>

                                <h1 class="title"><?php echo $title; ?></h1>

                                <?php echo !empty($articleCustom['subtitle']) ? '<h2 class="subtitle">' . $articleCustom['subtitle'] . '</h2>' : ''; ?>

                                <!-- <a class="ovr" href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author(); ?></a> -->

                                <div class="bylinepublished">By <?php echo pm_print_authors($authors, true); ?>
                                    &nbsp;|&nbsp;
                                    <?php echo get_the_date('F j, Y | g:ia'); ?>
                                </div>

                                <?php echo !empty($articleCustom['extra_byline']) ? '<i class="extra-byline">' . $articleCustom['extra_byline'] . '</i>' : ''; ?>

                                <b class="type">
                                    <a class="non"
                                        href="/<?php echo $articleCategoryTerms[0]->slug; ?>"><?php echo $articleCategoryTerms[0]->name; ?></a>
                                    <a class="non"
                                        href="/articles/<?php echo $articleCategoryTerms[0]->slug; ?>/<?php echo $articleTypeTerms[0]->slug; ?>"><?php echo $articleTypeTerms[0]->name; ?></a>
                                    <?php if (isset($artistTerms[0])) { ?>
                                    <a class="non"
                                        href="/search?q=<?php echo urlencode($artistTerms[0]->name); ?>"><?php echo $artistTerms[0]->name; ?></a>
                                    <?php } ?>
                                </b>
                            </div>

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
 
 


 
                            <?php if (empty($ytID) && !empty($featuredURL) && !stristr($featuredURL, 'p-logo-main') && !stristr($featuredURL, 'p_icon_lead')) {
                                list($imgWidth, $imgHeight) = getimagesize($featuredURL);
                                $isMobile = $detect->isMobile();
                                //echo !$isMobile ? "width='226' height='127'" : "width='$imgWidth' height='$imgHeight'";
                            ?>
                            <div class="main-image">
                                <img src="<?php echo $featuredURL; ?>" alt="<?php echo wp_strip_all_tags($title); ?>"
                                    width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" <?php /*loading="lazy" data-no-lazy="1"*/ ?> />
                            </div>
                            <?php } ?>

                            <div class="copy entry manual-ads">

                                <?php if (stristr($post->post_content, 'audio-playlist-embed')) { ?>

                                <div class="session-player" id="jp_jplayer_0" style="width: 0px; height: 0px;"><img
                                        id="jp_poster_0" style="width: 0px; height: 0px; display: none;"><audio
                                        id="jp_audio_0" preload="metadata"
                                        src="https://mb.wolfgangsvault.com/audio/320/4893852.mp3?token=00000000-0000-0000-0000-000000000000"></audio>
                                </div>

                                <?php } ?>

                                <?php
                                global $adSlotIndex;

                                the_content();

                                ?>
                                <div class="entry-links"><?php wp_link_pages(); ?></div>
                            </div>

                            <div class="grid-x grid-padding-x tags"><b
                                    class="large-1 medium-1 small-1 cell label">Tags</b>
                                <div class="large-11 medium-11 small-11 cell">
                                    <?php

                                    if (!empty($articleTags)) {
                                        foreach ($articleTags as $aT) {
                                            echo '<h2><a class="non" href="/tag/' . $aT->slug . '">' . $aT->name . '</a></h2>';
                                        }
                                    }

                                    ?>
                                </div>
                            </div>

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

                        </article>


                        <!-- LOOP USED TO END HERE -->

                        <?php if ($adSlotIndex < 10) {
                            $adSlotIndex++; ?>
                        <div class="dfp">
                            <div id="mid_leaderboard_rectangle_<?php echo $adSlotIndex; ?>"></div>
                        </div>
                        <?php }

                        /*<div id="pm_single_article_artist_ajax" class="pm_ajax_placeholder"></div>*/

                        /*<div id="pm_single_article_category_ajax" class="pm_ajax_placeholder"></div>*/

                        ?>

                        <?php

                        echo pm_single_article_bottom_artist_section([
                            'artist' => $artist,
                            'artistLabel' => $artistLabel,
                            'ppp' => $ppp,
                            'adSlotIndex' => $adSlotIndex
                        ]);
                        ?>

                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>