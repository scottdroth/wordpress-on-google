<div class="grid-x grid-padding-x landing-top">
    <div class="large-12 cell articles-header show-for-small-only">
        <a href="/articles/article-type/features/" class="float-left hed">Featured</a>
    </div>
    <div class="large-12 cell articles">
        <ul>
            <?php

            global $wpdb, $article_cat;

            $articleCategorySQL = '';

            if (!empty($article_cat)) {

                $articleCategorySQL = " AND article_category LIKE '%|$article_cat|%'";

                /* $articleCategoryTermID = $wpdb->get_var(
                    "SELECT t.term_id FROM $wpdb->terms t JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'article-category' AND t.slug = '$article_cat'"
                );

                if (is_numeric($articleCategoryTermID)) {
                    $articleCategoryTermID = intval($articleCategoryTermID);
                    $articleCategorySQL = " AND (SELECT COUNT(*) FROM $wpdb->term_relationships WHERE object_id = ID AND term_taxonomy_id = $articleCategoryTermID) > 0";
                } */
            }

            $topOrderSQL = is_front_page() ? " AND home_top_order > 0" : " AND other_top_order > 0";

            $orderBySQL = is_front_page() ? " ORDER BY home_top_order ASC, post_date DESC" : " ORDER BY other_top_order ASC, post_date DESC";

            global $idsNotIn;
            $idsNotIn = [];

            $topArticles = $wpdb->get_results("SELECT ID, 
                                                    post_title, 
                                                    post_date, 
                                                    post_author,
                                                    lead_img_url
                                                    FROM pm_article_index
                                                    WHERE 1=1
                                                    AND post_status = 'publish'
                                                    $articleCategorySQL
                                                    $topOrderSQL
                                                    $orderBySQL
                                                    LIMIT 3");

            //var_dump($topArticles);

            if (!$topArticles || count($topArticles) < 3) {


                if (is_array($topArticles) && !empty($topArticles)) {

                    $limit = 3 - count($topArticles);

                    foreach ($topArticles as $tA) {
                        $idsNotIn[] = $tA->ID;
                    }

                    $topArticlesAdditional = $wpdb->get_results("SELECT ID, 
                                                            post_title, 
                                                            post_date, 
                                                            post_author,
                                                            lead_img_url 
                                                            FROM pm_article_index
                                                            WHERE 1=1 
                                                            AND post_status = 'publish'
                                                            AND ID NOT IN (" . implode(',', $idsNotIn) . ")
                                                            $articleCategorySQL
                                                            ORDER BY post_date DESC
                                                            LIMIT $limit");

                    $topArticles = array_merge($topArticles, $topArticlesAdditional);
                } else {

                    $topArticles = $wpdb->get_results("SELECT ID, 
                                                            post_title, 
                                                            post_date, 
                                                            post_author,
                                                            lead_img_url
                                                            FROM pm_article_index
                                                            WHERE 1=1 
                                                            AND post_status = 'publish'
                                                            $articleCategorySQL
                                                            ORDER BY post_date DESC
                                                            LIMIT 3");

                    foreach ($topArticles as $tA) {
                        $idsNotIn[] = $tA->ID;
                    }
                }
            }


            if (!empty($topArticles)) {

                foreach ($topArticles as $tA) {

                    $permalink = get_the_permalink($tA->ID);
                    $thumbnailLargeURL = get_the_post_thumbnail_url($tA->ID, 'full');
                    $thumbnailSmallURL = $tA->lead_img_url;
                    $author = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID = $tA->post_author");
                    $articleCategoryLabel = implode(", ", wp_get_object_terms($tA->ID, 'article-category', ['fields' => 'names']));
            ?>

            <li>
                <a class="image" href="<?php echo $permalink; ?>">
                    <picture data-sizes="[&quot;(min-width: 40em)&quot;,&quot;(min-width: 64em)&quot;]" class="main"
                        data-sources="[&quot;<?php echo $thumbnailSmallURL; ?>&quot;,&quot;<?php echo $thumbnailLargeURL; ?>&quot;,&quot;<?php echo $thumbnailLargeURL; ?>&quot;]">
                        <source media="(min-width: 40em)" srcset="<?php echo $thumbnailLargeURL; ?>">
                        <source media="(min-width: 64em)" srcset="<?php echo $thumbnailLargeURL; ?>"><img
                            alt="<?php echo wp_strip_all_tags($tA->post_title); ?>"
                            src="<?php echo $thumbnailSmallURL; ?>">
                    </picture>
                </a>
                <a class="copy-container" href="<?php echo $permalink; ?>">
                    <b class="type hide-for-medium">Movies</b>
                    <b class="title"><?php echo $tA->post_title; ?></b>
                    <b class="byline hide-for-medium">By <?php echo $author; ?></b>
                    <b class="time hide-for-medium"><?php echo get_the_date('F j, Y | g:ia', $tA->ID); ?></b>
                </a>
            </li>

            <?php }
            } ?>

        </ul>
    </div>
</div>