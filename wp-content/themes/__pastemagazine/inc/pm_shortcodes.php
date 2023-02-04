<?php

function pm_article_type_listing_shortcode_fn(array $atts)
{
    $a = shortcode_atts(
        [
            'article_category_slug' => '',
            'article_type_slug' => '',
            'article_type_label' => '',
            'id' => '',
            'class' => 'grid-x articles-standard',
            'ul_class' => 'articles grid-margin-x flex-container flex-dir-column',
            'more_text' => '',
            'more_link' => '',
            'ppp' => false,
            'not_in' => ''
        ],
        $atts
    );

    $output = '';

    //return wp_json_encode($a);

    extract($a);

    if (empty(trim($article_type_slug))) {
        return 'Missing article_type_slug.';
    }

    $more_text = $more_text !== '' ? $more_text : 'More ' . $article_type_label;
    $more_link = $more_link !== '' ? $more_link : '/articles/article-type/' . $article_type_slug;

    $ppp = !$ppp ? get_option('posts_per_page') : ($article_type_slug === 'reviews' ? 10 : $ppp);



    $articleCategorySQL = $article_category_slug !== '' ? " AND article_category LIKE '%|$article_category_slug|%'" : '';

    $notIn = !empty($not_in) ? $not_in : '';

    global $wpdb;

    $maybeHideForMobile = $article_type_label === 'Features' ? " show-for-medium" : "";

    $output .=
        '<div class="' . $class . '" id="' . $id . '">
            <div class="large-12 cell articles-header ' . $maybeHideForMobile . '">
                <a href="' . $more_link . '" class="float-left hed">' . $article_type_label . '</a>
            </div>';

    $sql = "SELECT ID, 
                    post_title, 
                    post_date, 
                    lead_img_url,
                    permalink,
                    co_authors,
                    rating 
                    FROM pm_article_index 
                    WHERE 1=1
                    AND post_status = 'publish'
                    AND article_type = '|$article_type_slug|'
                    $articleCategorySQL
                    $notIn
                    ORDER BY post_date DESC
                    LIMIT $ppp";

    //echo $sql . '<hr/>';

    $articles = $wpdb->get_results($sql);


    if (empty($articles)) {
        //return "No $article_type_label found.";
        return "";
    }

    $output .=
        '<ul class="' . $ul_class . '">';

    foreach ($articles as $article) {

        $thumbnailURL = $article->lead_img_url;
        $permalink = $article->permalink;
        $authors = json_decode($article->co_authors, true);

        $output .= '<li class="grid-x grid-padding-x">';

        $output .=
            '<a class="large-3 medium-3 cell image" href="' . $permalink . '" aria-label="' . wp_strip_all_tags($article->post_title) . '">
                <picture class="lazyload">
                    <source media="(min-width:40em)" srcset="' . $thumbnailURL . '">
                    <img src="' . $thumbnailURL . '" alt="' . wp_strip_all_tags($article->post_title) . '" />
                </picture>
            </a>';

        //<b class="time" data-iso-date="2022-05-02T15:38:06-06:00">May 2, 2022 &nbsp;|&nbsp; 3:38pm</b>
        $output .= '<a class="auto cell copy-container" href="' . $permalink . '">';

        $output .= '<b class="title">' . $article->post_title . '</b>';
        $output .= $article_type_slug === 'reviews' && (float) $article->rating !== 0 ? '<b class="rating"><i class="text">Rating</i><i class="number">' . $article->rating . '</i></b>' : '';
        $output .= $article_type_slug !== 'reviews' ? '<b class="byline">By ' . pm_print_authors($authors) . '</b>' : '';
        $output .= $article_type_slug !== 'reviews' ? '<b class="time" data-iso-date="' . date('c', strtotime($article->post_date)) . '">' . date('F j, Y | g:ia', strtotime($article->post_date)) . '</b>' : '';

        $output .= '</a>';

        $output .= '</li>';
    }

    if ($article_type_slug === 'reviews' && count($articles) % 2 !== 0) {
        $output .= '<li class="grid-x grid-padding-x"><a class="large-3 medium-3 cell image youtube"></a></li>';
    }

    $output .= $article_type_slug === 'reviews' ? '</ul><ul class="articles grid-margin-x flex-container flex-dir-column more">' : '';

    $output .= '<li class="more">
                    <a href="' . $more_link . '">' . $more_text . '</a>
                </li>';

    $output .= '</ul>
            </div>';

    return $output;
}
add_shortcode(
    'pm_article_type_listing',
    'pm_article_type_listing_shortcode_fn'
);





function pm_studio_article_listing_shortcode_fn(array $atts)
{
    $a = shortcode_atts(
        [
            'label' => '',
            'slug' => '',
            'where' => '',
            'orderby' => 'ORDER BY post_date DESC',
            'ppp' => false
        ],
        $atts
    );

    $output = '';

    extract($a);

    global $wpdb, $wp_query;

    //var_dump($a);

    $ppp = !$ppp ? $wp_query->query_vars['posts_per_page'] : $ppp; //get_query_var('posts_per_page');

    $adFreq = 16; // (int) get_option('archive_listing_ad_freq') ?? $wp_query->query_vars['posts_per_page'];
    $adSlotIndex = 0;

    $sql = "SELECT SQL_CALC_FOUND_ROWS
                    ID, 
                    post_title, 
                    lead_img_url,
                    permalink,
                    artist
                    FROM pm_article_index 
                    WHERE 1=1
                    AND post_status = 'publish'
                    AND article_type = '|studio|'
                    AND (SELECT meta_value FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = 'additional_article_fields_youtube_id') != ''
                    AND (
                            (SELECT meta_value FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = 'additional_article_fields_next_track_url') = ''
                            OR
                            (SELECT COUNT(*) FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = 'additional_article_fields_next_track_url') = 0
                        )
                    $where
                    $orderby
                    LIMIT $ppp";

    // Pagination

    $qv_page = get_query_var('page');
    $qv_paged = get_query_var('paged');

    $current_page = $qv_page != '' && $qv_page != 0 ? $qv_page : ($qv_paged != '' && $qv_paged != 0 ? $qv_paged : 1);
    if (isset($current_page) && ($current_page === 0 || is_null($current_page))) $current_page = 1;
    if (isset($current_page) && $current_page > 1) $sql .= " OFFSET " . $ppp * ($current_page - 1);

    //wp_die($sql);

    // Maybe redirect if pagecount > 10
    // if ($current_page > 10) {
    //     wp_safe_redirect(home_url().'/studio');
    // }


    $articles = $wpdb->get_results($sql);
    $results_count = $wpdb->get_var("SELECT FOUND_ROWS()");

    // Limit the pagecount to 10
    $results_count = $results_count > $ppp * 10 ? $ppp * 10 : $results_count;

    $wp_query->query_vars['results_count'] = $results_count;
    $wp_query->query_vars['current_page'] = $current_page;

    if (empty($articles)) {
        return "";
    }

    $output .= '<div class="large-12 cell articles-header"><h1 class="float-left hed">Recent Videos</h1></div>';

    $output .= '<div class="carousel01 pagesize0" id="landing-studio-recent">';

    if ($label !== 'Recent') {
        $output .= '<div class="main-hed">';
        $output .= !empty($slug) ?
            '<a href="/articles/studio/' . $slug . '" class="non see-more"><b>More</b></a>'
            : '';
        $output .= '<div class="hed">' . $label . '</div>
                </div>';
    } else {

        //$output .= '<br>';
    }

    $output .= '<div class="container">
                    <ul class="no-bullet grid-x small-12">';

    $articleCount = 0;

    foreach ($articles as $a) {

        $articleCount++;



        $artists = array_filter(explode('|', $a->artist));
        $artistsHTML = [];

        foreach ($artists as $art) {
            //$artistsHTML[] = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE slug = '$art' ORDER BY term_id DESC");
            $artistsHTML[] = get_term_by('slug', $art, 'artist')->name;
        }

        $artistsHTML = implode(', ', $artistsHTML); //.$a->artist;

        $output .= '<li class="large-2 medium-4 small-12">';

        //$output .= "SELECT name FROM $wpdb->terms WHERE slug = '$art'";
        $output .=
            '<a class="image" href="' . $a->permalink . '" aria-label="' . wp_strip_all_tags($a->post_title) . '">
                <picture class="lazyload">
                    <source media="(min-width:40em)" srcset="' . $a->lead_img_url . '">
                    <img src="' . $a->lead_img_url . '" alt="' . wp_strip_all_tags($a->post_title) . '" />
                </picture>
            </a>';
        $output .= '<a class="title" href="' . $a->permalink . '" title="' . wp_strip_all_tags($a->post_title) . '">' . $artistsHTML . '</a>';
        $output .= '</li>';

        if ($adFreq > 0 && $articleCount % $adFreq === 0) {
            $adSlotIndex++;
            $output .= '<li class="large-12 ad_slot"><div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div></li>';
        }
    }

    $output .= '</ul>
            </div>
        </div>';

    return $output;
}
add_shortcode(
    'pm_studio_article_listing',
    'pm_studio_article_listing_shortcode_fn'
);






/**
 * MOST POPULAR
 */

function pm_sidebar_most_popular_shortcode_fn($atts = [])
{
    $a = shortcode_atts(
        [],
        $atts
    );

    global $wpdb, $article_cat, $article_cat_label;

    $output = '';

    $article_cat = !empty($atts['articleCat']) ? $atts['articleCat'] : $article_cat;

    $article_cat_label = !empty($atts['articleCatLabel']) ? $atts['articleCatLabel'] : $article_cat_label;


    /*$pm_cache_key = !is_null($article_cat) ? 'pm_sidebar_popular_transient_' . $article_cat : 'pm_sidebar_popular_transient_general';

    $eta = -hrtime(true);
    if ($cache = get_transient($pm_cache_key)) {
        $eta += hrtime(true);
        return 'transient (' . ($eta / 1e+6) . ')' . $cache;
    }*/


    $articleCategorySQL = !is_null($article_cat) ? " AND article_category LIKE '%|$article_cat|%' " : "";

    $limit = get_option('sidebar_article_limit') ?? get_option('posts_per_page');

    $sql = "SELECT  ID, 
                    post_title, 
                    co_authors, 
                    permalink,
                    post_date 
                FROM pm_article_index 
                WHERE 1=1
                AND post_status = 'publish' 
                $articleCategorySQL
                ORDER BY visits DESC
                LIMIT $limit";

    $mostPopular = $wpdb->get_results($sql);

    //return $sql;


    if (!empty($mostPopular)) {

        $label = !is_null($article_cat_label) ? "$article_cat_label Most Popular" : "Most Popular";
        $moreLink = !is_null($article_cat) ? "/articles/$article_cat/most-read" : "/articles/article-type/most-read";

        $output .= '
        <div class="grid-x articles-abbreviated" id="article-detail-essentials-small">
            <div class="large-12 cell articles-header">
                <a href="' . $moreLink . '" class="float-left hed">' . $label . '</a>
            </div>
            <ul class="articles grid-margin-x flex-container flex-dir-column">';

        foreach ($mostPopular as $mP) {

            $permalink = $mP->permalink;
            $authors = json_decode($mP->co_authors, true);

            $output .= '
                <li class="grid-x grid-padding-x">
                    <a href="' . $permalink . '" class="auto cell copy-container noimage">
                    <b class="title">' . $mP->post_title . '</b>
                    <b class="byline hide-for-large">By ' . pm_print_authors($authors) . '</b>
                    <b class="time hide-for-medium" data-iso-date="' . date('c', strtotime($mP->post_date)) . '">' . date('F j, Y | g:ia', strtotime($mP->post_date)) . '</b>
                    </a>
                </li>';
        }

        $output .= '
                <li class="more">
                    <a href="' . $moreLink . '">More ' . $label . '</a>
                </li>
            </ul>
        </div>';
    }

    //set_transient($pm_cache_key, $output, 3600);

    return $output;
}
add_shortcode(
    'pm_sidebar_most_popular',
    'pm_sidebar_most_popular_shortcode_fn'
);





/**
 * EDITORS' PICKS
 */

function pm_sidebar_editors_picks_shortcode_fn($atts = [])
{

    global $article_type;

    $article_type = !empty($atts['articleType']) ? $atts['articleType'] : $article_type;

    if ($article_type === 'editors-picks'/* || is_singular('article') || $atts['isSingularArticle']*/) return;

    $a = shortcode_atts(
        [],
        $atts
    );

    global $wpdb, $article_cat, $article_cat_label;

    $output = '';

    $article_cat = !empty($atts['articleCat']) ? $atts['articleCat'] : $article_cat;

    $article_cat_label = !empty($atts['articleCatLabel']) ? $atts['articleCatLabel'] : $article_cat_label;


    /*$pm_cache_key = !is_null($article_cat) ? 'pm_sidebar_editors_transient_' . $article_cat : 'pm_sidebar_editors_transient_general';

    $eta = -hrtime(true);
    if ($cache = get_transient($pm_cache_key)) {
        $eta += hrtime(true);
        return 'transient (' . ($eta / 1e+6) . ')' . $cache;
    }*/

    //return wp_json_encode($a);

    //extract($a);

    /* $editorsPicksMetaKey = is_front_page() ? 'additional_article_fields_editors_picks_homepage' : 'additional_article_fields_editors_picks_other_pages';

    $editorsPicks = $wpdb->get_results("SELECT ID, 
                                                post_title, 
                                                post_author, 
                                                post_date 
                                        FROM $wpdb->posts 
                                        WHERE post_type = 'article'
                                        AND post_status = 'publish' 
                                        AND (SELECT COUNT(*) FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = '$editorsPicksMetaKey' AND meta_value = '1') > 0
                                        ORDER BY post_date DESC"); */


    $editorsPicksMetaKey = is_front_page()/* || (bool) $atts['isFrontPage'] */? 'editors_home_order' : 'editors_other_order';

    $articleCategorySQL = !is_null($article_cat) ? " AND article_category LIKE '%|$article_cat|%' " : "";

    $limit = get_option('sidebar_article_limit') ?? get_option('posts_per_page');
    //$limit = !$limit ? get_option('posts_per_page') : $limit;

    $sql = "SELECT  ID, 
                    post_title, 
                    co_authors, 
                    permalink,
                    post_date 
                FROM pm_article_index 
                WHERE 1=1
                AND post_status = 'publish' 
                AND $editorsPicksMetaKey > 0
                $articleCategorySQL
                ORDER BY $editorsPicksMetaKey ASC
                LIMIT $limit";

    $editorsPicks = $wpdb->get_results($sql);

    //return wp_json_encode($atts);
    //return $sql;

    //var_dump($editorsPicksMetaKey, $editorsPicks);

    if (!empty($editorsPicks)) {

        //$moreLink = !is_null($article_cat) ? "/articles/$article_cat/editors-picks" : "/articles/article-type/editors-picks";
        //<a href="' . $moreLink . '" class="float-left hed">Editors\' Picks</a>
        
        $output .= '
        <div class="grid-x articles-abbreviated" id="article-detail-essentials-small">
            <div class="large-12 cell articles-header">
                <span class="float-left hed">Editors\' Picks</span>
            </div>
            <ul class="articles grid-margin-x flex-container flex-dir-column">';

        foreach ($editorsPicks as $eP) {

            $permalink = $eP->permalink;
            $authors = json_decode($eP->co_authors, true);

            $output .= '
                <li class="grid-x grid-padding-x">
                    <a href="' . $permalink . '" class="auto cell copy-container noimage">
                    <b class="title">' . $eP->post_title . '</b>
                    <b class="byline hide-for-large">By ' . pm_print_authors($authors) . '</b>
                    <b class="time hide-for-medium" data-iso-date="' . date('c', strtotime($eP->post_date)) . '">' . date('F j, Y | g:ia', strtotime($eP->post_date)) . '</b>
                    </a>
                </li>';
        }

        /*$output .= '
                <li class="more">
                    <a href="' . $moreLink . '">More ' . (!is_null($article_cat_label) ? $article_cat_label : '') . ' Editors\' Picks</a>
                </li>';*/
        
        $output .= '</ul>
                </div>';
    }

    //set_transient($pm_cache_key, $output, 3600);

    return $output;
}
add_shortcode(
    'pm_sidebar_editors_picks',
    'pm_sidebar_editors_picks_shortcode_fn'
);




/**
 * NEWS
 */

function pm_sidebar_news_shortcode_fn($atts = [])
{

    if (is_front_page() /*|| (bool) $atts['isFrontPage']*/ || is_singular('article')) return; // We don't want news of the Homepage


    $a = shortcode_atts(
        [],
        $atts
    );

    global $wpdb, $article_cat, $article_cat_label;

    $output = '';

    $article_cat = !empty($atts['articleCat']) ? $atts['articleCat'] : $article_cat;
    $article_cat_label = !empty($atts['articleCatLabel']) ? $atts['articleCatLabel'] : $article_cat_label;


    /*$pm_cache_key = !is_null($article_cat) ? 'pm_sidebar_news_transient_' . $article_cat : 'pm_sidebar_news_transient_general';

    $eta = -hrtime(true);
    if ($cache = get_transient($pm_cache_key)) {
        $eta += hrtime(true);
        return 'transient (' . ($eta / 1e+6) . ')' . $cache;
    }*/

    //return wp_json_encode($a);

    //extract($a);

    /* <div class="dfp">
        <div id="top_rectangle">
            <div id="aax_top_rectangle"><iframe marginwidth="0" marginheight="0" scrolling="no"
                    frameborder="0" height="601px" width="300px" id="aax_if_aax_top_rectangle"
                    allowtransparency="true"
                    name="{&quot;type&quot;:2,&quot;fid&quot;:3,&quot;tarOg&quot;:&quot;https://www.pastemagazine.com&quot;}"
                    src="https://pastemagazine.assistpub.com/display.html?_otarOg=https%3A%2F%2Fwww.pastemagazine.com&amp;_cpub=AAXGIZE66&amp;_csvr=050516_368&amp;_cgdpr=1&amp;_cgdprconsent=0&amp;_cusp_status=0&amp;_ccoppa=0"
                    sandbox="allow-forms allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-top-navigation-by-user-activation allow-scripts"></iframe>
            </div>
        </div>
    </div> */

    $sortSQL = isset($_GET['sort']) && $_GET['sort'] === 'popular' ? "ORDER BY visits DESC" : "ORDER BY post_date DESC";

    $articleCategorySQL = !is_null($article_cat) ? " AND article_category LIKE '%|$article_cat|%' " : "";

    $limit = get_option('sidebar_article_limit') ?? get_option('posts_per_page');
    //$limit = !$limit ? get_option('posts_per_page') : $limit;

    $news = $wpdb->get_results("SELECT  ID, 
                                        post_title, 
                                        co_authors, 
                                        permalink,
                                        post_date 
                                        FROM pm_article_index 
                                        WHERE 1=1
                                        AND post_status = 'publish' 
                                        AND article_type = '|news|'
                                        $articleCategorySQL
                                        $sortSQL
                                        LIMIT $limit");

    if (!empty($news)) {

        $label = !is_null($article_cat_label) ? "$article_cat_label News" : "News";
        $moreLink = !is_null($article_cat) ? "/articles/$article_cat/news" : "/articles/article-type/news";

        $output .= '
        <div class="grid-x articles-abbreviated" id="article-detail-essentials-small">
            <div class="large-12 cell articles-header">
                <a href="' . $moreLink . '" class="float-left hed">' . $label . '</a>
            </div>
            <ul class="articles grid-margin-x flex-container flex-dir-column">';

        foreach ($news as $n) {

            $permalink = $n->permalink;
            $authors = json_decode($n->co_authors, true);

            $output .= '
                <li class="grid-x grid-padding-x">
                    <a href="' . $permalink . '" class="auto cell copy-container noimage">
                    <b class="title">' . $n->post_title . '</b>
                    <b class="byline hide-for-large">By ' . pm_print_authors($authors) . '</b>
                    <b class="time hide-for-medium" data-iso-date="' . date('c', strtotime($n->post_date)) . '">' . date('F j, Y | g:ia', strtotime($n->post_date)) . '</b>
                    </a>
                </li>';
        }

        $output .= '
                <li class="more">
                    <a href="' . $moreLink . '">More ' . $label . '</a>
                </li>
            </ul>
        </div>';
    }

    //set_transient($pm_cache_key, $output, 3600);

    return $output;
}
add_shortcode(
    'pm_sidebar_news',
    'pm_sidebar_news_shortcode_fn'
);



function pm_contact_us_form_shortcode_fn($atts)
{
    $a = extract(shortcode_atts(
        [
            'to' => ''
        ],
        $atts
    ));

    if (!is_email($to)) {
        return '<p>Please specify the <em>to="{email_address}"</em> parameter in the shortcode.</p>';
    }

    $output = '';

    if (isset($_REQUEST['contactUsSubmit'])) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query(
                array(
                    'secret' => '6LeDShkTAAAAAG5fyIRrqV5HHnwxdwb54TdCi2iV',
                    'response' => $_POST['g-recaptcha-response'],
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            )
        );

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $recaptcha_result = json_decode($server_output, true);

        $errorMsg = '';

        //return pm_var_export($recaptcha_result, false, false);

        /*
        site key: 6LeDShkTAAAAAPOq2_fIcUUz5reCx09BYUCO8KEH
        secret: 6LeDShkTAAAAAG5fyIRrqV5HHnwxdwb54TdCi2iV
        */

        if (!isset($recaptcha_result['success']) || !$recaptcha_result['success']) {
            /* array (
                'success' => false,
                'error-codes' => 
                array (
                    0 => 'invalid-input-response',
                ),
                )
                
             array (
                'success' => true,
                'challenge_ts' => '2022-07-26T15:24:15Z',
                'hostname' => 'www.pastemagazine.com',
                )   
                */

            $errorMsg .= '<p>You have to check the reCaptcha checkbox to be able to submit the form.</p>';
        }


        switch (true) {

            case empty(sanitize_text_field($_REQUEST['ContactUsName'])):
                $errorMsg .= "<p>Please fill in Your Name.</p>";

            case empty(sanitize_text_field($_REQUEST['ContactUsEmail'])) || !is_email(trim($_REQUEST['ContactUsEmail'])):
                $errorMsg .= "<p>Please provide a valid Email Address.</p>";

            case empty(sanitize_text_field($_REQUEST['ContactUsSubject'])):
                $errorMsg .= "<p>Please provide an Email Subject.</p>";

            case empty(sanitize_text_field($_REQUEST['ContactUsMessage'])):
                $errorMsg .= "<p>Please provide a Message.</p>";
        }

        if (!empty($errorMsg)) {

            $output .= '<div style="color: #c1001b; text-align: center;">';
            $output .= '<strong>Please fix the errors below:<br><br></strong>';
            $output .= $errorMsg;
            $output .= '</div>';
        } else {

            $headers = [];
            $headers[] = 'From: ' . sanitize_text_field($_REQUEST['ContactUsName']) . ' <' . sanitize_text_field($_REQUEST['ContactUsEmail']) . '>';
            $headers[] = 'Content-Type: text/html; charset=UTF-8';

            $result = wp_mail($to, sanitize_text_field($_REQUEST['ContactUsSubject']), sanitize_text_field($_REQUEST['ContactUsMessage']), $headers);

            if ($result) {

                return "<p><big>Thank you for your email. We'll get in touch with you shortly.</big></p>";
            } else {
                $output .= '<h4 style="color: #c1001b; text-align: center;">Error sending email. Please try again later.</h4>';
            }
        }
    }



    $output .= '<form class="copy form-data" action="/customer-service" method="post" id="pm-contact-us-form">';

    $output .= '<p>
                    <label for="ContactUsName">Your Name:</label>
                    <input name="ContactUsName" type="text" id="ContactUsName" class="news-field" data-lpignore="true" required
                        value="' . (isset($_REQUEST['ContactUsName']) ? sanitize_text_field($_REQUEST['ContactUsName']) : '') . '">
                </p>';

    $output .= '<p>

        <label for="ContactUsEmail">Your Email Address:</label>
        <input name="ContactUsEmail" type="email" id="ContactUsEmail" class="news-field" data-lpignore="true" required
            value="' . (isset($_REQUEST['ContactUsEmail']) ? sanitize_text_field($_REQUEST['ContactUsEmail']) : '') . '">
    </p>';

    $output .= '<p>
        <label for="ContactUsSubject">Subject:</label>
        <input name="ContactUsSubject" type="text" id="ContactUsSubject" class="news-field" data-lpignore="true"
            required
            value="' . (isset($_REQUEST['ContactUsSubject']) ? sanitize_text_field($_REQUEST['ContactUsSubject']) : '') . '">
    </p>';

    $output .= '<p>
        <label for="ContactUsMessage">Message:</label>
        <textarea name="ContactUsMessage" rows="10" cols="20"
            id="ContactUsMessage">' . (isset($_REQUEST['ContactUsMessage']) ? sanitize_text_field($_REQUEST['ContactUsMessage']) : '') . '</textarea>
    </p>';

    $output .= '<div id="captcha_element"></div>
    <script type="text/javascript">
    function captchaOnloadCallback() {
        grecaptcha.render("captcha_element", {
            //"sitekey": "6Lde7qkUAAAAAG8w69p-Qr0B6AnTsJzxlOAM70Ab"
            "sitekey": "6LeDShkTAAAAAPOq2_fIcUUz5reCx09BYUCO8KEH"
        });
    }
    </script>
    <script type="text/javascript"
        src="https://www.google.com/recaptcha/api.js?onload=captchaOnloadCallback&amp;render=explicit" async=""
        defer=""></script>';
    $output .= '<p class="submit">
                    <input type="submit" name="contactUsSubmit" value="Send" style="width: 140px" />
                </p>';

    $output .= !stristr($to, 'advert') ? '<p>Need to tell us something or ask a question? Simply complete this form and press the "Send" button. We will review your message and respond to you as soon as we can.</p>' : '';

    $output .= '</form>';

    return $output;
}
add_shortcode(
    'pm_contact_us_form',
    'pm_contact_us_form_shortcode_fn'
);