<?php

use AmpProject\Validator\Spec\Tag\Strong;

add_action('wp_ajax_pm_article_search', 'pm_article_search_ajax_callback');
add_action('wp_ajax_nopriv_pm_article_search', 'pm_article_search_ajax_callback');

function pm_article_search_ajax_callback()
{

    //if (!wp_verify_nonce( $_POST['aa_nonce'], 'aa_product_frontend_search_nonce' )) wp_send_json_error("NONCE ERROR - aa_product_frontend_search_ajax_callback");

    //wp_send_json_success(pm_var_export($_REQUEST, false));

    if (isset($_REQUEST['q'])) {
        global $wpdb, $current_user;

        $output = '';

        $search_where = 'WHERE 1=1';

        $search_term_orig = sanitize_text_field(trim($_REQUEST['q']));

        if ($search_term_orig !== '') {

            /* $search_term_orig_unsls = wp_unslash($search_term_orig);
            $search_term_orig = preg_quote($search_term_orig_unsls);
            $search_term_orig = str_replace('\(', '(', $search_term_orig);
            $search_term_orig = str_replace('\)', ')', $search_term_orig);
            $search_term_orig = str_replace('–', '-', $search_term_orig);
            $search_term_orig = str_replace('“', '\"', $search_term_orig);
            $search_term_orig = str_replace('”', '\"', $search_term_orig);
            $search_term_orig = str_replace('’', '\\\\\'', $search_term_orig); */

            $search_weight = '';



            // TITLE

            /*  $search_weight .= preg_replace(
                '/{{s}}/',
                strtolower($search_term_orig),
                "IF (post_title REGEXP '(^){{s}}([[:blank:]])', 30, 
                    IF (post_title REGEXP '([[:blank:][:punct:]]|^){{s}}([[:blank:][:punct:]]|$)', 20, 
                        IF (LOWER(post_title) LIKE '%{{s}}%' collate utf8mb4_bin, 10, 0)
                    )
                )"
            ); */
            /* $search_weight .= preg_replace(
                '/{{s}}/',
                strtolower($search_term_orig),
                "IF (post_title REGEXP '([[:blank:][:punct:]]|^){{s}}([[:blank:][:punct:]]|$)', 20, 
                    IF (LOWER(post_title) LIKE '%{{s}}%' collate utf8mb4_bin, 10, 0)
                )"
            ); */


            // ARTIST 

            /* $search_weight .= preg_replace(
                '/{{s}}/',
                sanitize_title($search_term_orig),
                " + IF (artist LIKE '%|{{s}}|%' collate utf8mb4_bin, 20, IF (artist LIKE '%{{s}}%' collate utf8mb4_bin, 10, 0))"
            ); */

            $search_weight .= preg_replace(
                '/{{s}}/',
                strtolower($search_term_orig),
                "IF (artist = '|{{s}}|', 30, 
                    IF (artist LIKE '|{{s}}%', 20, 
                        IF (artist LIKE '%-{{s}}%', 10, 0)
                    )
                )"
            );

            // TAGS

            /* $search_weight .= preg_replace(
                '/{{s}}/',
                sanitize_title($search_term_orig),
                " + IF (article_tags LIKE '%|{{s}}|%' collate utf8mb4_bin, 20, IF (article_tags LIKE '%{{s}}%' collate utf8mb4_bin, 10, 0))"
            ); */


            // CONTENT

            /* $search_weight .= preg_replace(
                '/{{s}}/',
                strtolower($search_term_orig),
                " + IF (LOWER(post_content_raw) REGEXP '(^){{s}}([[:blank:]])', 10, 
                        IF (LOWER(post_content_raw) REGEXP '([[:blank:][:punct:]]|^){{s}}([[:blank:][:punct:]]|$)', 7, 
                            IF (LOWER(post_content_raw) LIKE '%{{s}}%' collate utf8mb4_bin, 3, 0)
                        )
                    )"
            ); */

            /* $search_weight .= preg_replace(
                '/{{s}}/',
                strtolower($search_term_orig),
                " + IF (LOWER(post_content_raw) LIKE '%{{s}}%' collate utf8mb4_bin, 5, 0)"
            ); */


            $search_where .= preg_replace('/{{s}}/', sanitize_title($search_term_orig), " AND (artist = '|{{s}}|' OR artist LIKE '|{{s}}%' OR artist LIKE '%-{{s}}%'");

            /* $search_where .= preg_replace('/{{s}}/', strtolower($search_term_orig), " AND (LOWER(post_title) LIKE '%{{s}}%'");
            $search_where .= preg_replace('/{{s}}/', sanitize_title($search_term_orig), " OR article_tags LIKE '%{{s}}%'");
            $search_where .= preg_replace('/{{s}}/', sanitize_title($search_term_orig), " OR artist LIKE '%{{s}}%'");
            $search_where .= preg_replace('/{{s}}/', strtolower($search_term_orig), " OR post_content_raw LIKE '%{{s}}%' "); */


            $s_arr = array_filter(explode(' ', $search_term_orig));
            $s_arr_count = count($s_arr);

            if ($s_arr_count > 1) {

                foreach ($s_arr as $s_part) {

                    /* $search_weight .= preg_replace(
                        '/{{s}}/',
                        strtolower($s_part),
                        " + IF (LOWER(post_title) REGEXP '([[:blank:]]|^){{s}}([[:blank:]]|$)', 5, IF (LOWER(post_title) LIKE '%{{s}}%' collate utf8mb4_bin, 2, 0))"
                    ); */

                    $search_weight .= preg_replace(
                        '/{{s}}/',
                        strtolower($s_part),
                        " + IF (artist = '|{{s}}|', 10, 
                            IF (artist LIKE '|{{s}}%', 5, 
                                IF (artist LIKE '%-{{s}}%', 2, 0)
                            )
                        )"
                    );

                    $search_weight .= preg_replace(
                        '/{{s}}/',
                        strtolower($s_part),
                        " + IF (LOWER(post_title) LIKE '%{{s}}%', 5, 0)"
                    );
                }

                $search_where .= " OR (";
                foreach ($s_arr as $s_part) {
                    $search_where .= preg_replace('/{{s}}/', strtolower($s_part), "artist LIKE '%{{s}}%' AND ");
                }

                $search_where = rtrim($search_where, " AND ") . ")";

                $search_where .= " OR (";
                foreach ($s_arr as $s_part) {
                    $search_where .= preg_replace('/{{s}}/', strtolower($s_part), "LOWER(post_title) LIKE '%{{s}}%' AND ");
                }

                $search_where = rtrim($search_where, " AND ") . ")";
            }

            $search_where .= ")";

            /* $sql_articles = "SELECT 
                        ID,
                        post_title,
                        permalink, 
                        lead_img_url, 
                        article_category,
                        $search_weight as weight 
                        FROM pm_article_index 
                        $search_where 
                        ORDER BY weight DESC, post_date DESC LIMIT 20"; */

            /* $sql_articles = "SELECT 
                        ID,
                        post_title,
                        permalink, 
                        lead_img_url, 
                        article_category
                        FROM pm_article_index 
                        $search_where 
                        ORDER BY post_date DESC LIMIT 20"; */

            /* $sql_types = "SELECT 
                            DISTINCT article_type
                            FROM pm_article_index 
                            $search_where
                            LIMIT 100";*/

            $sql_artists = "SELECT 
                            DISTINCT artist,
                            $search_weight as weight
                            FROM pm_article_index 
                            $search_where
                            ORDER BY weight DESC
                            LIMIT 100";
        }


        //wp_send_json_success(array('search_results' => $sql_artists, 'has_results' => true));


        //$article_types = $wpdb->get_results($sql_types);
        $article_artists = $wpdb->get_results($sql_artists);
        //$search_results = $wpdb->get_results($sql_articles);

        if (/* $search_results || $article_types ||  */$article_artists) {

            //$output .= '<li>' . $sql . '</li>';
            /* if ($article_types) {
                foreach ($article_types as $st) {
                    $aTslug = str_replace('|', '', $st->article_type);

                    if (empty($aTslug)) continue;

                    $aTname = $GLOBALS['global_article_type'][$aTslug];
                    $output .= '<li data-href="/search?q=' . $search_term_orig . '&t=' . $aTslug . '">' . $search_term_orig . ' <strong>' . $aTname . '</strong></li>';
                }
            } */


            if ($article_artists) {
                //$output .= '<li>' . $sql . '</li>';
                foreach ($article_artists as $sai => $sa) {
                    $aAslug = str_replace('|', '', $sa->artist);
                    $aAname = $wpdb->get_var("SELECT t.name FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = '$aAslug' AND tt.taxonomy = 'artist'");
                    $output .= '<li data-href="/search?q=' . $aAname . '">' . $aAname . '</strong></li>';

                    //if ($sai > 7) break;
                }
            }



            /* if ($search_results) {

                //$output .= $sql_articles;

                foreach ($search_results as $i => $sr) {


                    $aC_array = array_filter(explode('|', $sr->article_category));

                    $aC = [];
                    array_map(function ($i) use (&$aC) {
                        $aC[] = $GLOBALS['global_article_category'][$i];
                    }, $aC_array);

                    $output .= '<li class="ui-menu-item" data-id="' . $sr->ID . '" data-href="' . $sr->permalink . '">
                                    <div class="article_search_lead_img" style="background: url(' . $sr->lead_img_url . ') no-repeat center center / contain"></div>
                                    <div>
                                        <p class="article_search_category">' . implode(', ', $aC) . '</p>
                                        <p class="article_search_title">' . $sr->post_title . '</p>
                                        <em class="article_weight">' . $sr->weight . '</em>
                                    </div>
                                </li>';
                }
            } */
        }

        if ($output === '') {
            $output .= '<li>No search results for the given criteria.</li>';
            //$output .= '<li>Nincs találat.</li><li>'.$sql.'</li>';
            $has_results = false;
        } else {

            $has_results = true;
        }

        wp_send_json_success(array('search_results' => $output, 'sql' => $sql_artists, 'has_results' => $has_results));
    }

    die;
}