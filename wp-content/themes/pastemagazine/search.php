<?php get_header(); ?>

<?php
global $wpdb, $wp_query;
?>

<main class="expanded site-body" id="site_body" role="main">
    <div class="grid-container">

        <div class="grid-x search-wrapper">
            <div class="large-12 cell">
                <div class="grid-x small-up-1 medium-up-2 large-up-12">
                    <div class="large-auto medium-auto small-auto search-container">
                        <br>
                        <form id="article-search" action="/search" method="get" class="search-input">
                            <input type="text" id="master-search" placeholder="Search"
                                value="<?php echo isset($_REQUEST['q']) ? $_REQUEST['q'] : ''; ?>" aria-label="Search"
                                class="ui-autocomplete-input" autocomplete="off" name="q">
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

                        <?php

                        $search_weight = '';
                        $search_where = '';


                        if (isset($_REQUEST['q']) || isset($_REQUEST['t'])) {

                            $ppp = isset($_REQUEST['t']) ?
                                (get_option('search_article_limit') ?? $wp_query->query_vars['posts_per_page'])
                                : (get_option('search_section_article_limit') ?? $wp_query->query_vars['posts_per_page']);

                            //$adFreq = isset($_REQUEST['t']) ? (int) get_option('search_ad_freq') : (int) get_option('search_section_ad_freq');

                            echo '<section id="search-results">';

                            $q = isset($_REQUEST['q']) ? sanitize_text_field($_REQUEST['q']) : '';

                            $tSQL = '';
                            $articleTypeArr = [];

                            if (isset($_REQUEST['t']) && trim($_REQUEST['t']) !== '') {

                                $articleTypeArr = explode('|', trim($_REQUEST['t']));

                                $tSQL = " AND (" . implode(' OR ', array_map(function ($i) {
                                    return sprintf("article_type LIKE '%s'", '%|' . esc_sql($i) . '|%');
                                }, $articleTypeArr)) . ")";
                            }


                            $search_weight .= preg_replace(
                                '/{{s}}/',
                                strtolower($q),
                                "IF (post_title REGEXP '^{{s}}', 40,
                                    IF (post_title REGEXP '{{s}}', 30, 
                                        IF (LOWER(post_title) LIKE '%{{s}}%', 20, 0)
                                    )
                                )"
                            );

                            $search_weight .= preg_replace(
                                '/{{s}}/',
                                sanitize_title($q),
                                " + IF (artist LIKE '%|{{s}}|%', 20, IF (artist LIKE '%{{s}}%', 5, 0))"
                            );

                            $search_weight .= preg_replace(
                                '/{{s}}/',
                                sanitize_title($q),
                                " + IF (article_tags LIKE '%|{{s}}|%', 10, IF (article_tags LIKE '%{{s}}%', 5, 0))"
                            );

                            $search_weight = " + (datediff(post_date, now()))";

                            $sql = "SELECT SQL_CALC_FOUND_ROWS 
                                            ID, 
                                            post_title, 
                                            post_date, 
                                            lead_img_url,
                                            permalink,
                                            co_authors,
                                            rating,
                                            article_category,
                                            article_type,
                                            $search_weight as weight
                                            FROM pm_article_index 
                                            WHERE 1=1
                                            AND post_status = 'publish' ";

                            //$where = " AND (LOWER(post_title) LIKE %s OR LOWER(post_content_raw) LIKE %s)";
                            /* $likeQ = $wpdb->esc_like(strtolower($q));
                            $where = " AND (LOWER(post_title) LIKE '%$likeQ%' OR LOWER(post_content_raw) LIKE '%$likeQ%')"; */

                            $search_where .= preg_replace('/{{s}}/', strtolower($q), " AND (LOWER(post_title) LIKE '%{{s}}%'");

                            $q_arr = array_filter(explode(' ', $q));
                            $q_arr_count = count($q_arr);

                            if ($q_arr_count > 1) {
                                $search_where .= " OR (";
                                foreach ($q_arr as $q_part) {

                                    $search_weight .= preg_replace(
                                        '/{{s}}/',
                                        strtolower($q_part),
                                        " + IF (LOWER(post_title) LIKE '%{{s}}%', 5, 0)"
                                    );
                                }

                                foreach ($q_arr as $q_part) {
                                    $search_where .= preg_replace('/{{s}}/', strtolower($q_part), "LOWER(post_title) LIKE '%{{s}}%' AND ");
                                }

                                $search_where = rtrim($search_where, " AND ") . ")";
                            }



                            $search_where .= preg_replace('/{{s}}/', sanitize_title($q), " OR article_tags LIKE '%{{s}}%'");

                            if ($q_arr_count > 1) {
                                $search_where .= " OR (";
                                foreach ($q_arr as $q_part) {
                                    $search_where .= preg_replace('/{{s}}/', strtolower($q_part), "article_tags LIKE '%{{s}}%' AND ");
                                }
                                $search_where = rtrim($search_where, " AND ") . ")";
                            }

                            $search_where .= preg_replace('/{{s}}/', sanitize_title($q), " OR artist LIKE '%{{s}}%'");

                            if ($q_arr_count > 1) {
                                $search_where .= " OR (";
                                foreach ($q_arr as $q_part) {
                                    $search_where .= preg_replace('/{{s}}/', strtolower($q_part), "artist LIKE '%{{s}}%' AND ");
                                }
                                $search_where = rtrim($search_where, " AND ") . ")";
                            }

                            $search_where .= preg_replace('/{{s}}/', sanitize_title($q), " OR co_authors LIKE '%{{s}}%'");

                            /* if ($q_arr_count > 1) {
                                $search_where .= " OR (";
                                foreach ($q_arr as $q_part) {
                                    $search_where .= preg_replace('/{{s}}/', strtolower($q_part), "artist LIKE '%{{s}}%' AND ");
                                }
                                $search_where = rtrim($search_where, " AND ") . ")";
                            }
                            $search_where .= preg_replace('/{{s}}/', strtolower($q), " OR post_content_raw LIKE '%{{s}}%' "); */

                            $search_where .= ")";

                            $sql .= $search_where;
                            $sql .= count($articleTypeArr) > 0 ? $tSQL : "";
                            $sql .= " ORDER BY weight DESC, post_date DESC";
                            //$sql .= " ORDER BY post_date DESC, weight DESC";

                            //if (count($articleTypeArr) === 1) {

                            $sql .= " LIMIT $ppp";

                            /* $current_page = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
                            $current_page = $current_page === 0 || is_null($current_page) ? 1 : $current_page; */

                            $current_page = isset($wp_query->query_vars['s']) && stristr($wp_query->query_vars['s'], 'page') ? (int) str_ireplace('page/', '', $wp_query->query_vars['s']) : 1;

                            //var_dump($current_page);

                            $sql .= $current_page > 1 ? " OFFSET " . $ppp * ($current_page - 1) : "";
                            //}

                            /* echo $sql;
                            die; */

                            /*$ articles = $wpdb->get_results(
                                $wpdb->prepare(
                                    $sql,
                                    '%' . $wpdb->esc_like(strtolower($q)) . '%',
                                    '%' . $wpdb->esc_like(strtolower($q)) . '%'
                                )
                            ); */

                            $articles = $wpdb->get_results($sql);
                            $results_count = $wpdb->get_var("SELECT FOUND_ROWS()");


                            if (!empty($articles)) {

                                $filtersSQL = "SELECT DISTINCT article_type
                                                FROM pm_article_index 
                                                WHERE 1=1
                                                $search_where";


                                $filtersArray = $wpdb->get_results($filtersSQL);

                                $filters = [];


                                foreach ($filtersArray as $fA) {
                                    $fAFiltered = array_filter(explode('|', $fA->article_type));
                                    foreach ($fAFiltered as $fAF) {
                                        if (!in_array($fAF, $filters)) {
                                            $filters[] = $fAF;
                                        }
                                    }
                                }


                                if (!empty($filters)) {

                                    echo '<div class="filters" id="search-result-filters">
                                            <div class="hed">Filter Results</div>
                                                <select id="filters-dropdown" class="pm-dropdown">
                                                    <option value="" ' . (empty($articleTypeArr) ? 'selected' : '') . '>All Types</option>';
                                    foreach ($filters as $f) {

                                        $fLabel = $GLOBALS['global_article_type'][$f];

                                        echo '<option value="' . $f . '" ' . (in_array($f, $articleTypeArr) ? 'selected' : '') . '>' . $fLabel . '</option>';
                                    }

                                    echo '</select>
                                    </div>';

                                    $fLabel = isset($_REQUEST['t']) ? $GLOBALS['global_article_type'][trim($_REQUEST['t'])] : '';

                                    echo "<script>
                                            jQuery(function($) {

                                                $(document).on('change', '#filters-dropdown', function() {

                                                    const value = $(this).val();
                                                    
                                                    setTimeout(function() {

                                                        //alert('jhhj');

                                                        const url = new URL(window.location.href);

                                                        console.log(value);

                                                        if (value.length > 1) {
                                                            url.searchParams.set('t', value);
                                                            //$('#search-article-type').val(checkedFilters.join('|'));
                                                        } else {
                                                            url.searchParams.delete('t');
                                                            //$('#search-article-type').val('');
                                                        }

                                                        //window.history.replaceState(null, null, url);

                                                        window.location = url.toString().replace(/\/page\/\d*/, '');

                                                    }, 100);

                                                });

                                            });
                                            </script>";
                                }



                                // ARTISTS

                                /* if (!isset($_REQUEST['t'])) {

                                    $artistsSQL = "SELECT DISTINCT artist
                                                FROM pm_article_index 
                                                WHERE 1=1 
                                                AND artist LIKE %s
                                                $tSQL";

                                    $artistsArray = $wpdb->get_results(
                                        $wpdb->prepare(
                                            $artistsSQL,
                                            '%' . $wpdb->esc_like($q) . '%'
                                        )
                                    );

                                    if (!empty($artistsArray)) {
                                        //var_dump($artistsArray);
                                        echo '<div class="grid-x articles-standard artists">
                                            <div class="large-12 cell articles-header">
                                                <b class="float-left hed">Artists</b>
                                            </div>
                                            <ul class="no-bullet search-articles-artists">';

                                        foreach ($artistsArray as $aA) {

                                            $aSlugs = array_values(array_filter(explode('|', $aA->artist)));
                                            //var_dump($aSlugs);
                                            //if (empty($artist)) continue;
                                            $firstArtistSlug = array_shift($aSlugs);
                                            $aName = $wpdb->get_var("SELECT t.name FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = '$firstArtistSlug' AND tt.taxonomy = 'artist'");

                                            echo '<li><a class="non title" href="/artist/' . $firstArtistSlug . '">' . $aName . '</a></li>';
                                        }

                                        echo '</ul>
                                        </div>';
                                    }
                                } */

                                // IF there is MORE than one article-type

                                /* if (count($filters) > 1) {

                                    $adSlotIndex = 0;
                                    $articleCount = 0;

                                    foreach ($filters as $aT) {

                                        $aTCounter = 0;
                                        $aTLabel = $GLOBALS['global_article_type'][$aT];

                                        echo '<div class="grid-x articles-standard">
                                                <div class="large-12 cell articles-header">
                                                    <b class="float-left hed">';
                                        echo count($filters) === 1 ? $q . ' ' : '';
                                        echo $aTLabel . '</b>
                                                </div>
                                                
                                                <ul class="no-bullet articles grid-margin-x flex-container flex-dir-column grouped">';

                                        foreach ($articles as $a) {

                                            if (!stristr($a->article_type, $aT)) continue;

                                            $articleCount++;

                                            $authors = json_decode($a->co_authors, true);

                                            echo '<li class="grid-x grid-padding-x">';

                                            if (!empty($a->lead_img_url) && !stristr($a->lead_img_url, 'p_icon_lead')) {

                                                echo '<a class="large-3 medium-3 cell image youtube" href="' . $a->permalink . '" aria-label="' . htmlentities($a->post_title) . '">
                                                        <picture class="lazyload">
                                                            <source media="(min-width:40em)" srcset="' . $a->lead_img_url . '">
                                                            <img src="' . $a->lead_img_url . '" alt="' . wp_strip_all_tags($a->post_title) . '" />
                                                        </picture>
                                                    </a>';
                                            }

                                            echo '<a class="auto cell copy-container" href="' . $a->permalink . '">';

                                            echo '<b class="title">' . $a->post_title . '</b>';
                                            echo '<b class="byline">By ' . pm_print_authors($authors) . '</b>';
                                            echo '<b class="time" data-iso-date="' . date('c', strtotime($a->post_date)) . '">' . date('F j, Y | g:ia', strtotime($a->post_date)) . '</b>';

                                            echo '</a>';

                                            echo '</li>';

                                            $aTCounter++;

                                            if ($aTCounter >= $ppp) break;
                                        }

                                        echo '<li class="more">
                                                    <a href="/search?q=' . $q . '&amp;t=' . $aT . '" class="non">More ' . $q . ' ' . $aTLabel . '</a>
                                                </li>
                                            </ul>
                                        </div>';

                                         if ($articleCount === $adFreq) {
                                            $articleCount = 0;
                                            $adSlotIndex++;
                                            echo '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
                                        }
                                    }
                                } else {

                                    $fLabel = $GLOBALS['global_article_type'][array_shift($filters)];*/

                                echo '<h4>"' . wp_unslash($q) . '" ' . $fLabel . '</h4>';

                                echo '<ul class="articles no-bullet grid-margin-x flex-container flex-dir-column">';

                                pm_list_articles([
                                    'articles' => $articles,
                                    'results_count' => $results_count,
                                    'current_page' => $current_page,
                                    'ppp' => $ppp,
                                    'ad_freq' => 10 //$adFreq
                                ]);

                                echo '</ul>';



                                echo pm_pagination([
                                    'results_count' => $wp_query->query_vars['results_count'],
                                    'current_page' => $wp_query->query_vars['current_page'],
                                    'ppp' => $ppp
                                ]);
                                // } 
                            } else {

                                echo '<h4>No results found.</h4>';
                            }

                            echo '</section>';
                        } ?>

                    </div>

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>