<?php

use function DI\add;

function pm_maybe_create_article_index_table()
{

    global $wpdb;

    // set the default character set and collation for the table
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->query("SHOW TABLES LIKE 'pm_article_index'")) return;

    // Check that the table does not already exist before continuing
    $sql = "CREATE TABLE IF NOT EXISTS `pm_article_index` (
                index_id bigint(20) NOT NULL AUTO_INCREMENT,
                ID bigint(20) UNSIGNED NOT NULL,
                post_author BIGINT(20) UNSIGNED NOT NULL default 8, 
                post_date DATETIME NOT NULL default '0000-00-00 00:00:00',
                post_content_raw LONGTEXT NOT NULL,
                post_title TEXT NOT NULL, 
                post_status VARCHAR(20) NOT NULL,
                post_name VARCHAR(200) NOT NULL,
                post_modified DATETIME NOT NULL default '0000-00-00 00:00:00',
                post_type VARCHAR(20) NOT NULL default 'article',
                permalink VARCHAR(2048),
                lead_img_url VARCHAR(2048),
                lead_img_id bigint(20) UNSIGNED NOT NULL,
                article_category VARCHAR(200) NOT NULL,
                article_type VARCHAR(200) NOT NULL,
                artist VARCHAR(200) NOT NULL,
                article_tags TEXT NOT NULL,
                co_authors VARCHAR(2048) NOT NULL,
                visits INT UNSIGNED NOT NULL,
                rating DECIMAL(2,1),
                home_top_order TINYINT UNSIGNED NOT NULL default 0,
                other_top_order TINYINT UNSIGNED NOT NULL default 0,
                editors_home_order TINYINT UNSIGNED NOT NULL default 0, 
                editors_other_order TINYINT UNSIGNED NOT NULL default 0,
                is_interview BOOLEAN default 0,
                is_recommended BOOLEAN default 0,
                PRIMARY KEY (index_id),
                UNIQUE (ID),
                INDEX `idx1` (`post_author`),
                INDEX `idx2` (post_title(255)),
                INDEX `idx3` (`article_category`),
                INDEX `idx4` (`article_type`),
                INDEX `idx5` (`artist`)
            ) $charset_collate;";

    //wp_die($sql);

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta($sql);

    if (!empty($wpdb->last_error)) {
        wp_die($wpdb->last_error);
    }
}
add_action('init', 'pm_maybe_create_article_index_table');




function pm_article_index_update_acf($post_id)
{

    /* $myfile = fopen(ABSPATH . "test.txt", "a");
    fwrite($myfile, $post_id . "\n");
    fclose($myfile); */

    global $wpdb;

    $post_data = $wpdb->get_row("SELECT post_author,
                                        post_date,
                                        post_title,
                                        post_status,
                                        post_content,
                                        post_name,
                                        post_modified
                                        FROM $wpdb->posts
                                        WHERE ID = $post_id");

    //usleep(2000);

    $post_meta = $wpdb->get_row("SELECT 
                        MAX( IF( meta_key = 'visits', meta_value, null) ) as visits,
                        MAX( IF( meta_key = 'lead_image', meta_value, null) ) as lead_image,
                        MAX( IF( meta_key = 'additional_article_fields_rating', meta_value, null) ) as rating,
                        MAX( IF( meta_key = 'additional_article_fields_top_section_homepage', meta_value, null) ) as home_top,
                        MAX( IF( meta_key = 'additional_article_fields_top_section_other', meta_value, null) ) as other_top,
                        MAX( IF( meta_key = 'additional_article_fields_editors_picks_homepage', meta_value, null) ) as editors_home,
                        MAX( IF( meta_key = 'additional_article_fields_editors_picks_other_pages', meta_value, null) ) as editors_other,
                        MAX( IF( meta_key = 'additional_article_fields_is_interview', meta_value, null) ) as is_interview,
                        MAX( IF( meta_key = 'additional_article_fields_is_recommended', meta_value, null) ) as is_recommended
                        FROM $wpdb->postmeta
                        WHERE post_id = $post_id
                        GROUP BY post_id");

    $articleCat = wp_get_post_terms($post_id, 'article-category', ['fields' => 'id=>slug']);
    $articleCatSlugs = implode('|', array_values($articleCat));

    $articleType = wp_get_post_terms($post_id, 'article-type', ['fields' => 'id=>slug']);
    $articleTypeSlugs = implode('|', array_values($articleType));

    $articleTags = wp_get_post_terms($post_id, 'article-tag', ['fields' => 'id=>slug']);
    $articleTagSlugs = implode('|', array_values($articleTags));

    $artist = wp_get_post_terms($post_id, 'artist', ['fields' => 'id=>slug']);
    $artistSlugs = implode('|', array_values($artist));

    $co_authors = (array) get_post_meta($post_id, 'co_authors', true);
    $co_authors = array_merge([$post_data->post_author], $co_authors);
    $co_authors = array_filter($co_authors);

    $co_authors_json = [];

    if (!empty($co_authors)) {
        foreach ($co_authors as $ca) {
            $caData = $wpdb->get_row("SELECT display_name, user_nicename FROM $wpdb->users WHERE ID = $ca");
            $co_authors_json[$ca] = $caData->display_name . '|' . $caData->user_nicename;
        }
    }

    //$article_custom = get_field('additional_article_fields', $post_id);

    //$meta_data = $article_custom;


    $articleFields = [
        'ID'                    => $post_id,
        'post_author'           => (int) $post_data->post_author,
        'post_date'             => $post_data->post_date,
        'post_content_raw'      => wp_trim_words($post_data->post_content, 600), //mb_substr(str_replace(["\n", "\r", "\t"], '', wp_strip_all_tags($post_data->post_content)), 0, 10000),
        'post_title'            => $post_data->post_title,
        'post_status'           => $post_data->post_status,
        'post_name'             => $post_data->post_name,
        'post_modified'         => $post_data->post_modified,
        //'post_type'             => $post_data->post_type,
        'permalink'             => get_permalink($post_id),
        'lead_img_url'          => wp_get_attachment_image_url($post_meta->lead_image, 'full'),
        'lead_img_id'           => (int) $post_meta->lead_image,
        'article_category'      => "|$articleCatSlugs|",
        'article_type'          => "|$articleTypeSlugs|",
        'artist'                => "|$artistSlugs|",
        'article_tags'          => "|$articleTagSlugs|",
        'co_authors'            => wp_json_encode($co_authors_json),
        'visits'                => (int) $post_meta->visits,
        'home_top_order'        => (int) $post_meta->home_top,
        'other_top_order'       => (int) $post_meta->other_top,
        'editors_home_order'    => (int) $post_meta->editors_home,
        'editors_other_order'   => (int) $post_meta->editors_other,
        'is_interview'          => (bool) $post_meta->is_interview,
        'is_recommended'        => (bool) $post_meta->is_recommended,
        'rating'                => $post_meta->rating !== '' ? (float) $post_meta->rating : NULL,
        //'meta_data'             => wp_json_encode($meta_data)
    ];

    /*  echo '<pre>';
    var_export($articleFields);
    echo '</pre>';
    die; */


    $data_arr_sql = '';

    foreach ($articleFields as $col => $val) {
        $data_arr_sql .= "`$col`='" . esc_sql($val) . "',";
    }

    $data_arr_sql = rtrim($data_arr_sql, ',');

    //usleep(2000);

    //$result = $wpdb->insert('pm_article_index', $articleFields);


    //$result = $wpdb->query("INSERT IGNORE INTO pm_article_index ($cols) VALUES ($vals)");

    //return true;


    if ($exists = $wpdb->get_var("SELECT COUNT(*) FROM pm_article_index WHERE ID = $post_id")) {

        /* $myfile = fopen(ABSPATH . "test.txt", "a");
        fwrite($myfile, $exists . ' - ' . $post_id . "\n");
        fclose($myfile); */

        if (is_wp_error($result = $wpdb->query("UPDATE `pm_article_index` SET $data_arr_sql WHERE ID = $post_id"))) {
            return false;
        }

        return true;
    }

    /* $myfile = fopen(ABSPATH . "test.txt", "a");
    fwrite($myfile, 'Maybe insert - ' . $exists . ' - ' . $post_id . "\n");
    fclose($myfile); */

    if ($exists < 2) {

        $result = $wpdb->insert('pm_article_index', $articleFields);

        //$result = $wpdb->query("INSERT IGNORE INTO pm_article_index ($cols) VALUES ($vals)");

        if (!$result) {
            var_dump($wpdb->last_error, $articleFields);
            die;
        }

        if (is_wp_error($result)) {
            error_log($result);
            return false;
        }
    }

    return false;
}
add_action('acf/save_post', 'pm_article_index_update_acf');


function pm_article_index_update($post_id, $post, $update)
{
    global $wpdb;
    $wpdb->update('pm_article_index', ['post_status' => $post->post_status], ['ID' => $post_id]);
}
add_action('save_post_article', 'pm_article_index_update', 10, 3);



function pm_delete_article($pid)
{
    global $wpdb;
    $wpdb->delete('pm_article_index', ['ID' => $pid]);
}
add_action('delete_post', 'pm_delete_article', 10);


function _pm_regenerate_index_table()
{

    if (!isset($_GET['pm_regen'])) return;

    ini_set('memory_limit', '15000M');
    set_time_limit(1200);

    global $wpdb;

    if ($articles = $wpdb->get_results("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_type = 'article' AND post_status = 'publish' LIMIT 51000,1000")) {

        //$wpdb->query("TRUNCATE TABLE `pm_article_index`");
        //sleep(1);

        /*  var_dump(count($articles));
        die; */

        foreach ($articles as $a) {
            //usleep(2000);
            pm_article_index_update_acf($a->ID);
        }
    }

    //$wpdb->query("UPDATE pm_article_index SET visits = FLOOR(RAND() * 1000)");
}
add_action('init', '_pm_regenerate_index_table');


function pm_article_index_update_acf_mass($limit, $offset)
{
    usleep(20000);
    //return $limit;

    global $wpdb;

    /* $myfile = fopen(ABSPATH . "test.txt", "a");
    fwrite($myfile, "SELECT ID FROM $wpdb->posts WHERE post_type = 'article' AND post_status = 'publish' ORDER BY ID DESC LIMIT $offset,$limit" . "\n\n\n");
    fclose($myfile); */
    //return $limit;

    if ($articles = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type = 'article' AND post_status = 'publish' ORDER BY post_date DESC LIMIT $offset,$limit")) {

        //sleep(1);

        /*  var_dump(count($articles));
        die; */

        foreach ($articles as $a) {
            //usleep(100);
            pm_article_index_update_acf($a->ID);
        }
    }

    $article_count = count($articles);

    return $limit > $article_count ? $article_count : $limit;
}


add_action('wp_ajax_pm_maintenance', 'pm_maintenance_callback');
add_action('wp_ajax_nopriv_pm_maintenance', 'pm_maintenance_callback');

function pm_maintenance_callback()
{

    if (!wp_verify_nonce($_POST['aa_nonce'], 'aa_store_maintenance')) {
        wp_send_json_error("NONCE ERROR");
    }

    global $wpdb;

    if (isset($_REQUEST['maintenance_data'])) {

        $return_arr = $_REQUEST['maintenance_data'];

        if (!isset($return_arr['total'])) {

            //$wpdb->query("TRUNCATE TABLE `pm_article_index`");

            $return_arr['total'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'article' AND post_status = 'publish'");
        }


        /*if ($return_arr['offset'] == 0) {
            $return_arr['offset'] = 70000;
        } */

        if (!isset($return_arr['offset'])) {
            $return_arr['offset'] = 0;
        }


        if ($return_arr['offset'] >= $return_arr['total']) {


            $return_arr['offset'] = $return_arr['total'];

            $return_arr['import_done'] = 1;

            $return_arr['html'] = '<h3>Articles updated: ' . $return_arr['offset'] . ' / ' . $return_arr['total'] . '</h3>';

            $return_arr['html'] .= '<div id="import_percent">
            <div><i style="width: 100%; background-color: #52AB37"></i><p>100%</p></div>
            </div>';

            //self::aa_wp_maintenance();

            wp_send_json_success($return_arr);
        }


        //wp_send_json_success($return_arr);

        if ($items_done = pm_article_index_update_acf_mass($limit = 250, $offset = $return_arr['offset'])) {
            $return_arr['offset'] += $items_done;
            $return_arr['progress'] = round((($return_arr['offset'] / $return_arr['total']) * 100), 1);
            $return_arr['import_done'] = 0;
        } else {
            wp_send_json_error("Article update error: " . var_export($items_done, false));
        }


        $return_arr['html'] = '<h3>Articles updated: ' . $return_arr['offset'] . ' / ' . $return_arr['total'] . '</h3>';

        $return_arr['html'] .= '<div id="import_percent">
										<div><i style="width: ' . $return_arr['progress'] . '%"></i><p>' . $return_arr['progress'] . '%</p></div>
									</div>';

        //aa_log(AA_DEBUG_LOG, __FILE__.' : '.__METHOD__.' #'.__LINE__, AA_Store::var_export($return_arr, false));

        wp_send_json_success($return_arr);
    } else {
        wp_send_json_error("Maintenance DATA ERROR");
    }

    die;
}


function _pm_ai_diff()
{

    if (!isset($_GET['pm_diff'])) return;

    ini_set('memory_limit', '15000M');
    set_time_limit(1200);

    global $wpdb;

    $articles = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type = 'article' ORDER BY post_date DESC");

    $pm_articles = $wpdb->get_results("SELECT ID FROM pm_article_index ORDER BY post_date DESC");

    $articles_arr = [];
    $pm_articles_arr = [];

    foreach ($articles as $a) {
        $articles_arr[] = $a->ID;
    }

    foreach ($pm_articles as $pma) {
        $pm_articles_arr[] = $pma->ID;
    }

    var_dump(count($articles_arr), count($pm_articles_arr));

    var_dump(array_diff($articles_arr, $pm_articles_arr));

    /* $counter = 0;
        $found = false;

        foreach ($articles as $a) {
            foreach ($pm_articles as $pma) {
                if ($pma->ID == $a->ID) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                echo $a->ID . '<br>';
                $counter++;
            }
            $found = false;
        }

        echo '<h3>' . $counter . '</h3>'; */



    die;
}
add_action('init', '_pm_ai_diff');
