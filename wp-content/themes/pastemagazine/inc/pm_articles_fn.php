<?php

use AmpProject\Validator\Spec\Tag\Var_;

function pm_get_article_lead_img($post_id)
{

    $img = get_field('lead_image', $post_id);

    if (is_numeric($img)) {
        $imgURLArray = wp_get_attachment_image_src($img, 'full');
        return is_array($imgURLArray) ? array_shift($imgURLArray) : ''; //wp_die($post_id . ' - ' . wp_json_encode($imgURLArray));
    }

    return $img;
}


function pm_list_articles($args)
{

    global $wpdb, $wp_query;

    extract($args);

    $adFreq = isset($ad_freq) ? $ad_freq : 0;
    $articleCatSQL = "";
    if (!isset($articles)) {

        $articleCatSQL = !empty($article_cat) ? " AND article_category LIKE '%|$article_cat|%' " : "";
        $articleTypeSQL = !empty($article_type) && !in_array($article_type, ['most-read', 'editors-picks']) ? " AND article_type = '|$article_type|' " : "";
        $articleTagSQL = !empty($article_tag) ? " AND article_tags LIKE '%|$article_tag|%' " : "";
        $artistSQL = !empty($artist) ? " AND artist LIKE '%|$artist|%' " : "";
        $sortSQL = (isset($_GET['sort']) && $_GET['sort'] === 'popular') || $article_type === 'most-read'  ? "ORDER BY visits DESC" : "ORDER BY post_date DESC";
        $editorsSQL = $article_type === 'editors-picks' ? " AND (editors_home_order = 1 OR editors_other_order = 1) " : "";
        $authorSQL = !empty($author) ? " AND (post_author = $author OR co_authors LIKE '%\"$author\"%') " : "";

        $adFreq = (int) get_option('archive_listing_ad_freq') ?? $wp_query->query_vars['posts_per_page'];
        $adSlotIndex = 0;

        $sql = "SELECT SQL_CALC_FOUND_ROWS
                        ID, 
                        post_title, 
                        post_date, 
                        lead_img_url,
                        permalink,
                        co_authors,
                        rating,
                        article_category
                        FROM pm_article_index 
                        WHERE 1=1 
                        AND post_status = 'publish'
                        $articleCatSQL
                        $articleTypeSQL
                        $articleTagSQL
                        $artistSQL
                        $editorsSQL
                        $authorSQL
                        $sortSQL
                        ";

        // Pagination

        $ppp = !isset($ppp) ? $wp_query->query_vars['posts_per_page'] : $ppp; //get_query_var('posts_per_page');

        $sql .= " LIMIT $ppp";

        $qv_page = get_query_var('page');
        $qv_paged = get_query_var('paged');

        $current_page = $qv_page != '' && $qv_page != 0 ? $qv_page : ($qv_paged != '' && $qv_paged != 0 ? $qv_paged : 1);
        if (isset($current_page) && ($current_page === 0 || is_null($current_page))) $current_page = 1;
        if (isset($current_page) && $current_page > 1) $sql .= " OFFSET " . $ppp * ($current_page - 1);

        //wp_die($sql);

        $articles = $wpdb->get_results($sql);
        $results_count = $wpdb->get_var("SELECT FOUND_ROWS()");
    }

    if (!empty($articles)) {

        // Set the global query args
        $wp_query->query_vars['results_count'] = $results_count;
        $wp_query->query_vars['current_page'] = $current_page;


        foreach ($articles as $i => $a) {

            $authors = json_decode($a->co_authors, true);
            $article_cat = !isset($article_cat) ? implode(', ', array_filter(explode('|', $a->article_category))) : $article_cat;
            $article_type = !isset($article_type) ? implode(', ', array_filter(explode('|', $a->article_type))) : $article_type;

            if ($adFreq > 0 && $i > 0 && $i % $adFreq === 0) {
                $adSlotIndex++;
                echo '<li><div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div></li>';
            }
?>

<li class="grid-x grid-padding-x">
    <?php

                $ytID = get_post_meta($a->ID, 'additional_article_fields_youtube_id', true);
                $leadImgUrl = (empty($a->lead_img_url) || stristr($a->lead_img_url, 'p_icon_lead')) && $ytID != '' ? 'https://i3.ytimg.com/vi/' . $ytID . '/mqdefault.jpg' : $a->lead_img_url;
                
                $titleBlockClass = 'noimage';
                
                if (!empty($leadImgUrl) && !stristr($leadImgUrl, 'p_icon_lead')) {
                
                    //$imgClass = stristr($leadImgUrl, 'i3.ytimg') || !empty($ytID)  ? 'youtube-square' : '';
                    
                    list($width, $height) = getimagesize($leadImgUrl);
                    
                    $imgClass = $width > $height ? 'youtube-square' : '';
                    
                    $titleBlockClass = '';

                ?>
    <a class="large-3 medium-3 cell image youtube <?php echo $imgClass; ?>" href="<?php echo $a->permalink; ?>"
        aria-label="<?php echo wp_strip_all_tags($a->post_title); ?>">
        <picture class="lazyload">
            <source media="(min-width:40em)" srcset="<?php echo $leadImgUrl; ?>">
            <img src="<?php echo $leadImgUrl; ?>" alt="<?php echo wp_strip_all_tags($a->post_title); ?>" />
        </picture>
    </a>
    <?php } ?>
    <a class="auto cell copy-container <?php echo $titleBlockClass;?>" href="<?php echo $a->permalink; ?>">
        <?php echo $articleCatSQL === '' || isset($args['articles']) ? '<b class="type">' . implode(", ", array_filter(explode('|', $a->article_category))) . '</b>' : ''; ?>
        <b class="title"><?php echo $a->post_title; ?></b>
        <?php echo '<b class="byline">By ' . pm_print_authors($authors) . '</b>'; ?>
        <?php echo '<b class="time">' . date('F j, Y | g:ia', strtotime($a->post_date)) . '</b>'; ?>
        <?php echo $article_type === 'reviews' && (float) $a->rating != 0 ? '<b class="rating"><i class="text">Rating</i><i class="number">' . $a->rating . '</i></b>' : ''; ?>
    </a>

</li>

<?php   }
    }
}




function pm_list_crawdaddy_articles()
{

    global $wpdb;

    $adFreq = 15;

    $adSlotIndex = 0;

    $sql = "SELECT SQL_CALC_FOUND_ROWS
                        ID, 
                        post_title, 
                        post_date, 
                        permalink,
                        co_authors
                        FROM pm_article_index 
                        WHERE 1=1 
                        AND post_status = 'publish'
                        AND article_category LIKE '%crawdaddy%'";


    $articles = $wpdb->get_results($sql);
    //$results_count = $wpdb->get_var("SELECT FOUND_ROWS()");


    if (!empty($articles)) {

        foreach ($articles as $i => $a) {

            $authors = json_decode($a->co_authors, true);
            $article_cat = !isset($article_cat) ? implode(', ', array_filter(explode('|', $a->article_category))) : $article_cat;

            if ($adFreq > 0 && $i > 0 && $i % $adFreq === 0) {
                $adSlotIndex++;
                echo '<li class="dfp"><b id="mid_leaderboard_rectangle_' . $adSlotIndex . '"><div id="aax_mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></b></li>';
            }
        ?>

<li class="grid-x grid-padding-x">
    <a href="<?php echo $a->permalink; ?>" class="auto cell copy-container noimage">
        <b class="title"><?php echo wp_strip_all_tags($a->post_title); ?></b>
        <b class="byline">By <?php echo pm_print_authors($authors); ?></b>
        <b class="time"><?php echo date('F j, Y', strtotime($a->post_date)); ?></b>
    </a>
</li>

<?php   }
    }
}




function pm_pagination($args)
{

    extract($args);

    $output = '';

    $pageCount = ceil($results_count / $ppp);

    if ($pageCount > 1) {

        global $wp_rewrite, $wp;
        $current_url = home_url(add_query_arg(array(), $wp->request));

        $output .= '<div class="pm_pagination">
                        <div class="pm_pagination_results">';

        $from = $ppp * $current_page - $ppp + 1;
        $to = ($results_count - ($results_count - $ppp * $current_page) > $results_count) ? $results_count : $results_count - ($results_count - $ppp * $current_page);
        $range = $from . ' - ' . $to;

        if ($results_count > $ppp) {
            $output .= "Showing items $range / $results_count total";
        }

        $output .= '    </div>';

        $output .= '<div class="pm_pagination_links">';

        //$paged = $paged == '' ? (get_query_var('paged') ? intval(get_query_var('paged')) : 1) : $paged;
        $pagenum_link = preg_replace('/\/page\/(\d+)/', '', $current_url);

        $query_args = array();
        $url_parts = explode('?', $pagenum_link);
        $pageCount = ceil($results_count / $ppp);

        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $filter_url_duplicates = array_unique(explode('/', $url_parts[0]));

        $pagenum_link = implode('/', $filter_url_duplicates);
        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);

        /* if ($pagenum_link == '') {
            if (isset($_REQUEST['category']) && is_numeric($_REQUEST['category'])) {
                $pagenum_link = get_term_link((int) $_REQUEST['category']);
            }
        } */

        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $pageCount,
            'current' => $current_page,
            'mid_size' => 2,
            'end_size' => 2,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => esc_html__('&lsaquo; Previous', 'pastemagazine'),
            'next_text' => esc_html__('Next &rsaquo;', 'pastemagazine'),
        ));

        $output .= $links;

        $output .= '</div>
                </div>';
    }

    return $output;
}


function pm_single_article_ads($content)
{
    global $post;

    if (is_admin() || has_term('audio', 'article-type', $post) || is_page()) return $content;

    $adCount = 0;

    if (preg_match_all('/<ad\/|<ad><\/ad>|<ad>/', $content, $matches)) {
        $adCount = count($matches[0]);
    }

    if ($adCount === 0) {

        $paragraph_ad_limit = get_option('single_article_ad_word_count') ?? 300;

        //$content = str_ireplace('<ad></ad>', '', $content);

        $paragraphs = [];
        $split = explode('</p>', $content);

        foreach ($split as $paragraph) {

            //filter out empty paragraphs
            if (strlen($paragraph) > 3) {
                $paragraphs[] = $paragraph . '</p>';
            }
        }


        /* $paragraphs = array_map('htmlentities', $paragraphs);
        echo '<pre style="white-space: pre-wrap">';
        var_dump($paragraphs);
        echo '</pre>';
        die; */

        // set the counter to 0
        $paragraph_length = 0;

        foreach ($paragraphs as $i => $p) {

            $paragraph_length += (int) str_word_count($p);

            if ($paragraph_length >= $paragraph_ad_limit && $adCount < 10) {

                // Increase the offset counter
                $adCount++;

                // insert the <ad> tag
                array_splice($paragraphs, $i + $adCount, 0, '<ad></ad>');

                // reset the counter
                $paragraph_length = 0;
            }
        }

        $content = implode('', $paragraphs);
    }

    /* $paragraphs = array_map('htmlentities', $paragraphs);
    echo '<pre style="white-space: pre-wrap">';
    var_dump($paragraphs);
    echo '</pre>';
    die; */

    // Transform the <ad> tags
    global $adSlotIndex;
    $adSlotIndex = 0;

    $content = preg_replace_callback('/<ad><\/ad>/', function ($matches) use (&$adSlotIndex) {
        if ($adSlotIndex >= 10) return '';
        $adSlotIndex++;
        return '<div class="dfp" data-chars=""><div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div></div>';
    }, $content);


    return $content;
}
add_filter('the_content', 'pm_single_article_ads', 20, 1);


function pm_single_article_html($content)
{
    
    $content = str_replace('xdata-src', 'src', $content);
    $content = str_replace('<small>', '<small class="small">', $content);
    
    
    return $content;
}
add_filter('the_content', 'pm_single_article_html', 15, 1);



function pm_flush_specific_caches($post_id, $post, $update)
{
    
    if (!function_exists('w3tc_flush_url')) return;
    
    $home = home_url() . '/';
    $article_category = wp_get_object_terms($post_id, 'article-category');
    $article_type = wp_get_object_terms($post_id, 'article-type');

    $urls = [
        $home,
        $home .  $article_category[0]->slug,
        $home .  $article_type[0]->slug,
        $home . 'articles/' . $article_category[0]->slug . '/' . $article_type[0]->slug,
    ];

    foreach ($urls as $url) {
        w3tc_flush_url($url);
        \W3TC\Util_Http::get($url, array('user-agent' => 'WordPress'));
    }
}
add_action('save_post_article', 'pm_flush_specific_caches', 10, 3);





function _pm_filter_galleries()
{

    if (!isset($_GET['pm_galleries'])) return;

    ini_set('memory_limit', '12000M');
    set_time_limit(1000);

    global $wpdb;

    if ($audio = $wpdb->get_results("SELECT ID FROM pm_article_index WHERE article_type = '|galleries|'")) {

        foreach ($audio as $a) {
            wp_delete_post($a->ID, true);
        }
    }
}
add_action('init', '_pm_filter_galleries');


function _pm_list_articles_with_missing_thumb()
{

    if (!isset($_GET['pm_thumb'])) return;

    ini_set('memory_limit', '12000M');
    set_time_limit(1000);

    global $wpdb;

    $limit = preg_match('/(\d*),(\d*)/', $_GET['pm_thumb']) ? " LIMIT {$_GET['pm_thumb']}" : "";

    $sql = "SELECT ID, post_title, permalink, article_category, article_type FROM `pm_article_index` WHERE lead_img_url = '' OR (SELECT meta_value FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = '_thumbnail_id') = '' $limit";

    //wp_die($sql);

    if ($articles = $wpdb->get_results($sql)) {

        echo '<style>
        
            table tbody tr:nth-child(even) {
                background: #eee;
            }
            
            table tbody td {
                padding: 3px;
            }
            
        </style>';

        echo '<table>
                <thead>
                  <tr>
                    <th>WP ID</th>
                    <th>Title</th>
                    <th>Permalink</th>
                    <th>Article Category</th>
                    <th>Article Type</th>
                  </tr>
                </thead>
                <tbody>';

        foreach ($articles as $a) {
            echo "<tr>
                    <td>$a->ID</td>
                    <td>$a->post_title | <a target='_blank' href='" . get_edit_post_link($a->ID) . "'>Edit</a> | <a target='_blank' href='$a->permalink'>View</a></td>
                    <td>$a->permalink</td>
                    <td>$a->article_category</td>
                    <td>$a->article_type</td>
                  </tr>";
        }

        echo '</tbody>
            </table>';
    }

    die;
}
add_action('init', '_pm_list_articles_with_missing_thumb'); 


class App_Convert_XmlToArray {

    const NAME_ATTRIBUTES = '@attributes';

    const NAME_CONTENT = '@content';

    const NAME_ROOT = '@root';

    /**
     * Convert a given XML String to Array
     *
     * @param string $xmlString
     * @return array|boolean false for failure
     */
    public static function XmlToArray($xmlString) {
        $doc = new DOMDocument();
        $load = $doc->loadXML($xmlString);
        if ($load == false) {
            return false;
        }
        $root = $doc->documentElement;
        $output = self::DOMDocumentToArray($root);
        $output[self::NAME_ROOT] = $root->tagName;
        return $output;
    }

    /**
     * Convert DOMDocument->documentElement to array
     *
     * @param DOMElement $documentElement
     * @return array
     */
    protected static function DOMDocumentToArray($documentElement) {
        $return = array();
        switch ($documentElement->nodeType) {

            case XML_CDATA_SECTION_NODE:
                $return = trim($documentElement->textContent);
                break;
            case XML_TEXT_NODE:
                $return = trim($documentElement->textContent);
                break;

            case XML_ELEMENT_NODE:
                for ($count=0, $childNodeLength=$documentElement->childNodes->length; $count<$childNodeLength; $count++) {
                    $child = $documentElement->childNodes->item($count);
                    $childValue = self::DOMDocumentToArray($child);
                    if(isset($child->tagName)) {
                        $tagName = $child->tagName;
                        if(!isset($return[$tagName])) {
                            $return[$tagName] = array();
                        }
                        $return[$tagName][] = $childValue;
                    }
                    elseif($childValue || $childValue === '0') {
                        $return = (string) $childValue;
                    }
                }
                if($documentElement->attributes->length && !is_array($return)) {
                    $return = array(self::NAME_CONTENT=>$return);
                }

                if(is_array($return))
                {
                    if($documentElement->attributes->length)
                    {
                        $attributes = array();
                        foreach($documentElement->attributes as $attrName => $attrNode)
                        {
                            $attributes[$attrName] = (string) $attrNode->value;
                        }
                        $return[self::NAME_ATTRIBUTES] = $attributes;
                    }
                    foreach ($return as $key => $value)
                    {
                        if(is_array($value) && count($value)==1 && $key!=self::NAME_ATTRIBUTES)
                        {
                            $return[$key] = $value[0];
                        }
                    }
                }
                break;
        }
        return $return;
    }

}

function _pm_multipage_content()
{

    if (!isset($_GET['pm_multi'])) return;
    
    
    $json = file_get_contents(ABSPATH . 'xmltojson.json');
    
    $arr = json_decode($json, true);
    
    global $wpdb;
    
    foreach($arr['root']['response'] as $i => $r) {
        
        $slug = rtrim($r['list']['i']['slug'], '-');
        
        $permalink = $wpdb->get_var("SELECT permalink FROM pm_article_index WHERE post_name = '{$slug}'");
        
        echo $i . ' <a href="'.$permalink.'" target="_blank">'.$permalink.'</a><br>';
        
        /*if ($wpdb->update($wpdb->posts, ['post_content' => $r['list']['i']["body_xml"]["root"]["entry_text"]["__cdata"]], ['post_name' => $r['list']['i']['slug']])) {
            
            echo $i.' | '.$r['list']['i']['slug'].'<br>';
            //echo htmlentities($r['list']['i']["body_xml"]["root"]["entry_text"]["__cdata"]);
            echo '<hr>';
        }*/
        
        //if ($i >= 10) break;
    }
    
die;
    
}
add_action('init', '_pm_multipage_content'); 







function pm_upload_file_by_url($image_url, $media_name)
{

    // it allows us to use download_url() and wp_handle_sideload() functions
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    // download to temp dir
    $temp_file = download_url($image_url);

    if (is_wp_error($temp_file)) {
        return false;
    }

    // move the temp file into the uploads directory
    $file = array(
        'name'     => $media_name,
        'type'     => mime_content_type($temp_file),
        'tmp_name' => $temp_file,
        'size'     => filesize($temp_file),
    );
    $sideload = wp_handle_sideload(
        $file,
        array(
            'test_form'   => false // no needs to check 'action' parameter
        )
    );

    if (!empty($sideload['error'])) {
        // you may return error message if you want
        return false;
    }

    // it is time to add our uploaded image into WordPress media library
    $attachment_id = wp_insert_attachment(
        array(
            'guid'           => $sideload['url'],
            'post_mime_type' => $sideload['type'],
            'post_title'     => basename($sideload['file']),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ),
        $sideload['file']
    );

    if (is_wp_error($attachment_id) || !$attachment_id) {
        return false;
    }

    // update medatata, regenerate image sizes
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata($attachment_id, $sideload['file'])
    );

    return $attachment_id;
}


function _pm_youtube_splash()
{

    if (!isset($_GET['pm_yt'])) return;
    
    ini_set('memory_limit', '12000M');
    set_time_limit(1000);

    global $wpdb;

    //AND ((SELECT meta_value FROM wppm_postmeta WHERE post_id = ID AND meta_key = '_thumbnail_id') = '' OR (SELECT COUNT(*) FROM wppm_postmeta WHERE post_id = ID AND meta_key = '_thumbnail_id') = 0) 

    $sql = "SELECT ai.ID, 
                    ai.permalink,
                   MAX( IF (pm.meta_key = 'additional_article_fields_youtube_id', pm.meta_value, null)) as ytID, 
                   MAX( IF (pm.meta_key = '_thumbnail_id', pm.meta_value, null)) as thumbID 
                   FROM pm_article_index ai 
                   JOIN $wpdb->postmeta pm ON ai.ID = pm.post_id 
                   WHERE 1=1 
                   AND (SELECT meta_value FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = 'additional_article_fields_youtube_id') != ''
                   AND ((SELECT meta_value FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = '_thumbnail_id') = '' OR (SELECT COUNT(*) FROM $wpdb->postmeta WHERE post_id = ID AND meta_key = '_thumbnail_id') = 0)
                   GROUP BY ID";
    
    
    $results = $wpdb->get_results($sql);

    if ($results) {

        foreach ($results as $i => $r) {

            if (strlen($r->ytID) > 5 && empty($thumbID)) {
                $exists = (bool) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title = '$r->ytID.jpg'");
                $exists_txt = $exists ? 'EXISTS' : 'NOT EXISTS';
                echo '#' . $i . ' ' . $r->ID . ' | ' . $r->permalink . ' | ' . $exists_txt . '<br>';

                $attachmentId = $exists ?
                    (int) $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title = '$r->ytID.jpg'")
                    : pm_upload_file_by_url('https://i3.ytimg.com/vi/' . $r->ytID . '/mqdefault.jpg', $r->ytID . '.jpg');

                echo 'Att: ' . $attachmentId . '<br>';
                usleep(2000);
                set_post_thumbnail($r->ID, $attachmentId);
                update_post_meta($r->ID, 'lead_image', $attachmentId);
                $wpdb->update('pm_article_index', ['lead_img_url' => wp_get_attachment_image_url($attachmentId, 'full')], ['ID' => $r->ID]);
                $wpdb->update($wpdb->posts, ['post_parent' => $r->ID], ['ID' => $attachmentId]);
                usleep(2000);
            }

            if ($i == 2500) break;
        }
    }

    die;
}
add_action('init', '_pm_youtube_splash'); 



function pm_single_article_bottom_artist_section($args)
{
    global $wpdb;

    extract($args);

    $output = '';

    if (!empty($artist)) {

        $sql = "SELECT ID, 
                        post_title,
                        post_date, 
                        permalink,
                        co_authors,
                        lead_img_url
                        FROM pm_article_index 
                        WHERE 1=1
                        AND post_status = 'publish'
                        AND artist LIKE '%|$artist|%'
                        ORDER BY post_date DESC
                        LIMIT $ppp";

        
        $artistArticles = $wpdb->get_results($sql);

        if (!empty($artistArticles)) {

            ob_start();
        ?>

<div class="grid-x articles-standard">
    <div class="large-12 cell articles-header" style="height: auto;">
        <a class="float-left hed" href="/search?q=<?php echo urlencode($artistLabel); ?>">More from <span
                style="text-transform: capitalize;"><?php echo $artistLabel; ?></span></a>&nbsp;
        <ul class="no-bullet articles grid-margin-x flex-container flex-dir-column">

            <?php foreach ($artistArticles as $aA) {
                            $authors = json_decode($aA->co_authors, true);
                        ?>
            <li class="grid-x grid-padding-x">
                
                <?php

                $ytID = get_post_meta($aA->ID, 'additional_article_fields_youtube_id', true);
                $leadImgUrl = (empty($aA->lead_img_url) || stristr($aA->lead_img_url, 'p_icon_lead')) && $ytID != '' ? 'https://i3.ytimg.com/vi/' . $ytID . '/mqdefault.jpg' : $aA->lead_img_url;
                
                $titleBlockClass = 'noimage';
                
                if (!empty($leadImgUrl) && !stristr($leadImgUrl, 'p_icon_lead')) {
                
                    //$imgClass = stristr($leadImgUrl, 'i3.ytimg') || !empty($ytID)  ? 'youtube-square' : '';
                    
                    list($width, $height) = getimagesize($leadImgUrl);
                    
                    $imgClass = $width > $height ? 'youtube-square' : '';
                    
                    $titleBlockClass = '';
                ?>
                <a class="large-3 medium-3 cell image youtube <?php echo $imgClass; ?>" href="<?php echo $aA->permalink; ?>"
                    aria-label="<?php echo wp_strip_all_tags($aA->post_title); ?>">
                    <picture class="lazyload">
                        <source media="(min-width:40em)" srcset="<?php echo $leadImgUrl; ?>">
                        <img src="<?php echo $leadImgUrl; ?>" alt="<?php echo wp_strip_all_tags($aA->post_title); ?>" />
                    </picture>
                </a>
                <?php } ?>
                
                
                <a href="<?php echo $aA->permalink; ?>" class="auto cell copy-container <?php echo $titleBlockClass;?>">
                    <b class="title"><?php echo $aA->post_title; ?></b>
                    <b class="byline">By <?php echo pm_print_authors($authors); ?></b>
                    <b class="time"
                        data-iso-date="<?php echo date('c', strtotime($aA->post_date)); ?>"><?php echo date('F j, Y | g:ia', strtotime($aA->post_date)); ?></b>
                </a>
            </li>
            <?php } ?>

        </ul>
    </div>
</div>

<?php
            if ($adSlotIndex < 10) {
                $adSlotIndex++;
                echo '<div class="dfp">
                    <div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div>
                </div>';
            }

            $output = ob_get_clean();
        }
    }

    return $output;
}






function pm_single_article_bottom_category_section($args)
{
    global $wpdb;

    extract($args);

    $output = '';

    $categoryArticles = $wpdb->get_results("SELECT ID, 
                        post_title,
                        post_date, 
                        permalink,
                        co_authors
                        FROM pm_article_index 
                        WHERE 1=1
                        AND post_status = 'publish'
                        AND article_category LIKE '%|$articleCat|%'
                        ORDER BY post_date DESC
                        LIMIT $ppp");

    if (!empty($categoryArticles)) {

        ob_start();

        ?>

<div class="grid-x articles-standard" id="article-detail-studio-highlights">
    <div class="large-12 cell articles-header" style="height: auto;">
        <b class="float-left hed">Also in <?php echo $articleCatLabel; ?></b>
        <ul class="no-bullet articles grid-margin-x flex-container flex-dir-column">
            <?php foreach ($categoryArticles as $cA) {
                        $authors = json_decode($cA->co_authors, true);
                    ?>
            <li class="grid-x grid-padding-x">
                <a href="<?php echo $cA->permalink; ?>" class="auto cell copy-container noimage">
                    <b class="title"><?php echo $cA->post_title; ?></b>
                    <b class="byline">By <?php echo pm_print_authors($authors); ?></b>
                    <b class="time"
                        data-iso-date="<?php echo date('c', strtotime($cA->post_date)); ?>"><?php echo date('F j, Y | g:ia', strtotime($cA->post_date)); ?></b>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>

<?php
        $adSlotIndex++; // Pre-increment this variable as it cannot be passed by reference
        if ($adSlotIndex < 10) {
            $adSlotIndex++;
            echo '<div class="dfp">
                    <div id="mid_leaderboard_rectangle_' . $adSlotIndex . '"></div>
                </div>';
        }

        $output = ob_get_clean();
    }

    return $output;
}


/*function pm_articles_sidebar_ajax_js()
{
    global $article_type, $article_cat, $article_cat_label, $artist, $artistLabel, $ppp, $adSlotIndex;
    
    
    
    echo "<script id='pm_articles_sidebar_ajax_js'>
            jQuery(function($){
            
                function randomInteger(min, max) {
                  return Math.floor(Math.random() * (max - min + 1)) + min;
                }

                function pm_article_sidebar_ajax(selector, callback) {

                    $.ajax({
                        url:    '" . admin_url('admin-ajax.php') . "', 
                        method: 'POST',
                        data:   { 
                                    action: 'pm_sidebar_ajax',
                                    callback,
                                    isFrontPage: " . ((int) is_front_page()) . ",
                                    isSingularArticle: " . ((int) is_singular('article')) . ",
                                    articleType: '" . $article_type . "',
                                    articleCat: '" . $article_cat . "',
                                    articleCatLabel: '" . $article_cat_label . "',
                                    artist: '" . $artist . "',
                                    artistLabel: '" . $artistLabel . "',
                                    ppp: " . (!is_null($ppp) ? (int) $ppp : 0) . ",
                                    adSlotIndex: " . (!is_null($adSlotIndex) ? (int) $adSlotIndex : 0) . "
                                },
                        error: function(jqXHR, textStatus, errorThrown) {
                            
                            //location.reload();
                        }
            
                    }).done(function( response ) {
                        
                       // console.log(response);

                        $(selector)
                            .html(response.data)
                            .removeClass('pm_ajax_placeholder');
                        
                    });

                }
            
                //HTML: <div id="pm_sidebar_most_popular_ajax" class="pm_ajax_placeholder"></div>
                
                //<div id="pm_sidebar_editors_ajax" class="pm_ajax_placeholder"></div>
                
                let ajaxItems = [
                    {
                        selector: '#pm_sidebar_most_popular_ajax',
                        callback: 'pm_sidebar_most_popular_shortcode_fn',
                        timeout: 2500
                    },
                    {
                        selector: '#pm_sidebar_editors_ajax',
                        callback: 'pm_sidebar_editors_picks_shortcode_fn',
                        timeout: 2500
                    },
                    {
                        selector: '#pm_sidebar_news_ajax',
                        callback: 'pm_sidebar_news_shortcode_fn',
                        timeout: 2500
                    },
                    {
                        selector: '#pm_single_article_artist_ajax',
                        callback: 'pm_single_article_bottom_artist_section',
                        timeout: randomInteger(2000, 3500)
                    },
                    {
                        selector: '#pm_single_article_category_ajax',
                        callback: 'pm_single_article_bottom_category_section',
                        timeout: 2500
                    }
                ];
                
                $.each(ajaxItems, function(i,v){

                    setTimeout(function(){
                        
                        if ($(v.selector).length > 0) {
                            pm_article_sidebar_ajax(v.selector, v.callback);
                        }
    
                    }, v.timeout);

                });

            });
    </script>";
}
add_action('wp_footer', 'pm_articles_sidebar_ajax_js');
add_action('wp_ajax_pm_sidebar_ajax', 'pm_sidebar_ajax_callback');
add_action('wp_ajax_nopriv_pm_sidebar_ajax', 'pm_sidebar_ajax_callback');


function pm_sidebar_ajax_callback()
{
    $callback = $_REQUEST['callback'];

    $atts = [
        'isFrontPage' => $_REQUEST['isFrontPage'],
        'isSingularArticle' => $_REQUEST['isSingularArticle'],
        'articleCat' => $_REQUEST['articleCat'],
        'articleCatLabel' => $_REQUEST['articleCatLabel'],
        'artist' => $_REQUEST['artist'],
        'artistLabel' => $_REQUEST['artistLabel'],
        'ppp' => $_REQUEST['ppp'],
        'adSlotIndex' => $_REQUEST['adSlotIndex']
    ];

    wp_send_json_success($callback($atts));
}*/
