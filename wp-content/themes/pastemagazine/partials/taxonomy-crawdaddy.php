<?php

global $wp_query, $article_cat, $wpdb;
$qo = $wp_query->get_queried_object();
$ppp = get_option('archive_listing_limit');
?>
<?php get_header(); ?>
<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">

        <div class="grid-x search-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="sub-navigation daytrotter-navigation">
                        <a class="non image icon-crawdaddy" href="/crawdaddy" title="Crawdaddy!">
                            <!--link to Crawdaddy! landing-->
                        </a>
                        <b>
                            <!--flex alignment-->
                        </b>
                    </div>

                    <div class="large-auto medium-auto small-auto search-container">

                        <?php
                        $term = get_term_by('slug', 'crawdaddy', 'article-category');
                        echo get_term_field('description', $term, $taxonomy = 'article-category'); ?>

                        <form id="article-search" action="/search" method="get" class="search-input">
                            <input type="text" id="master-search" placeholder="Search" value="crawdaddy"
                                aria-label="Search" class="ui-autocomplete-input" autocomplete="off" name="q">
                            <a onclick="jQuery(this).parent().submit();" href="#" class="non" id="master-search-button"
                                aria-label="Search">Search</a>
                            <!-- <input id="search-article-type" type="hidden" name="t"
                                value="<?php echo isset($_REQUEST['t']) ? $_REQUEST['t'] : ''; ?>" /> -->
                            <i id="search_in_progress"></i>
                        </form>
                        <!-- <ul id="ui-id-1" tabindex="0"
                            class="ui-menu ui-widget ui-widget-content ui-autocomplete ui-front" style="display: none;">
                        </ul> -->
                        <ul id="search_results_list" tabindex="0"
                            class="ui-menu ui-widget ui-widget-content ui-autocomplete ui-front" style="display: none;">
                        </ul>



                        <div class="grid-x articles-standard">
                            <div class="large-12 cell articles-header">
                                <b class="float-left hed">Crawdaddy</b>
                            </div>
                            <ul class="no-bullet articles grid-margin-x flex-container flex-dir-column grouped">
                                <?php echo pm_list_crawdaddy_articles(); ?>
                            </ul>
                        </div>

                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>